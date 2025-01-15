DROP TABLE IF EXISTS lecturers;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS subjects;
DROP TABLE IF EXISTS groups;
DROP TABLE IF EXISTS faculties;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS schedules;
CREATE TABLE faculty (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    faculty_name TEXT NOT NULL
);
CREATE TABLE room (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    room_name TEXT NOT NULL,
    faculty_id INTEGER NOT NULL,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);
CREATE TABLE group (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    group_name TEXT NOT NULL
);
CREATE TABLE group_student (
    group_id INTEGER NOT NULL,
    student_id INTEGER NOT NULL,
    FOREIGN KEY (group_id) REFERENCES group(id),
    FOREIGN KEY (student_id) REFERENCES student(id)
);
CREATE TABLE subject (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    subject_name TEXT NOT NULL,
    subject_form TEXT NOT NULL
);
CREATE TABLE lecturer (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    lecturer_name TEXT NOT NULL,
    title TEXT NOT NULL
);
CREATE TABLE student (
    id INTEGER PRIMARY KEY,
    faculty_id INTEGER NOT NULL,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);
CREATE TABLE schedules (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    subject_id INTEGER NOT NULL,
    lecturer_id INTEGER NOT NULL,
    faculty_id INTEGER NOT NULL,
    group_id INTEGER NOT NULL,
    room_id INTEGER NOT NULL,
    time_start TEXT NOT NULL,
    time_end TEXT NOT NULL,
    color TEXT,
    FOREIGN KEY (subject_id) REFERENCES subject(id),
    FOREIGN KEY (lecturer_id) REFERENCES lecturer(id),
    FOREIGN KEY (faculty_id) REFERENCES faculty(id),
    FOREIGN KEY (group_id) REFERENCES group(id),
    FOREIGN KEY (room_id) REFERENCES room(id)
);