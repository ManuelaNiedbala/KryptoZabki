<?php

namespace App\API;

use App\Model\Lecturer;
use App\Model\Faculty;
use App\Model\Subject;
use App\Model\Group;
use App\Model\Student;
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
            
            if ($response === false) {
                echo "Błąd pobierania danych dla litery: $letter\n";
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

                $this->fetchAndSaveSchedule($lecturerData['item'], $startDate, $endDate);
            } else {
                echo "Brak nazwy wykładowcy w danych: " . json_encode($lecturerData) . "\n";
            }
        }
    }

    private function fetchAndSaveSchedule($lecturerName, $startDate, $endDate)
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
            $foundFaculties = [];
            foreach ($data as $scheduleData) {
                if (isset($scheduleData['subject']) && isset($scheduleData['lesson_form'])) {
                    $subjectName = $scheduleData['subject'];
                    $subjectForm = $scheduleData['lesson_form'];

                    $subject = Subject::findSubject($subjectName, $subjectForm);
                    if (!$subject) {
                        $subject = new Subject();
                        $subject->setSubjectName($subjectName);
                        $subject->setSubjectForm($subjectForm);
                        $subject->save();
                        echo "Zapisano przedmiot: $subjectName, forma: $subjectForm\n";
                    } else {
                        echo "Przedmiot już istnieje: $subjectName, forma: $subjectForm\n";
                    }
                } else {
                    echo "Brak danych przedmiotu lub formy zajęć w danych: " . json_encode($scheduleData) . "\n";
                }

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
                } else {
                    echo "Brak danych grupy w danych: " . json_encode($scheduleData) . "\n";
                }

                if (isset($scheduleData['room'])) {
                    $facultyName = $this->extractFacultyName($scheduleData['room']);
                    if ($facultyName && !in_array($facultyName, $foundFaculties)) {
                        $foundFaculties[] = $facultyName;
                        $faculty = Faculty::findFaculty($facultyName);
                        if (!$faculty) {
                            $faculty = new Faculty();
                            $faculty->setFacultyName($facultyName);
                            $faculty->save();
                            echo "Zapisano wydział: $facultyName\n";
                        } else {
                            echo "Wydział już istnieje: $facultyName\n";
                        }
                    }
                    if (count($foundFaculties) === count($this->getFacultiesList())) {
                        echo "Znaleziono wszystkie wydziały.\n";
                        break;
                    }
                } else {
                    echo "Brak danych sali w danych: " . json_encode($scheduleData) . "\n";
                }
            }
        } else {
            echo "Brak danych harmonogramu dla wykładowcy: $lecturerName\n";
        }
    }

    public function fetchAndSaveStudent($studentNumber)
    {
        $url = "https://plan.zut.edu.pl/schedule_student.php?number=" . urlencode($studentNumber);
        $response = @file_get_contents($url);

        if ($response === false) {
            echo "Błąd pobierania danych dla studenta: $studentNumber\n";
            return;
        }

        $data = json_decode($response, true);

        if ($data) {
            echo "Pobrano dane dla studenta: $studentNumber\n";
            $student = new Student();
            $student->setId($studentNumber);
            if (isset($data['faculty_id'])) {
                $student->setFacultyId($data['faculty_id']);
            }
            $student->save();
            echo "Zapisano studenta: $studentNumber\n";
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
        return null;
    }

    private function getFacultiesList()
    {
        return ['WI', 'WE', 'WA', 'WEkon', 'WTMiT', 'WTiICH', 'WBiIS', 'WIMiM', 'WNoZiR', 'WBiHZ', 'WKSiR'];
    }
}