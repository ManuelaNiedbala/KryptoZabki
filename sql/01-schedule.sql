create table schedule (
    id integer not null constraint schedule_pk primary key autoincrement,
    subject text not null,
    content text not null
);