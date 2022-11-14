drop table if exists 'employees_jobs';
drop table if exists 'employees';
drop table if exists 'works';
drop table if exists 'service_types';

pragma foreign_keys=on;

create table 'service_types' (
	'id' integer primary key autoincrement,
	'name' text not null check(name != ''), 
	'car_category' text not null check(car_category != ''),
	'duration_minutes' integer not null check(duration_minutes > 0),
	'price' real not null check(price > 0),
	unique('name', 'car_category')
);

create table 'employees' (
	'personnel_id' integer primary key autoincrement,
	'first_name' text not null check('first_name' != ''),
	'last_name' text not null check('last_name' != '')
);

create table 'jobs' (
	'id'  integer primary key autoincrement,
	'status' text not null check(status = 'completed' OR status = 'scheduled'),
	'box_number' integer not null check(box_number > 0 AND box_number <= 4),
	'scheduled_for' integer not null,
	'service_id' integer not null,
	unique('scheduled_for', 'box_number'),
	foreign key ('service_id') references 'service_types'(id)
);

create table 'employees_jobs' (
	'job_id' integer not null,
	'employee_id' integer not null,
	'percentage_of_revenue' real not null check(percentage_of_revenue > 0 AND percentage_of_revenue <= 1),
	unique('job_id', 'employee_id'),
	foreign key ('employee_id') references 'employees'('personnel_id') ON DELETE NO ACTION,
	foreign key ('job_id') references 'jobs' ('id') ON DELETE RESTRICT
);

insert into employees (first_name, last_name) values
('Maksim', 'Kuchin'),
('Denis', 'Ivanov'),
('Fedor', 'Fedorov'),
('Alexander', 'Naumov');

insert into service_types (name, car_category, duration_minutes, price) values 
('interior cleaning', 'B', 60, 1000),
('washing outside', 'B', 45, 500),
('full waching', 'B', 150, 2000);

insert into jobs (status, box_number, scheduled_for, service_id) values 
('scheduled', 1, 1670004000, 1),
('scheduled', 2, 1670007000, 2),
('scheduled', 3, 1670010000, 3);

insert into employees_jobs (employee_id, job_id, percentage_of_revenue) values
(1, 1, 1),
(2, 2, 1),
(3, 3, 1);
