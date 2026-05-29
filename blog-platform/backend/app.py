"""
admin that is made with flask.
"""
from flask import Flask, render_template, redirect, request, session
from flask_session import Session
import pymysql
import bcrypt

app = Flask(__name__)

app.config["SESSION_PERMANENT"] = False     
app.config["SESSION_TYPE"] = "filesystem"     
Session(app)  

connection = pymysql.connect(host="localhost", port=3306, 
                            user="root", password="", database="blog_platform")
cursor = connection.cursor()

@app.route('/', methods=["GET", "POST"])
def index():
    """
    index page that allows users to log in.
    """
    return render_template("index.html")

@app.route('/dashboard')
def dashboard():
    """
    dashboard page that only admins can access.
    """
    if not session.get("admin") or not session.get("admin_id"):
        return redirect("/")

    cursor.execute("SELECT COUNT(*) as total_users FROM users")
    users = cursor.fetchall()

    return render_template("dashboard.html", users=users)

@app.route('/login', methods=["GET", "POST"])
def login():
    """
    Log in page.
    """
    if request.method == "POST":
        username = request.form.get("username")
        password = request.form.get("password")
        cursor.execute("SELECT * FROM admins WHERE username=%s", (username))
        data = cursor.fetchall()
        if data and bcrypt.checkpw(password.encode('utf-8'), data[0][2].encode('utf-8')):
            session["admin"] = username
            session["admin_id"] = data[0][0]
            return redirect("dashboard")
        else:
            return redirect("/")
    return redirect("/")

if __name__ == '__main__':
    app.run()
