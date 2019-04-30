from sqlalchemy import create_engine

ip = '51.15.59.15/?charset=utf8mb4'
username = 'proyecto_si'
password = 'bicho'
engine = create_engine(f"mysql+mysqldb://{username}:{password}@{ip}", encoding='utf-8')