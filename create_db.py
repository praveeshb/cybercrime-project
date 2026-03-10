import sqlite3

conn = sqlite3.connect("database.db")

conn.execute("""
CREATE TABLE users(
id INTEGER PRIMARY KEY AUTOINCREMENT,
name TEXT,
email TEXT,
password TEXT
)
""")

conn.execute("""
CREATE TABLE admin(
id INTEGER PRIMARY KEY AUTOINCREMENT,
email TEXT,
password TEXT
)
""")

conn.execute("""
CREATE TABLE complaint(
id INTEGER PRIMARY KEY AUTOINCREMENT,
user TEXT,
description TEXT,
status TEXT,
evidence TEXT
)
""")

conn.commit()
conn.close()

print("Database Created")