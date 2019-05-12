import os
import coloredlogs
import logging
from sqlalchemy.sql import text
from sqlalchemy import create_engine
from dotenv import load_dotenv

load_dotenv(verbose=True)
logger = logging.getLogger(__name__)
coloredlogs.install(level='DEBUG')
coloredlogs.install(level='DEBUG', logger=logger)
engine = create_engine(f"mysql+mysqldb://{os.getenv('USERNAME')}:{os.getenv('PASSWORD')}@{os.getenv('IP')}",
                       encoding='utf-8')


def get_all_user_count():
    with engine.connect() as conn:
        query = text('select count(distinct proyecto_SI.ratings.userID) from proyecto_SI.ratings;')
        result = conn.execute(query)
        for res in result:
            res = str(res).replace('(', '')
            res = res.replace(',)', '')
        return int(res)


def create_user_data_table():
    with engine.connect() as conn:
        query = text("""
        create table if not exists proyecto_SI.user_global_mean(
            user_id int    not null,
            mean    double null,
            constraint user_global_mean_user_id_uindex
                unique (user_id)
        );
        alter table proyecto_SI.user_global_mean
            add primary key (user_id);""")
        conn.execute(query)


def calculate_global_user_mean(user_count):
    with engine.connect() as conn:
        query = text('insert into proyecto_SI.user_global_mean (user_id, mean) '
                     'values (:_user_id, (select avg(proyecto_SI.ratings.rating) '
                     'from proyecto_SI.ratings where userID like :_user_id));')
        for i in range(1, user_count):
            conn.execute(query, _user_id=i)
            logging.debug(f'Calculated: {i} of {user_count}')


def create_user_user_mean():
    with engine.connect() as conn:
        query = text("""
        CREATE TABLE if not exists proyecto_SI.user_mean (
            id INT NOT NULL AUTO_INCREMENT,
            id_user1 INT NOT NULL,
            id_user2 int NOT NULL,
            mean DOUBLE NULL,
            CONSTRAINT user_mean_PK PRIMARY KEY (id)
        )
        ENGINE=InnoDB
        DEFAULT CHARSET=utf8mb4
        COLLATE=utf8mb4_bin;""")
        conn.execute(query)


def calculate_user_to_user_mean(user_count):
    with engine.connect() as conn:
        query = text("""
        insert into proyecto_SI.user_mean (id_user1, id_user2, mean)
        values (:_user_id1, :_user_id2, (select avg(proyecto_SI.ratings.rating) from proyecto_SI.ratings
                                            where userID like :_user_id1 and proyecto_SI.ratings.movieID in
                                            (select movieID from proyecto_SI.ratings 
                                            where userID like :_user_id2)));
        """)
        for i in range(1, user_count):
            logging.debug(f'Calculating user: {i}')
            for j in range(1, user_count):
                if i != j:
                    logging.info(f'Calculating user: {i} on user {j}')
                    conn.execute(query, _user_id1=i, _user_id2=j)


if __name__ == '__main__':
    logging.info('Creating user global means table')
    create_user_data_table()
    logging.debug('Table created successfully')
    logging.info('Getting all user count')
    user_count = get_all_user_count()
    logging.debug(f'Found: {user_count} users')
    logging.info(f'Calculating global means for {user_count} users')
    calculate_global_user_mean(user_count)
    logging.debug(f'Creating user_user mean table')
    create_user_user_mean()
    logging.info(f'Table user_user created successfully')
    logging.info('Calculating user to user mean')
    calculate_user_to_user_mean(user_count)
