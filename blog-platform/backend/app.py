"""
admin that is made with flask.
"""
from flask import Flask, render_template
import pymysql

app = Flask(__name__)

connection = pymysql.connect(host="localhost", port=3306, user="root", password="")
cursor = connection.cursor()

@app.route('/')
def index():
    
        
    return render_template("index.html")

if __name__ == '__main__':
    app.run()