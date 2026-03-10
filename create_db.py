import sqlite3

conn = sqlite3.connect("database.db")

conn.execute("""
CREATE TABLE IF NOT EXISTS users(
id INTEGER PRIMARY KEY AUTOINCREMENT,
name TEXT,
email TEXT,
password TEXT
)
""")

conn.execute("""
CREATE TABLE IF NOT EXISTS admin(
id INTEGER PRIMARY KEY AUTOINCREMENT,
email TEXT,
password TEXT
)
""")

conn.execute("""
CREATE TABLE IF NOT EXISTS complaint(
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