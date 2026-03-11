import sqlite3

conn = sqlite3.connect("database.db")
cur = conn.cursor()

# USERS TABLE
cur.execute("""
CREATE TABLE IF NOT EXISTS users(
id INTEGER PRIMARY KEY AUTOINCREMENT,
name TEXT,
email TEXT,
password TEXT,
role TEXT
)
""")

# COMPLAINT TABLE
cur.execute("""
CREATE TABLE IF NOT EXISTS complaints(
id INTEGER PRIMARY KEY AUTOINCREMENT,
tracking_id TEXT,
user_id INTEGER,
description TEXT,
evidence TEXT,
status TEXT
)
""")

conn.commit()
conn.close()

print("Database Created")