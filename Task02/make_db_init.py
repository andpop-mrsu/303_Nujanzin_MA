import os

sql_script = open('db_init.sql', 'w+')

MOVIES = 'movies'
RATINGS = 'ratings'
TAGS = 'tags'
USERS = 'users'

sql_script.write('DROP TABLE IF EXISTS {0};\n'.format(MOVIES))
sql_script.write('DROP TABLE IF EXISTS {0};\n'.format(RATINGS))
sql_script.write('DROP TABLE IF EXISTS {0};\n'.format(TAGS))
sql_script.write('DROP TABLE IF EXISTS {0};\n'.format(USERS))

#movies.csv
dataset_movies_file = open('movies.csv', 'r')
movies_fields = dataset_movies_file.readline().split(',')

sql_script.write(('CREATE TABLE \'{0}\' (\n' +
                  '\'{1}\' INTEGER PRIMARY KEY,\n' +
                  '\'{2}\' TEXT NOT NULL,\n' +
                  '\'{3}\' INTEGER,\n' +
                  '\'{4}\' TEXT NOT NULL);\n')
                 .format(MOVIES, 'id', 'title', 'year', 'genres'))

insert_into_script = ('INSERT INTO \'{0}\' VALUES\n').format(MOVIES)
for line in dataset_movies_file:
    values = line.split(',')
    mid_value = ''
    for i in range(1, len(values) - 1):
        mid_value += (values[i] + ',')
    if mid_value[0] == '"':
        mid_value = mid_value[1:len(mid_value) - 2]
    else:
        mid_value = mid_value[:-1]

    year = mid_value[len(mid_value) - 5: len(mid_value) - 1]
    if not year.isdigit():
        year = "null"
    else:
        mid_value = mid_value[:-7]
        year = int(year)
    insert_into_script += ('({0},"{1}", {2}, "{3}"),\n').format(values[0], mid_value, year, values[len(values)-1][:-1])

insert_into_script = insert_into_script[:-2] + ';'
sql_script.write(insert_into_script)
dataset_movies_file.close()

sql_script.write('\n\n')
#ratings.csv

dataset_ratings_file = open('ratings.csv', 'r')
ratings_fields = dataset_ratings_file.readline().split(',')

sql_script.write('CREATE TABLE \'ratings\' (\n' +
                  '\'id\' INTEGER PRIMARY KEY,\n' +
                  '\'user_id\' INTEGER NOT NULL,\n' +
                  '\'movie_id\' INTEGER NOT NULL,\n' +
                  '\'rating\' REAL NOT NULL,\n' +
                  '\'timestamp\' INTEGER NOT NULL);\n')

insert_into_script = ('INSERT INTO \'ratings\' (\'user_id\', \'movie_id\', \'rating\', \'timestamp\') VALUES\n')
for line in dataset_ratings_file:
    values = line.split(',')
    insert_into_script += ('({0},{1},{2},{3}),\n').format(values[0], values[1], values[2], values[3][:-1])

insert_into_script = insert_into_script[:-2] + ';'

sql_script.write(insert_into_script)
dataset_ratings_file.close()

sql_script.write('\n\n')

#tags.csv
dataset_tags_file = open('tags.csv', 'r')
tags_fields = dataset_tags_file.readline().split(',')

sql_script.write('CREATE TABLE \'tags\' (\n' +
                  '\'id\' INTEGER PRIMARY KEY,\n' +
                  '\'user_id\' INTEGER NOT NULL,\n' +
                  '\'movie_id\' INTEGER NOT NULL,\n' +
                  '\'tag\' TEXT NOT NULL,\n' +
                  '\'timestamp\' INTEGER NOT NULL);\n')

insert_into_script = ('INSERT INTO \'tags\' (\'user_id\', \'movie_id\', \'tag\', \'timestamp\') VALUES\n').format(TAGS)
for line in dataset_tags_file:
    values = line.split(',')
    insert_into_script += ('({0},{1},"{2}",{3}),\n').format(values[0], values[1], values[2], values[3][:-1])

insert_into_script = insert_into_script[:-2] + ';'

sql_script.write(insert_into_script)
dataset_tags_file.close()
sql_script.write('\n\n')

#users.txt
dataset_users_file = open('users.txt', 'r')
users_fields = dataset_users_file.readline().split(',')

sql_script.write('CREATE TABLE \'users\' (\n' +
                  '\'id\' INTEGER PRIMARY KEY,\n' +
                  '\'name\' TEXT NOT NULL,\n' +
                  '\'email\' TEXT NOT NULL,\n' +
                  '\'gender\' TEXT NOT NULL,\n' +
                  '\'register_date\' TEXT NOT NULL,\n' +
                  '\'occupation\' TEXT NOT NULL);\n')

insert_into_script = ('INSERT INTO \'users\' (\'id\', \'name\', \'email\', \'gender\', \'register_date\', \'occupation\') VALUES\n')
for line in dataset_users_file:
    values = line.split('|')
    insert_into_script += ('({0},"{1}","{2}","{3}","{4}","{5}"),\n')\
        .format(values[0], values[1], values[2], values[3], values[4], values[5][:-1])

insert_into_script = insert_into_script[:-2] + ';'

sql_script.write(insert_into_script)
dataset_users_file.close()
sql_script.write('\n\n')

os.system('sqlite3 movies_rating.db ".read db_init.sql"')
