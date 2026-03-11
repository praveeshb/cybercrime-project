import sqlite3

conn = sqlite3.connect("database.db")
cur = conn.cursor()

# Insert demo admin
cur.execute("INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)", ("Admin User", "admin@gmail.com", "123", "admin"))

# Insert demo police
cur.execute("INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)", ("Police Officer", "police@gmail.com", "123", "police"))

# Insert demo user
cur.execute("INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)", ("John Doe", "user@gmail.com", "123", "user"))

conn.commit()
conn.close()

print("Demo accounts created:")
print("Admin: admin@gmail.com / 123")
print("Police: police@gmail.com / 123")
print("User: user@gmail.com / 123")
