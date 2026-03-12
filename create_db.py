import sqlite3

conn = sqlite3.connect("database.db")
cur = conn.cursor()

# ADMIN TABLE
cur.execute("""
CREATE TABLE IF NOT EXISTS admin(
admin_id INTEGER PRIMARY KEY AUTOINCREMENT,
name TEXT,
email TEXT UNIQUE,
password TEXT
)
""")

# POLICE TABLE
cur.execute("""
CREATE TABLE IF NOT EXISTS police(
police_id INTEGER PRIMARY KEY AUTOINCREMENT,
name TEXT,
email TEXT UNIQUE,
password TEXT,
department TEXT
)
""")

# COMPLAINT TABLE (simplified)
cur.execute("""
CREATE TABLE IF NOT EXISTS complaints(
complaint_id INTEGER PRIMARY KEY AUTOINCREMENT,
name TEXT,
email TEXT,
phone TEXT,
description TEXT,
evidence TEXT,
status TEXT DEFAULT 'Pending',
assigned_police_id INTEGER,
tracking_id TEXT,
FOREIGN KEY (assigned_police_id) REFERENCES police(police_id)
)
""")

conn.commit()
conn.close()

print("Database Created - Admin and Police System")