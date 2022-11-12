INSERT INTO 'users' ('first_name', 'last_name', 'email', 'gender', 'occupation_id') VALUES
('Maksim', 'Negrya', 'negmaksim@gmail.com', 'male', 1), 
('Egor', 'Nikishkin', 'nikishkin@gmail.com', 'male', 2),
('Maksim', 'Nuyanzin', 'nuyanzin@gmail.com', 'male', 3),
('Alina', 'Pomelova', 'pomelova@gmail.com', 'female', 4),
('Anton', 'Prokopenko', 'prok@gmail.com', 'male', 5);

INSERT INTO 'movies' ('title', 'year') VALUES
('Dune: Part One', 2021),
('Wrath of Man', 2021),
('Nobody', 2021);

INSERT INTO ratings ('user_id', 'movie_id', 'rating') VALUES
(13, 8, 4.3),
(13, 9, 4.0),
(13, 10, 0.0);
