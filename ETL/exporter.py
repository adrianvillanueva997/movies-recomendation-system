import csv
import os
import coloredlogs
import logging
from sqlalchemy.sql import text
from sqlalchemy import create_engine

# =====================
ip = '51.15.59.15/?charset=utf8mb4'
username = 'proyecto_si'
password = 'bicho'

csv_links = 'CSV/links.csv'
csv_movies = 'CSV/movies.csv'
csv_ratings = 'CSV/ratings.csv'
csv_tags = 'CSV/tags.csv'
# =====================

engine = create_engine(f"mysql+mysqldb://{username}:{password}@{ip}", encoding='utf-8')
logger = logging.getLogger(__name__)
coloredlogs.install(level='DEBUG')
coloredlogs.install(level='DEBUG', logger=logger)


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
            query = text('CREATE DATABASE if not exists proyecto_SI collate = utf8mb4_bin')
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
                "imdbID int(11),"
                "tmdbID int(11),"
                "constraint links_imbdID_uindex unique (imdbID),"
                "constraint links_tmdbID_uindex unique (tmdbID),"
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
                "timestamp timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,"
                "constraint tags_movies_movieID_fk foreign key (movieID) references proyecto_SI.movies (movieID))"
                "comment 'Table that has all the movies with their ID, title and genres';"
            )
            conn.execute(tags_query)
            logging.debug('Table tags created successfully')

            logging.info('Creating ratings table')
            ratings_query = text(
                "CREATE TABLE IF NOT EXISTS proyecto_SI.ratings"
                "(userID int(11) not null,"
                "movieID int(11) not null,"
                "rating double not null,"
                "timestamp timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,"
                "constraint rating_movies_movieID_fk foreign key (movieID) references proyecto_SI.movies (movieID))"
            )
            conn.execute(ratings_query)
            logging.debug('Table ratings created successfully')

    except Exception as e:
        logging.critical(e)


def export_links_file(file_path):
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
                logging.info(f'Inserted {rows} rows')
                rows += 1
    logging.debug('Links csv file exported successfully')


def export_movie_file(file_path):
    logging.info('Exporting movies csv file')
    with open(file_path, 'r', encoding='utf-8') as file:
        reader = csv.DictReader(file)
        with engine.connect() as conn:
            rows = 1
            query = text("INSERT INTO proyecto_SI.movies (movieID, title, genres) "
                         "VALUES (:_movie_id, :_titles, :_genres)")
            for row in reader:
                movie_id = row['movieId']
                title = row['title']
                genres = row['genres']
                conn.execute(query, _movie_id=movie_id, _titles=title, _genres=genres)
                logging.info(f'Inserted {rows} rows')
                rows += 1
    logging.debug('Movies csv file exported successfully')


def export_ratings_file(file_path):
    logging.info('Exporting ratings csv file')
    with open(file_path, 'r', encoding='utf-8') as file:
        reader = csv.DictReader(file)
        with engine.connect() as conn:
            query = text("INSERT INTO proyecto_SI.ratings (movieID, rating, userID, timestamp) "
                         "VALUES (:_movie_id, :_rating,:_userID,:_timestamp)")
            rows = 1
            for row in reader:
                movie_id = row['movieId']
                rating = row['rating']
                userID = row['userId']
                timestamp = row['timestamp']
                conn.execute(query, _movie_id=movie_id, _rating=rating, _userID=userID, _timestamp=timestamp)
                logging.info(f'Inserted {rows} rows')
                rows += 1
    logging.debug('Rating csv file exported successfully')


def export_tags_file(file_path):
    logging.info('Exporting tags csv file')
    with open(file_path, 'r', encoding='utf-8') as file:
        reader = csv.DictReader(file)
        with engine.connect() as conn:
            query = text("INSERT INTO proyecto_SI.tags (movieID, userID, tag, timestamp)"
                         "VALUES (:_movie_id, :_userID,:_tag,:_timestamp)")
            rows = 1
            for row in reader:
                movie_id = row['movieId']
                userID = row['userId']
                tag = row['tag']
                timestamp = row['timestamp']
                conn.execute(query, _movie_id=movie_id, _userID=userID, _tag=tag, _timestamp=timestamp)
                logging.info(f'Inserted {rows} rows')
                rows += 1
    logging.debug('Movies csv file exported successfully')


if __name__ == "__main__":
    csv_files = [csv_links, csv_movies, csv_ratings, csv_tags]
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
