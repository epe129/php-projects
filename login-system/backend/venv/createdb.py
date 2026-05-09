import pymysql, config
# luodaan yhteys
try:
    connection = pymysql.connect(host=config.data["host"], port=config.data["port"], user=config.data["user"], password=config.data["password"], database=config.data["database"])
    cursor = connection.cursor()
except Exception as e:
    print(f"Yhteyden luominen epäonnistui {e}")

def db():
    # luodaan taulut
    try:
        cursor.execute("CREATE TABLE IF NOT EXISTS users ( id INT AUTO_INCREMENT PRIMARY KEY NOT NULL UNIQUE, nimi VARCHAR(45) NOT NULL, email VARCHAR(45) NOT NULL UNIQUE, pword VARCHAR(255) NOT NULL);")
        cursor.connection.commit()          
    except Exception as e:
        print(f"Taulukon luominen epäonnistui {e}")
db()