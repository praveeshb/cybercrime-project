import sqlite3
from werkzeug.security import generate_password_hash

conn = sqlite3.connect("database.db")
cur = conn.cursor()

# Insert demo admin
cur.execute("INSERT INTO admin(name,email,password) VALUES(?,?,?)", 
           ("Admin User", "admin@gmail.com", generate_password_hash("123")))

# Insert demo police departments
cur.execute("INSERT INTO police_department(officer_name,phone,email,password) VALUES(?,?,?,?)", 
           ("Officer Smith", "555-0101", "cyber@police.gov", generate_password_hash("123")))
cur.execute("INSERT INTO police_department(officer_name,phone,email,password) VALUES(?,?,?,?)", 
           ("Officer Johnson", "555-0102", "fraud@police.gov", generate_password_hash("123")))

# Insert demo user
cur.execute("INSERT INTO users(name,email,password,address,phone) VALUES(?,?,?,?,?)", 
           ("John Doe", "user@gmail.com", generate_password_hash("123"), "123 Main St", "555-0123"))

conn.commit()
conn.close()

print("Demo accounts created:")
print("Admin: admin@gmail.com / 123")
print("Police (Cyber): cyber@police.gov / 123")
print("Police (Fraud): fraud@police.gov / 123")
print("User: user@gmail.com / 123")
