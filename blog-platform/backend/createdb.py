"""
Luodaan database.
"""
import pymysql

def databasen_luonti():
    """
    Luodaan itse tietokanta jos ei ole olemassa.
    """
    connection = pymysql.connect(host="localhost", port=3306, user="root", password="")
    cursor = connection.cursor()
    cursor.execute("CREATE DATABASE IF NOT EXISTS blog_platform")

# luodaan yhteys
try:
    databasen_luonti()
    connection = pymysql.connect(host="localhost", port=3306, user="root", password="", database="blog_platform")
    cursor = connection.cursor()
except ImportError:
    print("Yhteyden luominen epäonnistui")

def db():
    """
    luodaan tietokannan taulut sekä lisätään laji, vapa ja viehe arvot.
    """
    try:
        cursor.execute("CREATE TABLE IF NOT EXISTS users ( id INT AUTO_INCREMENT PRIMARY KEY UNIQUE, username VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL UNIQUE, pword VARCHAR(255) NOT NULL)")
        cursor.execute("CREATE TABLE IF NOT EXISTS blogs ( id INT AUTO_INCREMENT PRIMARY KEY UNIQUE, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, is_public BOOLEAN, FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE)")
        cursor.execute("CREATE TABLE IF NOT EXISTS admins ( id INT AUTO_INCREMENT PRIMARY KEY UNIQUE, username VARCHAR(100) NOT NULL UNIQUE, pword VARCHAR(255) NOT NULL)")
    except ImportError:
        print("Taulukon luominen epäonnistui")
db()