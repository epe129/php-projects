from tkinter import *
import mysql.connector


mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="",
  database="mytest"
)

cursor = mydb.cursor()

cursor.execute("SELECT * FROM mydata")

dbresult = cursor.fetchall()

print(dbresult)

root = Tk()

root.title("Welcome to GeekForGeeks")

root.geometry('350x200')


root.mainloop()