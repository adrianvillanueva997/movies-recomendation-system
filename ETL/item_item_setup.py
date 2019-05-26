import logging
import os
from concurrent.futures import ThreadPoolExecutor
import time
import coloredlogs
from dotenv import load_dotenv
from math import sqrt
from sqlalchemy import create_engine
from sqlalchemy.engine.url import URL
from sqlalchemy.sql import text

load_dotenv(verbose=True)
logger = logging.getLogger(__name__)
coloredlogs.install(level='DEBUG')
coloredlogs.install(level='DEBUG', logger=logger)
db_url = {
    'drivername': 'mysql',
    'username': os.getenv('USERNAME'),
    'password': os.getenv('PASSWORD'),
    'host': os.getenv('IP'),
    'query': {'charset': 'utf8'},  # the key-point setting
}
engine = create_engine(URL(**db_url), encoding='utf-8')


def sleep():
    time.sleep(0.1)


def create_cos_corr_table():
    with engine.connect() as conn:
        query = text("""
        create table if not exists proyecto_SI.sim_cos
        (
        id int auto_increment primary key,
        user_id_1       int    null,
        movie_id_1      int    null,
        movie_id_2      int    null,
        cos_correlation double null
        );
        """)
        conn.execute(query)


def create_movies_count_mean_table():
    with engine.connect() as conn:
        query = text("""
        CREATE TABLE if not exists proyecto_SI.movies_mean_count (
        id INT NOT NULL AUTO_INCREMENT,
        movie_id INT NULL,
        mean DOUBLE NULL,
        rating_count INT NULL,
        CONSTRAINT movies_mean_count_PK PRIMARY KEY (id),
        CONSTRAINT movies_mean_count_movies_FK FOREIGN KEY (movie_id) REFERENCES proyecto_SI.movies(movieID)
    )   
        """)
        conn.execute(query)


def get_all_user_count():
    """
    Function that selects all the users in the database and returns the unique users from the table
    :return:
    """
    with engine.connect() as conn:
        query = text(
            'select count(distinct proyecto_SI.ratings.userID) from proyecto_SI.ratings;')
        result = conn.execute(query)
        count = result.first()[0]
        return count


def get_movies_count():
    with engine.connect() as conn:
        query = text('select count(movieID) from proyecto_SI.movies;')
        result = conn.execute(query)
        count = result.first()[0]
        return count


def calculate_movies_mean_count(i):
    sleep()
    with engine.connect() as conn:
        query = text('insert into proyecto_SI.movies_mean_count (movie_id, mean, rating_count) '
                     'values (:_movie_id,'
                     '(select avg(rating) from proyecto_SI.ratings where movieID like :_movie_id),'
                     '(select count(rating) from proyecto_SI.ratings where movieID like :_movie_id));')
        conn.execute(query, _movie_id=i)
        logging.info(f'Inserted mean and rating count for movie: {i}')


def get_most_rated_movies():
    with engine.connect() as conn:
        query = text(
            'select * from proyecto_SI.movies_mean_count where rating_count >=50')
        results = conn.execute(query)
        movie_ids = []
        for result in results:
            movie_ids.append(result['movie_id'])
        return movie_ids


def calculate_cos_similitude(x_1, x_2):
    numerator, denominator_1, denominator_2 = 0, 0, 0
    for h in range(len(x_1)):
        numerator += (x_1[h] * x_2[h])
        denominator_1 += x_1[h] * x_1[h]
        denominator_2 += x_2[h] * x_2[h]
    similitude = numerator / (sqrt(denominator_1) * sqrt(denominator_2))
    return similitude


def insert_cos_similitude(i, similitude_movies):
    sleep()
    with engine.connect() as conn:
        user_i_mean_query = text(
            'select mean from proyecto_SI.user_global_mean where user_id like :_user_id')
        movie_1_ratings_query = text('select rating from proyecto_SI.ratings '
                                     'where userID like :_user_id')
        movie_2_ratings_query = text(
            'select rating from proyecto_SI.ratings where movieID like :_movie_id')
        insert_similitude_query = text('insert into proyecto_SI.sim_cos '
                                       '(user_id_1, movie_id_1, movie_id_2, cos_correlation) '
                                       'values (:_user_id,:_movie_1_id,:_movie_2_id,:_similitude)')
        mean_result = conn.execute(user_i_mean_query, _user_id=i)
        mean = mean_result.first()[0]
        for j in range(len(similitude_movies)):
            movie_1_ratings_results = conn.execute(
                movie_1_ratings_query, _movie_id=similitude_movies[j], _user_id=i)
            movie_1_ratings = []
            for result in movie_1_ratings_results:
                movie_1_ratings.append(mean - result['rating'])
            for k in range(j, len(similitude_movies)):
                if j != k:
                    movie_2_ratings_results = conn.execute(
                        movie_2_ratings_query, _movie_id=similitude_movies[k])
                    movie_2_ratings = []
                    for result in movie_2_ratings_results:
                        movie_2_ratings.append(mean - result['rating'])
                    if len(movie_1_ratings) != 0 and len(movie_2_ratings) != 0:
                        if len(movie_1_ratings) < len(movie_2_ratings):
                            x_1 = movie_1_ratings
                            x_2 = movie_2_ratings
                        else:
                            x_1 = movie_2_ratings
                            x_2 = movie_1_ratings
                        similitude = calculate_cos_similitude(x_1, x_2)
                        conn.execute(insert_similitude_query, _user_id=i, _movie_1_id=similitude_movies[j],
                                     _movie_2_id=similitude_movies[k],
                                     _similitude=similitude)
                        logging.info(f'similitude for user {i} '
                                     f'for movie {similitude_movies[j]} on {similitude_movies[k]}')


if __name__ == '__main__':
    logging.info('Creating cos similitude table')
    create_cos_corr_table()
    logger.debug('Cos similitude table created successfully')
    logging.debug('Similitude table created successfully')
    logging.info('Creating movies mean and count table')
    create_movies_count_mean_table()
    logging.debug('table created successfully')
    user_count = get_all_user_count()
    movies_count = get_movies_count()
    similitude_movies = get_most_rated_movies()
    similitude_movies.sort()
    print(len(similitude_movies))
    print(similitude_movies)
    i = 0
    with ThreadPoolExecutor(max_workers=128) as executor:
        while i < user_count:
            i += 1
            a = executor.submit(insert_cos_similitude, i, similitude_movies)
