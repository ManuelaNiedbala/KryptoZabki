DROP TABLE IF EXISTS Lecturer;
DROP TABLE IF EXISTS Student;
DROP TABLE IF EXISTS Subject;
DROP TABLE IF EXISTS Groups;
DROP TABLE IF EXISTS Faculty;
DROP TABLE IF EXISTS Room;
DROP TABLE IF EXISTS Schedule;
CREATE TABLE Faculty (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    faculty_name VARCHAR(255) NOT NULL
);
CREATE TABLE Room (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    room_name VARCHAR(255) NOT NULL,
    faculty_id INTEGER NOT NULL,
    FOREIGN KEY (faculty_id) REFERENCES Faculty(id)
);
CREATE TABLE Groups (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    group_name VARCHAR(255) NOT NULL,
    faculty_id INTEGER NOT NULL,
    FOREIGN KEY (faculty_id) REFERENCES Faculty(id)
);
CREATE TABLE Subject (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    subject_name VARCHAR(255) NOT NULL,
    subject_form VARCHAR(255) NOT NULL
);
CREATE TABLE Lecturer (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    lecturerName VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL
);
CREATE TABLE Student (
    id INTEGER PRIMARY KEY,
    group_id INTEGER,
    FOREIGN KEY (group_id) REFERENCES Groups(id)
);
CREATE TABLE Schedule (
    schedule_id INTEGER PRIMARY KEY AUTOINCREMENT,
    subject_id INTEGER NOT NULL,
    lecturer_id INTEGER NOT NULL,
    faculty_id INTEGER NOT NULL,
    group_id INTEGER NOT NULL,
    room_id INTEGER NOT NULL,
    time_start DATETIME NOT NULL,
    time_end DATETIME NOT NULL,
    FOREIGN KEY (subject_id) REFERENCES Subject(id),
    FOREIGN KEY (lecturer_id) REFERENCES Lecturer(id),
    FOREIGN KEY (faculty_id) REFERENCES Faculty(id),
    FOREIGN KEY (group_id) REFERENCES Groups(id),
    FOREIGN KEY (room_id) REFERENCES Room(id)
);