DROP TABLE IF EXISTS lecturers;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS subjects;
DROP TABLE IF EXISTS groups;
DROP TABLE IF EXISTS faculties;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS schedules;
CREATE TABLE faculty (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    faculty_name VARCHAR(255) NOT NULL
);
CREATE TABLE room (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    room_name VARCHAR(255) NOT NULL,
    faculty_id INTEGER NOT NULL,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);
CREATE TABLE group (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    group_name VARCHAR(255) NOT NULL,
    faculty_id INTEGER NOT NULL,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);
CREATE TABLE subject (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    subject_name VARCHAR(255) NOT NULL,
    subject_form VARCHAR(255) NOT NULL
);
CREATE TABLE lecturer (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    lecturer_name VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL
);
CREATE TABLE student (
    id INTEGER PRIMARY KEY,
    group_id INTEGER,
    FOREIGN KEY (group_id) REFERENCES group(id)
);
CREATE TABLE schedules (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    subject_id INTEGER,
    lecturer_id INTEGER,
    faculty_id INTEGER,
    group_id INTEGER,
    room_id INTEGER,
    time_start DATETIME,
    time_end DATETIME,
    FOREIGN KEY (subject_id) REFERENCES subject(id),
    FOREIGN KEY (lecturer_id) REFERENCES lecturer(id),
    FOREIGN KEY (faculty_id) REFERENCES faculty(id),
    FOREIGN KEY (group_id) REFERENCES group(id),
    FOREIGN KEY (room_id) REFERENCES room(id)
);