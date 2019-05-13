import csv
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


def check_if_files_exist(csv_files):
    """
    Function that receives a list of paths and checks if they all exist. If they all exist the function will return True
    otherwise, if one of them fails it will return False and will tell which file failed.
    :param csv_files:
    :return:
    """
    files = True
    for file in csv_files:
        if os.path.isfile(file):
            logging.debug(f'File found: {file}')
        else:
            logging.error(f'File {file} not found')
            files = False

    return files


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


def create_tables():
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


def export_links_file(file_path):
    """
    Function that reads the content of the links csv file and exports its content to the database
    :param file_path:
    :return:
    """
    logging.info('Exporting links csv file')
    with open(file_path, 'r', encoding='utf-8') as file:
        reader = csv.DictReader(file)
        with engine.connect() as conn:
            query = text("INSERT INTO proyecto_SI.links (movieID, imdbID, tmdbID)"
                         "VALUES (:_movie_id,:_imdbID,:_tmdbID)")
            rows = 1
            for row in reader:
                movie_id = row['movieId']
                imdbId = row['imdbId']
                tmdbID = row['tmdbId']
                conn.execute(query, _movie_id=movie_id, _imdbID=imdbId, _tmdbID=tmdbID)
                logging.info(f'Inserted {rows} links')
                rows += 1
    logging.debug('Links csv file exported successfully')


def export_movie_file(file_path):
    """
    Function that reads the movies csv file and exports its content to the database
    :param file_path:
    :return:
    """
    logging.info('Exporting movies csv file')
    with open(file_path, 'r', encoding='utf-8') as file:
        reader = csv.DictReader(file)
        with engine.connect() as conn:
            rows = 1
            query = text("INSERT INTO proyecto_SI.movies (movieID, title, genres) "
                         "VALUES (:_movie_id, :_titles, :_genres)")
            for row in reader:
                movie_id = int(row['movieId'])
                title = row['title']
                genres = row['genres']
                conn.execute(query, _movie_id=movie_id, _titles=title, _genres=genres)
                logging.info(f'Inserted {rows} movies')
                rows += 1
    logging.debug('Movies csv file exported successfully')


def export_ratings_file(file_path):
    """
    Function that reads the rating csv file and exports its content to the database
    :param file_path:
    :return:
    """
    logging.info('Exporting ratings csv file')
    with open(file_path, 'r', encoding='utf-8') as file:
        reader = csv.DictReader(file)
        with engine.connect() as conn:
            query = text("INSERT INTO proyecto_SI.ratings (movieID, rating, userID) "
                         "VALUES (:_movie_id, :_rating,:_userID)")
            rows = 1
            for row in reader:
                movie_id = int(row['movieId'])
                rating = float(row['rating'])
                userID = int(row['userId'])
                conn.execute(query, _movie_id=movie_id, _rating=rating, _userID=userID)
                logging.info(f'Inserted {rows} ratings')
                rows += 1
    logging.debug('Rating csv file exported successfully')


def export_tags_file(file_path):
    """
    Function that reads the tags csv file and exports its content to the database
    :param file_path:
    :return:
    """
    logging.info('Exporting tags csv file')
    with open(file_path, 'r', encoding='utf-8') as file:
        reader = csv.DictReader(file)
        with engine.connect() as conn:
            query = text("INSERT INTO proyecto_SI.tags (movieID, userID, tag)"
                         "VALUES (:_movie_id, :_userID,:_tag)")
            rows = 1
            for row in reader:
                movie_id = row['movieId']
                userID = row['userId']
                tag = row['tag']
                conn.execute(query, _movie_id=movie_id, _userID=userID, _tag=tag)
                logging.info(f'Inserted {rows} tags')
                rows += 1
    logging.debug('Movies csv file exported successfully')


if __name__ == "__main__":
    csv_files = [os.getenv('CSV_LINKS'), os.getenv('CSV_MOVIES'), os.getenv('CSV_RATINGS'), os.getenv('CSV_TAGS')]
    if check_if_files_exist(csv_files):
        create_database()
        create_tables()
        logging.info('Exporting files to Database')
        export_movie_file(csv_files[1])
        export_links_file(csv_files[0])
        export_ratings_file(csv_files[2])
        export_tags_file(csv_files[3])
    else:
        logging.critical('Files not found. Finishing script')
