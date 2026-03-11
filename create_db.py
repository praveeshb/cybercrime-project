import sqlite3

conn = sqlite3.connect("database.db")
cur = conn.cursor()

# USERS TABLE (updated with address and phone)
cur.execute("""
CREATE TABLE IF NOT EXISTS users(
user_id INTEGER PRIMARY KEY AUTOINCREMENT,
name TEXT,
email TEXT UNIQUE,
password TEXT,
address TEXT,
phone TEXT
)
""")

# ADMIN TABLE (separate from users)
cur.execute("""
CREATE TABLE IF NOT EXISTS admin(
admin_id INTEGER PRIMARY KEY AUTOINCREMENT,
name TEXT,
email TEXT UNIQUE,
password TEXT
)
""")

# POLICE DEPARTMENT TABLE
cur.execute("""
CREATE TABLE IF NOT EXISTS police_department(
dept_id INTEGER PRIMARY KEY AUTOINCREMENT,
officer_name TEXT,
phone TEXT,
email TEXT,
password TEXT
)
""")

# COMPLAINT TABLE (updated with dept_id)
cur.execute("""
CREATE TABLE IF NOT EXISTS complaints(
complaint_id INTEGER PRIMARY KEY AUTOINCREMENT,
user_id INTEGER,
description TEXT,
dept_id INTEGER,
complaint_data TEXT,
tracking_id TEXT,
FOREIGN KEY (user_id) REFERENCES users(user_id),
FOREIGN KEY (dept_id) REFERENCES police_department(dept_id)
)
""")

# STATUS TABLE
cur.execute("""
CREATE TABLE IF NOT EXISTS status(
complaint_id INTEGER,
department_id INTEGER,
progress TEXT DEFAULT 'Pending',
FOREIGN KEY (complaint_id) REFERENCES complaints(complaint_id),
FOREIGN KEY (department_id) REFERENCES police_department(dept_id)
)
""")

conn.commit()
conn.close()

print("Database Created with ER Diagram Structure")