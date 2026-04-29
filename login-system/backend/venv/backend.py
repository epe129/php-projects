import tkinter as tk
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

def delete_user(email):
  print(email)
  cursor.execute("DELETE FROM mydata WHERE email = %s", (email,))
  mydb.commit()

for c in dbresult:
  lbl = tk.Label(root, text=f"{c}")
  btn = tk.Button(root, text='delete user', command=lambda email=c[1]: delete_user(email))
  lbl.pack()
  btn.pack(side='top')

root.mainloop()

