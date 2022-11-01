#!/bin/bash
chcp 65001

sqlite3 movies_rating.db < db_init.sql

echo "1. Найти все пары пользователей, оценивших один и тот же фильм. Устранить дубликаты, проверить отсутствие пар с самим собой. Для каждой пары должны быть указаны имена пользователей и название фильма, который они ценили"
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT users1.name, users2.name, movies.title FROM ratings ratings1, ratings ratings2, users users1, users users2, movies WHERE ratings1.movie_id=ratings2.movie_id AND ratings1.user_id < ratings2.user_id AND ratings1.movie_id=movies.id AND users1.id=ratings1.user_id AND users2.id=ratings2.user_id GROUP BY ratings1.user_id, ratings2.user_id"
echo " "

echo "2. Найти 10 самых свежих оценок от разных пользователей, вывести названия фильмов, имена пользователей, оценку, дату отзыва в формате ГГГГ-ММ-ДД."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT movies.title, users.name, ratings.rating, newtable.tmp as 'timestamp' FROM movies, ratings, users, (SELECT DISTINCT FIRST_VALUE(ratings.movie_id) OVER(PARTITION BY ratings.user_id ORDER BY ratings."timestamp" DESC) AS movie_id, ratings.user_id, FIRST_VALUE(date(ratings.'timestamp', 'unixepoch')) OVER(PARTITION BY ratings.user_id ORDER BY ratings."timestamp" DESC) AS tmp FROM ratings ORDER BY ratings.'timestamp' DESC LIMIT 10) AS newtable WHERE ratings.movie_id=newtable.movie_id AND ratings.user_id=newtable.user_id AND movies.id=ratings.movie_id AND users.id=ratings.user_id"
echo " "

echo "3. Вывести в одном списке все фильмы с максимальным средним рейтингом и все фильмы с минимальным средним рейтингом. Общий список отсортировать по году выпуска и названию фильма. В зависимости от рейтинга в колонке 'Рекомендуем' для фильмов должно быть написано 'Да' или 'Нет'."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT movies.title, movies.year, AVG(ratings.rating) rating_avg1, 'yes' recommend FROM movies, ratings WHERE movies.id=ratings.movie_id AND movies.year IS NOT NULL GROUP BY movies.title HAVING rating_avg1=(SELECT MAX(rating_avg) FROM (SELECT movies.title, AVG(ratings.rating) rating_avg FROM movies, ratings WHERE (movies.id=ratings.movie_id) GROUP BY movies.title)) UNION ALL SELECT movies.title, movies.year, AVG(ratings.rating) rating_avg1, 'no' recommend FROM movies, ratings WHERE movies.id=ratings.movie_id AND movies.year IS NOT NULL GROUP BY movies.title HAVING rating_avg1=(SELECT MIN(rating_avg) FROM (SELECT movies.title, AVG(ratings.rating) rating_avg FROM movies, ratings WHERE (movies.id=ratings.movie_id) GROUP BY movies.title)) ORDER BY movies.year, movies.title"
echo " "

echo "4. Вычислить количество оценок и среднюю оценку, которую дали фильмам пользователи-женщины в период с 2010 по 2012 год."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT COUNT(*) 'count of ratings', AVG(ratings.rating) 'average rating' FROM ratings, users WHERE ratings.user_id=users.id AND users.gender='female' AND DATE(ratings.timestamp, 'unixepoch') >= '2010-00-00' AND DATE(ratings.timestamp, 'unixepoch') <= '2011-12-31'"
echo " "

echo "5. Составить список фильмов с указанием их средней оценки и места в рейтинге по средней оценке. Полученный список отсортировать по году выпуска и названиям фильмов. В списке оставить первые 20 записей."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT movies.title, movies.year, AVG(ratings.rating) rating_avg, ROW_NUMBER() OVER(ORDER BY AVG(ratings.rating)) 'number by average rating' FROM movies JOIN ratings ON movies.id=ratings.movie_id WHERE movies.year IS NOT NULL GROUP BY movies.id ORDER BY movies.year, movies.title LIMIT 20"
echo " "

echo "6. Вывести список из 10 последних зарегистрированных пользователей в формате 'Фамилия Имя|Дата регистрации' (сначала фамилия, потом имя)."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT substr(users.name, instr(users.name, ' ') + 1) || ' ' || substr(users.name, 0, instr(users.name, ' ')) || ' | ' || users.register_date AS 'users info' FROM users ORDER BY users.register_date DESC LIMIT 10"
echo " "

echo "7. С помощью рекурсивного CTE составить таблицу умножения для чисел от 1 до 10."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "WITH RECURSIVE prod(x, y) AS (SELECT 1, 1 UNION All SELECT x + (y)/10, (y + 0) %% 10 + 1 FROM prod WHERE x <= 10) SELECT prod.x || 'x' || prod.y || ' = ' || format(prod.x*prod.y) AS prod FROM prod WHERE prod.x <= 10"
echo " "

echo "8. С помощью рекурсивного CTE выделить все жанры фильмов, имеющиеся в таблице movies (каждый жанр в отдельной строке)."
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "WITH genres_array(r_lenght, r_part, res) AS (SELECT 1, movies.genres||'|', '' FROM movies WHERE 1 UNION ALL SELECT instr(r_part, '|' ) AS r_l, substr(r_part, instr(r_part, '|' ) + 1), substr(r_part, 1, instr(r_part, '|' ) - 1) FROM genres_array WHERE r_l > 0) SELECT DISTINCT res AS 'Genres' FROM genres_array WHERE length(res) > 0"
echo " "

start /wait 