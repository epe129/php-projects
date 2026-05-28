"""
admin that is made with flask.
"""
from flask import Flask, render_template, request
import pymysql

app = Flask(__name__)

connection = pymysql.connect(host="localhost", port=3306, user="root", password="")
cursor = connection.cursor()

@app.route('/', methods=["GET", "POST"])
def index():
    """
    Log in page.
    """

    if request.method == "POST":
        username = request.form.get("username")
        password = request.form.get("password")

        cursor.execute("SELECT * FROM users WHERE username=%s AND password=%s", (username, password))
        user = cursor.fetchone()

        if user:
            return render_template("dashboard.html")
        else:
            return render_template("index.html", error="Invalid credentials")
        

    return render_template("index.html")

if __name__ == '__main__':
    app.run()
