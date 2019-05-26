import logging
import os

import coloredlogs
from dotenv import load_dotenv
from sqlalchemy import create_engine
from sqlalchemy.sql import text
from sqlalchemy.engine.url import URL

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


def create_database():
    """
    Function that creates a database
    :return:
    """
    logging.info('Creating database')
    try:
        with engine.connect() as conn:
            query = text('CREATE DATABASE if not exists proyecto_SI;')
            conn.execute(query)
            logging.debug('Database created successfully')
    except Exception as e:
        logging.critical(f'Error: {e}')


def create_csv_tables():
    """
    Function that creates movies, links, tags and ratings tables into the Proyecto_SI database
    :return:
    """
    logging.info('Creating tables')
    try:
        with engine.connect() as conn:
            logging.info('Creating movies table')
            tables_query = text(
                "create table if not exists proyecto_SI.movies  "
                "(movieID int(11) auto_increment primary key,"
                "title varchar(255),"
                "genres varchar(255))"
                "comment 'Table that has all the movies with their ID, title and genres';"
            )
            conn.execute(tables_query)
            logging.debug('Table movies created successfully')

            logging.info('Creating links table')
            links_query = text(
                "CREATE TABLE IF NOT EXISTS proyecto_SI.links "
                "(movieID int(11) not null,"
                "imdbID varchar(11),"
                "tmdbID varchar(11),"
                "constraint links_movies_movieID_fk foreign key (movieID) references proyecto_SI.movies (movieID))"
                "comment 'Table that has all the movies IDs from IMDB and TMDB. This table references movies.';"

            )
            conn.execute(links_query)
            logging.debug('Table links created successfully')

            logging.info('Creating ratings table')
            tags_query = text(
                "CREATE TABLE IF NOT EXISTS proyecto_SI.tags"
                "(userID int(11),"
                "movieID int(11) not null,"
                "tag varchar(200),"
                "constraint tags_movies_movieID_fk foreign key (movieID) references proyecto_SI.movies (movieID))"
                "DEFAULT CHARACTER SET utf8mb4 DEFAULT COLLATE utf8mb4_general_ci "
                "comment 'Table that has all the movies with their ID, title and genres';"
            )
            conn.execute(tags_query)
            logging.debug('Table tags created successfully')

            logging.info('Creating ratings table')
            ratings_query = text(
                "CREATE TABLE IF NOT EXISTS proyecto_SI.ratings"
                "(userID int(11) not null,"
                "movieID int(11) not null,"
                "rating float not null,"
                "constraint rating_movies_movieID_fk foreign key (movieID) references proyecto_SI.movies (movieID))"
            )
            conn.execute(ratings_query)
            logging.debug('Table ratings created successfully')

    except Exception as e:
        logging.critical(e)


def create_aditional_tables():
    with engine.connect() as conn:
        logging.info('Creating user_global_mean table')
        user_global_mean_query = text("""create table if not exists  proyecto_SI.user_global_mean(
            user_id      int    not null,
            mean         double null,
            rating_count int    null,
            constraint user_global_mean_user_id_uindex
                unique (user_id)
            );
            alter table user_global_mean
                add primary key (user_id);
            """)
        conn.execute(user_global_mean_query)

        logging.debug('Table user_global_mean created successfully')
        logging.info('Creating movies_mean_count table')
        movies_mean_count_query = text("""
            CREATE TABLE if not exists proyecto_SI.movies_mean_count (
            id INT NOT NULL AUTO_INCREMENT,
            movie_id INT NULL,
            mean DOUBLE NULL,
            rating_count INT NULL,
            CONSTRAINT movies_mean_count_PK PRIMARY KEY (id),
            CONSTRAINT movies_mean_count_movies_FK FOREIGN KEY (movie_id) REFERENCES proyecto_SI.movies(movieID))   
            """)
        conn.execute(movies_mean_count_query)
        logging.debug('Table user_global_mean created successfully')
        logging.info('Creating user_mean table')
        user_mean_query = text("""
            CREATE TABLE if not exists proyecto_SI.user_mean (
                id INT NOT NULL AUTO_INCREMENT,
                id_user1 INT NOT NULL,
                id_user2 int NOT NULL,
                mean DOUBLE NULL,
                CONSTRAINT user_mean_PK PRIMARY KEY (id)
            )""")
        logging.debug('Table user_mean created successfully')
        logging.info('Creating similitude table')
        conn.execute(user_mean_query)
        similitude_query = text("""
            create table if not exists proyecto_SI.similitude
            (
                id int auto_increment,
                user_id_1 int null,
                user_id_2 int null,
                pearson_corr double null,
                constraint table_name_pk
                    primary key (id)
            ); """)
        conn.execute(similitude_query)
        logging.debug('Table similitude created successfully')
        logging.info('Creating sim_cos table')
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
        logging.debug('Table sim_cos created successfully')


if __name__ == '__main__':
    create_database()
    create_csv_tables()
    create_aditional_tables()
