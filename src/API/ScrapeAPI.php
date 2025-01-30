<?php

namespace App\API;

use App\Model\Lecturer;
use App\Model\Faculty;
use App\Model\Subject;
use App\Model\Group;
use App\Model\Student;
use App\Model\GroupStudent;
use App\Model\Room;
use App\Model\Schedule;
use App\Service\Config;
use PDO;
use PDOException;
use Exception;

class ScrapeAPI
{
    private PDO $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                Config::get('db_dsn'),
                Config::get('db_user'),
                Config::get('db_pass'),
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            throw new Exception("Błąd połączenia z bazą danych: " . $e->getMessage());
        }
    }

    public function scrapeSchedule($startDate, $endDate)
    {
        $baseUrl = "https://plan.zut.edu.pl/schedule.php?kind=teacher&query=";
        $alphabet = array_merge(range('A', 'E'));
        
        $allData = [];

        foreach ($alphabet as $letter) {
            $url = $baseUrl . urlencode($letter);
            $response = @file_get_contents($url);
            
            if ($response === null || empty($response)) {
                continue;
            }

            $data = json_decode($response, true);
            
            if ($data) {
                echo "Pobrano dane dla litery: $letter\n";
                $allData = array_merge($allData, $data);
            } else {
                echo "Brak danych dla litery: $letter\n";
            }
        }

        if (empty($allData)) {
            throw new Exception("Błąd pobierania danych z API");
        }

        foreach ($allData as $lecturerData) {
            if (isset($lecturerData['item'])) {
                $lecturer = new Lecturer();
                $lecturer->setLecturerName($lecturerData['item']);
                $lecturer->setTitle('');
                $lecturer->save();
                echo "Zapisano wykładowcę: {$lecturerData['item']}\n";

                $this->fetchAndSaveSchedule($lecturerData['item'], $startDate, $endDate, null);
            } else {
                echo "Brak nazwy wykładowcy w danych: " . json_encode($lecturerData) . "\n";
            }
        }
    }

    private function fetchAndSaveSchedule($lecturerName, $startDate, $endDate, $studentNumber = null)
    {
        $url = "https://plan.zut.edu.pl/schedule_student.php?teacher=" . urlencode($lecturerName) . "&start=" . urlencode($startDate) . "&end=" . urlencode($endDate);
        $response = @file_get_contents($url);

        if ($response === false) {
            echo "Błąd pobierania danych dla wykładowcy: $lecturerName\n";
            return;
        }

        $data = json_decode($response, true);

        if ($data) {
            echo "Pobrano dane dla wykładowcy: $lecturerName\n";
            foreach ($data as $scheduleData) {
                if (isset($scheduleData['subject']) && isset($scheduleData['lesson_form'])) {
                    $subjectName = $scheduleData['subject'];
                    $subjectForm = $scheduleData['lesson_form'];

                    $subjectId = Schedule::findSubjectId($subjectName, $subjectForm);
                    if (!$subjectId) {
                        $subject = new Subject();
                        $subject->setSubjectName($subjectName);
                        $subject->setSubjectForm($subjectForm);
                        $subject->save();
                        $subjectId = $subject->getId();
                        echo "Zapisano przedmiot: $subjectName, forma: $subjectForm\n";
                    } else {
                        echo "Przedmiot już istnieje: $subjectName, forma: $subjectForm\n";
                    }

                    // Znajdź lub zapisz wykładowcę
                    $lecturerId = Schedule::findLecturerId($lecturerName);
                    if (!$lecturerId) {
                        $lecturer = new Lecturer();
                        $lecturer->setLecturerName($lecturerName);
                        $lecturer->setTitle('');
                        $lecturer->save();
                        $lecturerId = $lecturer->getId();
                        echo "Zapisano wykładowcę: $lecturerName\n";
                    } else {
                        echo "Wykładowca już istnieje: $lecturerName\n";
                    }

                    // Znajdź lub zapisz wydział
                    $facultyName = $this->extractFacultyName($scheduleData['room']);
                    if ($facultyName === null) {
                        echo "Brak danych wydziału dla sali: " . $scheduleData['room'] . "\n";
                        continue;
                    }
                    $facultyId = Schedule::findFacultyId($facultyName);
                    if (!$facultyId) {
                        $faculty = new Faculty();
                        $faculty->setFacultyName($facultyName);
                        $faculty->save();
                        $facultyId = $faculty->getId();
                        echo "Zapisano wydział: $facultyName\n";
                    } else {
                        echo "Wydział już istnieje: $facultyName\n";
                    }

                    // Znajdź lub zapisz grupę
                    if (isset($scheduleData['group_name'])) {
                        $groupName = $scheduleData['group_name'];
                        $groupId = Schedule::findGroupId($groupName);
                        if (!$groupId) {
                            $group = new Group();
                            $group->setGroupName($groupName);
                            $group->save();
                            $groupId = $group->getId();
                            echo "Zapisano grupę: $groupName\n";
                        } else {
                            echo "Grupa już istnieje: $groupName\n";
                        }
                    } else {
                        echo "Brak danych grupy w danych: " . json_encode($scheduleData) . "\n";
                        continue;
                    }

                    // Znajdź lub zapisz salę
                    $roomName = $scheduleData['room'];
                    $roomId = Schedule::findRoomId($roomName, $facultyId);
                    if (!$roomId) {
                        $room = new Room();
                        $room->setRoomName($roomName);
                        $room->setFacultyId($facultyId);
                        $room->save();
                        $roomId = $room->getId();
                        echo "Zapisano salę: $roomName\n";
                    } else {
                        echo "Sala już istnieje: $roomName\n";
                    }

                    // Zapisz dane harmonogramu
                    $schedule = new Schedule();
                    $schedule->setSubjectId($subjectId);
                    $schedule->setLecturerId($lecturerId);
                    $schedule->setFacultyId($facultyId);
                    $schedule->setGroupId($groupId);
                    $schedule->setRoomId($roomId);
                    $schedule->setTimeStart($scheduleData['start']);
                    $schedule->setTimeEnd($scheduleData['end']);
                    $schedule->setColor($scheduleData['color'] ?? '#FF0000');
                    $schedule->save();
                } else {
                    echo "Brak danych przedmiotu lub formy zajęć w danych: " . json_encode($scheduleData) . "\n";
                }
            }
        } else {
            echo "Brak danych harmonogramu dla wykładowcy: $lecturerName\n";
        }
    }

    public function fetchAndSaveStudent($studentNumber, $startDate, $endDate)
    {
        $url = "https://plan.zut.edu.pl/schedule_student.php?number=" . urlencode($studentNumber) . "&start=" . urlencode($startDate) . "&end=" . urlencode($endDate);
        $response = @file_get_contents($url);

        if ($response === false) {
            echo "Błąd pobierania danych dla studenta: $studentNumber\n";
            return;
        }

        $data = json_decode($response, true);

        if ($data) {
            echo "Pobrano dane dla studenta: $studentNumber\n";
            echo "Dane studenta: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
            $student = new Student();
            $student->setId($studentNumber);
            $student->save();
            echo "Zapisano studenta: $studentNumber\n";

            // Process group data
            foreach ($data as $scheduleData) {
                if (isset($scheduleData['group_name'])) {
                    $groupName = $scheduleData['group_name'];

                    $group = Group::findGroup($groupName);
                    if (!$group) {
                        $group = new Group();
                        $group->setGroupName($groupName);
                        $group->save();
                        echo "Zapisano grupę: $groupName\n";
                    } else {
                        echo "Grupa już istnieje: $groupName\n";
                    }

                    // Save group-student relationship
                    $groupStudent = new GroupStudent();
                    $groupStudent->setGroupId($group->getId());
                    $groupStudent->setStudentId($studentNumber);
                    $groupStudent->save();
                    echo "Zapisano relację grupa-student: Grupa ID {$group->getId()}, Student ID $studentNumber\n";
                } else {
                    echo "Brak danych grupy w danych studenta: " . json_encode($scheduleData) . "\n";
                }
            }
        } else {
            echo "Brak danych dla studenta: $studentNumber\n";
        }
    }

    private function extractFacultyName($room)
    {
        $faculties = $this->getFacultiesList();
        foreach ($faculties as $faculty) {
            if (strpos($room, $faculty) !== false) {
                return $faculty;
            }
        }
        return 'Unknown Faculty';
    }

    private function getFacultiesList()
    {
        return ['WI', 'WE', 'WA', 'WEkon', 'WTMiT', 'WTiICH', 'WBiIS', 'WIMiM', 'WNoZiR', 'WBiHZ', 'WKSiR'];
    }
}