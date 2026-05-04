import pymysql
# luodaan yhteys
try:
    connection = pymysql.connect(host="localhost", port=3306, user="root", password="", database="kalastus")
    cursor = connection.cursor()
except Exception as e:
    print(f"Yhteyden luominen epäonnistui {e}")

def db():
    # luodaan taulut
    try:
        cursor.execute("CREATE TABLE IF NOT EXISTS integraatiot (diaNopeus INT)")
        cursor.execute("CREATE TABLE IF NOT EXISTS KALASTAJA ( id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, nimi TEXT NOT NULL UNIQUE);")
        cursor.execute("CREATE TABLE IF NOT EXISTS VIEHE ( id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, viehe TEXT NOT NULL);")
        cursor.execute("CREATE TABLE IF NOT EXISTS VAPA ( id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, vapa TEXT NOT NULL);")
        cursor.execute("CREATE TABLE IF NOT EXISTS LAJI ( id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, laji TEXT NOT NULL UNIQUE);")
        cursor.execute("CREATE TABLE IF NOT EXISTS TARPPI ( id INT AUTO_INCREMENT NOT NULL, aika DATETIME NOT NULL, kalastaja_id INT NOT NULL, viehe_id INT NOT NULL, vapa_id INT NOT NULL, paikka TEXT, FOREIGN KEY (kalastaja_id) REFERENCES KALASTAJA(id), FOREIGN KEY (viehe_id) REFERENCES VIEHE(id), FOREIGN KEY (vapa_id) REFERENCES VAPA(id), PRIMARY KEY (id, kalastaja_id, viehe_id, vapa_id));")
        cursor.execute("CREATE TABLE IF NOT EXISTS KALA ( id INT AUTO_INCREMENT NOT NULL, tarppi_id INT NOT NULL, pituus FLOAT, paino FLOAT, laji_id INT NOT NULL, FOREIGN KEY (tarppi_id) REFERENCES TARPPI(id), FOREIGN KEY (laji_id) REFERENCES LAJI(id), PRIMARY KEY (id, tarppi_id));")
        lajit = ["ahven", "harjus", "hauki", "jokirapu", "kiiski", "kirjolohi", "kolmipiikki", "kuha", "kuore", "lahna", "lohi", "made", "muikku", "pasuri", "rautu", "ruutana" "salakka", "särki", "säyne", "siika", "silakka", "sorva", "suutari", "taimen", "täplärapu"]
        for laji in lajit:
            cursor.execute(f'INSERT INTO laji (laji) VALUES ("{laji}")')
        cursor.connection.commit()          
    except Exception as e:
        print(f"Taulukon luominen epäonnistui {e}")
db()


# cursor.execute("CREATE TABLE IF NOT EXISTS kalastaja (id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, nimi TEXT)")
        # cursor.execute("CREATE TABLE IF NOT EXISTS viehe (id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, viehe TEXT)")
        # cursor.execute("CREATE TABLE IF NOT EXISTS vapa (id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, vapa TEXT)")
        # cursor.execute("CREATE TABLE IF NOT EXISTS laji (id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, laji TEXT)")
        # cursor.execute("CREATE TABLE IF NOT EXISTS tarppi (id INT AUTO_INCREMENT NOT NULL, aika DATETIME, kalastaja_id INT NOT NULL, viehe_id INT NOT NULL, vapa_id INT NOT NULL, paikka TEXT, PRIMARY KEY (id, kalastaja_id, viehe_id, vapa_id))")
        # cursor.execute("CREATE TABLE IF NOT EXISTS kala (id INT AUTO_INCREMENT NOT NULL, tarppi_id INT NOT NULL, pituus FLOAT, paino FLOAT, laji_id INT NOT NULL, PRIMARY KEY (id, tarppi_id))")