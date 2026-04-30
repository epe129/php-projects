from flask import Flask, request, jsonify, render_template
import pymysql, dbinfo
from datetime import datetime, timedelta

app = Flask(__name__)

def get_connection():
    return pymysql.connect(
        host=dbinfo.data["HOST"],
        port=dbinfo.data["PORT"],
        user=dbinfo.data["USER"],
        password=dbinfo.data["PASSWORD"],
        database=dbinfo.data["DBNIMI"],
        cursorclass=pymysql.cursors.DictCursor
    )

@app.route("/", methods=["POST", "GET"])
def index():
    return render_template("index.html")

# ---------------------------------------------------
# GENERATE SCHEDULE (basic version)
# ---------------------------------------------------
@app.route("/generate-schedule", methods=["POST"])
def generate_schedule():
    data = request.json
    user_id = data.get("user_id")

    connection = get_connection()
    cursor = connection.cursor()

    # Clear old schedule
    cursor.execute("DELETE FROM generated_schedule WHERE user_id=%s", (user_id,))

    # Get user daily hours
    cursor.execute("SELECT daily_available_hours FROM users WHERE id=%s", (user_id,))
    user = cursor.fetchone()

    if not user:
        return jsonify({"status": "error", "message": "User not found"})

    daily_hours = user["daily_available_hours"]

    # Get pending tasks ordered by deadline
    cursor.execute("""
        SELECT * FROM tasks 
        WHERE user_id=%s AND status='pending'
        ORDER BY deadline ASC
    """, (user_id,))
    tasks = cursor.fetchall()

    today = datetime.today().date()

    for task in tasks:
        remaining = task["remaining_hours"]
        current_day = today

        while remaining > 0:
            allocate = min(daily_hours, remaining)

            cursor.execute("""
                INSERT INTO generated_schedule 
                (user_id, task_id, scheduled_date, allocated_hours)
                VALUES (%s, %s, %s, %s)
            """, (user_id, task["id"], current_day, allocate))

            remaining -= allocate
            current_day += timedelta(days=1)

    connection.commit()
    cursor.close()
    connection.close()

    return jsonify({"status": "success", "message": "Schedule generated"})


# ---------------------------------------------------
# GET SCHEDULE
# ---------------------------------------------------
@app.route("/schedule/<int:user_id>", methods=["GET"])
def get_schedule(user_id):
    connection = get_connection()
    cursor = connection.cursor()

    cursor.execute("""
        SELECT gs.scheduled_date, t.title, gs.allocated_hours
        FROM generated_schedule gs
        JOIN tasks t ON gs.task_id = t.id
        WHERE gs.user_id=%s
        ORDER BY gs.scheduled_date ASC
    """, (user_id,))

    schedule = cursor.fetchall()

    cursor.close()
    connection.close()

    return jsonify(schedule)


if __name__ == "__main__":
    app.run(debug=True)