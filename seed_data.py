import sqlite3
from werkzeug.security import generate_password_hash

conn = sqlite3.connect("database.db")
cur = conn.cursor()

# Insert demo admin
cur.execute("INSERT INTO admin(name,email,password) VALUES(?,?,?)", 
           ("System Admin", "admin@cybercrime.gov", generate_password_hash("admin123")))

# Insert demo police officers
cur.execute("INSERT INTO police(name,email,password,department) VALUES(?,?,?,?)", 
           ("Officer Smith", "cyber@police.gov", generate_password_hash("police123"), "Cyber Crime"))
cur.execute("INSERT INTO police(name,email,password,department) VALUES(?,?,?,?)", 
           ("Officer Davis", "investigation@police.gov", generate_password_hash("police123"), "General Investigation"))

conn.commit()
conn.close()

print("Demo accounts created:")
print("Admin: admin@cybercrime.gov / admin123")
print("Police (Cyber): cyber@police.gov / police123")
print("Police (Investigation): investigation@police.gov / police123")
