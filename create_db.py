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
CREATE TABLE IF NOT EXISTS police(
id INTEGER PRIMARY KEY AUTOINCREMENT,
username TEXT,
password TEXT
)
""")

conn.execute("DROP TABLE IF EXISTS complaint")

conn.execute("""
CREATE TABLE IF NOT EXISTS complaint(
id INTEGER PRIMARY KEY AUTOINCREMENT,
tracking_id TEXT,
name TEXT,
crime TEXT,
description TEXT,
status TEXT,
evidence TEXT
)
""")

conn.execute("INSERT INTO police (username, password) VALUES (?, ?)", ("police", "123"))

conn.commit()
conn.close()

print("Database Created")