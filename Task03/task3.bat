#!/bin/bash
chcp 65001

sqlite3 movies_rating.db < db_init.sql

echo "1. Составить список фильмов, имеющих хотя бы одну оценку. Список фильмов отсортировать по году выпуска и по названиям. В списке оставить первые 10 фильмов."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "select movies.id, movies.title, movies.year from movies inner join ratings on movies.id = ratings.movie_id where movies.year is not null group by movies.id order by movies.year, movies.title limit 10"
echo " "

echo "2. Вывести список всех пользователей, фамилии (не имена!) которых начинаются на букву 'A'. Полученный список отсортировать по дате регистрации. В списке оставить первых 5 пользователей."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "select id, name, register_date from users where substr(name, instr(name, ' ') + 1, 1)=='A' order by register_date limit 5"
echo " "

echo "3. Написать запрос, возвращающий информацию о рейтингах в более читаемом формате: имя и фамилия эксперта, название фильма, год выпуска, оценка и дата оценки в формате ГГГГ-ММ-ДД. Отсортировать данные по имени эксперта, затем названию фильма и оценке. В списке оставить первые 50 записей."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT users.name, movies.title, movies.year, ratings.rating, date(ratings.timestamp, 'unixepoch') as rating_date FROM movies JOIN ratings ON movies.id = ratings.movie_id JOIN users ON users.id = ratings.user_id ORDER BY users.name, movies.title, ratings.rating LIMIT 50"
echo " "

echo "4. Вывести список фильмов с указанием тегов, которые были им присвоены пользователями. Сортировать по году выпуска, затем по названию фильма, затем по тегу. В списке оставить первые 40 записей."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT movies.title, movies.year, tags.tag FROM movies JOIN tags ON movies.id = tags.movie_id WHERE movies.year IS NOT NULL ORDER BY movies.year, movies.title, tags.tag LIMIT 40"
echo " "

echo "5. Вывести список самых свежих фильмов. В список должны войти все фильмы последнего года выпуска, имеющиеся в базе данных. Запрос должен быть универсальным, не зависящим от исходных данных (нужный год выпуска должен определяться в запросе, а не жестко задаваться)."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT movies.title, movies.year FROM movies WHERE movies.year = (SELECT max(movies.year) FROM movies LIMIT 1)"
echo " "

echo "6. Найти все комедии, выпущенные после 2000 года, которые понравились мужчинам (оценка не ниже 4.5). Для каждого фильма в этом списке вывести название, год выпуска и количество таких оценок. Результат отсортировать по году выпуска и названию фильма."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT movies.title, movies.year, count(*) as 'Count of Ratings' FROM movies JOIN ratings ON movies.id = ratings.movie_id JOIN users ON ratings.user_id = users.id WHERE movies.year > 2000 AND ratings.rating >= 4.5 AND users.gender = 'male' AND movies.genres LIKE '%%comedy%%' GROUP BY movies.id, movies.year ORDER BY movies.year, movies.title"
echo " "

echo "7. Провести анализ занятий (профессий) пользователей - вывести количество пользователей для каждого рода занятий. Найти самую распространенную и самую редкую профессию посетитетей сайта."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT users.occupation, count(*) as 'Count of Users' FROM users GROUP BY users.occupation"
sqlite3 movies_rating.db -box -echo "SELECT occupations.occupation as 'the most popular occupation', max(occupations.'Count of Users') as 'Count' FROM (SELECT users.occupation, count(*) as 'Count of Users' FROM users GROUP BY users.occupation) occupations"
sqlite3 movies_rating.db -box -echo "SELECT occupations.occupation as 'the rarest occupation', min(occupations.'Count of Users') as 'Count' FROM (SELECT users.occupation, count(*) as 'Count of Users' FROM users GROUP BY users.occupation) occupations"
echo " "


start /wait 