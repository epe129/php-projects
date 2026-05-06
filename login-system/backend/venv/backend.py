import tkinter as tk
from tkinter import ttk
import mysql.connector
import config

mydb = mysql.connector.connect(
  host=config.data["host"],
  user=config.data["user"],
  password=config.data["password"],
  database=config.data["database"]
)

cursor = mydb.cursor()

root = tk.Tk()

root.title("Welcome to GeekForGeeks")

root.geometry('350x200')


cursor.execute("SELECT * FROM mydata")

dbresult = cursor.fetchall()

print(dbresult)

def delete_user():
  email = lbl.get()
  print(email, "deleted")
  cursor.execute("DELETE FROM mydata WHERE email = %s", (email,))
  mydb.commit()

n = tk.StringVar()
lbl = ttk.Combobox(root, textvariable=n)
lbl['values'] = [i[1] for i in dbresult]
btn = tk.Button(root, text='delete user', command=delete_user)
lbl.pack()
btn.pack(side='top')

root.mainloop()

