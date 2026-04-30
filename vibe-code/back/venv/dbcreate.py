import pymysql
import dbinfo

# --- LUODAAN YHTEYS ---
try:
    connection = pymysql.connect(
        host=dbinfo.data["HOST"],
        port=dbinfo.data["PORT"],
        user=dbinfo.data["USER"],
        password=dbinfo.data["PASSWORD"],
        database=dbinfo.data["DBNIMI"],
    )
    cursor = connection.cursor()
    print("✅ Yhteys onnistui")
except Exception as e:
    print(f"❌ Yhteyden luominen epäonnistui: {e}")


def db():
    try:
        # USERS
        cursor.execute("""
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                daily_available_hours FLOAT DEFAULT 4,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        """)

        # SUBJECTS
        cursor.execute("""
            CREATE TABLE IF NOT EXISTS subjects (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                difficulty_level INT DEFAULT 3,
                priority_level INT DEFAULT 3,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        """)

        # TASKS
        cursor.execute("""
            CREATE TABLE IF NOT EXISTS tasks (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                subject_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                estimated_hours FLOAT NOT NULL,
                remaining_hours FLOAT NOT NULL,
                deadline DATE NOT NULL,
                status ENUM('pending', 'completed') DEFAULT 'pending',
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
            )
        """)

        # GENERATED SCHEDULE
        cursor.execute("""
            CREATE TABLE IF NOT EXISTS generated_schedule (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                task_id INT NOT NULL,
                scheduled_date DATE NOT NULL,
                allocated_hours FLOAT NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE
            )
        """)

        connection.commit()
        print("✅ Study Planner tietokanta luotu onnistuneesti!")

    except Exception as e:
        connection.rollback()
        print(f"❌ Taulujen luominen epäonnistui: {e}")


db()

cursor.close()
connection.close()