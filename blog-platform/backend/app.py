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

    cursor.execute("SELECT COUNT(*) as total_posts FROM blogs")
    posts_total = cursor.fetchall()

    cursor.execute("SELECT user_id, title, content, created_at, is_public FROM blogs")
    posts = cursor.fetchall()

    return render_template("dashboard.html", users=users, posts_total=posts_total, posts=posts)

@app.route('/settings', methods=["GET", "POST"])
def settings():
    """
    settings page that only admins can access.
    """
    if not session.get("admin") or not session.get("admin_id"):
        return redirect("/")

    return render_template("settings.html")

@app.route('/delete', methods=["GET", "POST"])
def delete():
    """
    Delete post page.
    """

    if not session.get("admin") or not session.get("admin_id"):
        return redirect("/")
    
    cursor.execute("SELECT id, user_id, title, content, created_at FROM blogs")
    posts = cursor.fetchall()

    cursor.execute("SELECT id, username, email FROM users")
    users = cursor.fetchall()

    return render_template("delete.html", posts=posts, users=users)

@app.route('/delete_post', methods=["GET", "POST"])
def delete_post():
    """
    Handle post deletion.
    """
    if not session.get("admin") or not session.get("admin_id"):
        return redirect("/")

    if request.method == "POST":
        post_id = request.form.get("post_id")
        cursor.execute("SET FOREIGN_KEY_CHECKS=0")    
        cursor.execute("DELETE FROM blogs WHERE id=%s", (post_id))
        connection.commit()

        return redirect("/delete")

    return redirect("/delete")

@app.route('/delete_user', methods=["GET", "POST"])
def delete_user():
    """
    Handle user deletion.
    """
    if not session.get("admin") or not session.get("admin_id"):
        return redirect("/")

    if request.method == "POST":
        user_id = request.form.get("user_id")
        cursor.execute("SET FOREIGN_KEY_CHECKS=0")    
        cursor.execute("DELETE FROM users WHERE id=%s", (user_id))
        connection.commit()

        return redirect("/delete")

    return redirect("/delete")

@app.route('/change_password', methods=["GET", "POST"])
def change_password():
    """
    Handle password change.
    """
    pass

@app.route('/delete_account', methods=["GET", "POST"])
def delete_account():
    """
    Handle account deletion.
    """
    pass

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
