import logging
import os
import threading
import time
from concurrent.futures import ThreadPoolExecutor
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
    time.sleep(0.001)


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


def create_user_data_table():
    """
    Function that creates the global user mean table
    :return:
    """
    with engine.connect() as conn:
        query = text("""
    create table if not exists  proyecto_SI.user_global_mean
    (
    user_id      int    not null,
    mean         double null,
    rating_count int    null,
    constraint user_global_mean_user_id_uindex
        unique (user_id)
    );
    alter table user_global_mean
        add primary key (user_id);
                """)
        conn.execute(query)


def calculate_global_user_mean(i, user_count):
    """
    Function that calculates the global user mean from each user
    :param user_count:
    :return:
    """
    sleep()
    with engine.connect() as conn:
        query = text('insert into proyecto_SI.user_global_mean (user_id, mean,rating_count ) '
                     'values (:_user_id, (select avg(proyecto_SI.ratings.rating) '
                     'from proyecto_SI.ratings where userID like :_user_id),'
                     '(select distinct count(userID) from proyecto_SI.ratings where userID like :_user_id))')
        conn.execute(query, _user_id=i)
        logging.info(f'Calculated: {i} of {user_count}')


def create_user_user_mean():
    """
    Function that creates the user user mean table
    :return:
    """
    with engine.connect() as conn:
        query = text("""
        CREATE TABLE if not exists proyecto_SI.user_mean (
            id INT NOT NULL AUTO_INCREMENT,
            id_user1 INT NOT NULL,
            id_user2 int NOT NULL,
            mean DOUBLE NULL,
            CONSTRAINT user_mean_PK PRIMARY KEY (id)
        )""")
        conn.execute(query)


def create_similitude_table():
    with engine.connect() as conn:
        query = text("""
        create table if not exists proyecto_SI.similitude
        (
            id int auto_increment,
            user_id_1 int null,
            user_id_2 int null,
            pearson_corr double null,
            constraint table_name_pk
                primary key (id)
        ); """)
        conn.execute(query)


def calculate_user_to_user_mean(i, user_count):
    """
    Function that receives an user count as param and inserts each user user mean
    :param user_count:
    :return:
    """
    sleep()
    with engine.connect() as conn:
        query = text("""
        insert into proyecto_SI.user_mean (id_user1, id_user2, mean)
        values (:_user_id1, :_user_id2, (select avg(proyecto_SI.ratings.rating) from proyecto_SI.ratings
                                            where userID like :_user_id1 and proyecto_SI.ratings.movieID in
                                            (select movieID from proyecto_SI.ratings 
                                            where userID like :_user_id2)));
        """)
        for j in range(1, user_count):
            if i != j:
                logging.info(f'Calculating user: {i} on user {j}')
                conn.execute(query, _user_id1=i, _user_id2=j)


def pearson(mean1, mean2, ratings_list1, ratings_list2):
    a = 0
    bx = 0
    by = 0
    for i in range(len(ratings_list1)):
        x_val = ratings_list1[i] - mean1
        y_val = ratings_list2[i] - mean2
        a += x_val * y_val
        bx += x_val ** 2
        by += y_val ** 2
    b = sqrt(bx * by)
    if b != 0:
        result = a / b
        return result
    else:
        return None


def get_similar_movies_ratings(i, user_count):
    sleep()
    with engine.connect() as conn:
        common_ratings_query = text('select * from proyecto_SI.ratings where userID like :_user_id1'
                                    ' and proyecto_SI.ratings.movieID in '
                                    '(select movieID from proyecto_SI.ratings '
                                    'where userID like :_user_id2)')

        user_user_mean_query = text('select * from proyecto_SI.user_mean where id_user1 like :_user_id1 '
                                    'and proyecto_SI.user_mean.id_user2 like :_user_id2')

        insert_similitude = text('insert into proyecto_SI.similitude (user_id_1, user_id_2, pearson_corr) '
                                 'values (:_user_id_1, :_user_id_2,:_pearson);')
        for j in range(1, user_count):
            if i != j:
                result_mean1 = conn.execute(
                    user_user_mean_query, _user_id1=i, _user_id2=j)
                result_mean2 = conn.execute(
                    user_user_mean_query, _user_id1=j, _user_id2=i)
                mean1 = result_mean1.first()[3]
                mean2 = result_mean2.first()[3]
                if mean1 or mean2 is None:
                    ratings_list1 = []
                    ratings_list2 = []
                    result1 = conn.execute(
                        common_ratings_query, _user_id1=i, _user_id2=j)
                    result2 = conn.execute(
                        common_ratings_query, _user_id1=j, _user_id2=i)
                    for result in result1:
                        ratings_list1.append(result['rating'])
                    for result in result2:
                        ratings_list2.append(result['rating'])
                    similitude = pearson(
                        mean1, mean2, ratings_list1, ratings_list2)
                    conn.execute(insert_similitude, _user_id_1=i,
                                 _user_id_2=j, _pearson=similitude)
                    logging.info(
                        f'Calculated similitude between users {i} and {j}')


if __name__ == '__main__':
    logging.info('Creating user global means table')
    create_user_data_table()
    logging.debug('Table created successfully')
    logging.info('Getting all user count')
    user_count = get_all_user_count()
    logging.debug(f'Found: {user_count} users')
    logging.info(f'Calculating global means for {user_count} users')
    with ThreadPoolExecutor(max_workers=128) as executor:
        for i in range(1, user_count):
            a = executor.submit(calculate_global_user_mean, i, user_count)
            logging.debug("Task Executed {}".format(
                threading.current_thread()))
    logging.debug(f'Creating user_user mean table')
    create_user_user_mean()
    logging.info(f'Table user_user created successfully')
    logging.info('Calculating user to user mean')
    with ThreadPoolExecutor(max_workers=128) as executor:
        for i in range(1, user_count):
            a = executor.submit(calculate_user_to_user_mean, i, user_count)
    logging.info('Creating similitude Table')
    create_similitude_table()
    logging.debug('Similitude table created successfully')
    with ThreadPoolExecutor(max_workers=128) as executor:
        for i in range(1, user_count):
            a = executor.submit(get_similar_movies_ratings, i, user_count)
    logger.info('Creating Cos correlation table')
