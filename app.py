from flask import Flask,render_template,request,redirect,session
import sqlite3
import uuid
import os
from werkzeug.utils import secure_filename
from werkzeug.security import generate_password_hash, check_password_hash

app = Flask(__name__)
app.secret_key = os.environ.get('SECRET_KEY', 'cybercrime_secret')
app.config['UPLOAD_FOLDER'] = 'static/uploads'

# Create uploads folder if it doesn't exist
os.makedirs(app.config['UPLOAD_FOLDER'], exist_ok=True)


def get_db():
    return sqlite3.connect("database.db")


# LOGIN (HOMEPAGE)
@app.route("/", methods=["GET","POST"])
def login():
    if request.method == "POST":
        email = request.form["email"]
        password = request.form["password"]
        user_type = request.form.get("user_type", "user")
        
        conn = get_db()
        cur = conn.cursor()
        
        if user_type == "admin":
            admin = cur.execute(
                "SELECT * FROM admin WHERE email=?",
                (email,)).fetchone()
            if admin and check_password_hash(admin[3], password):
                session["admin_id"] = admin[0]
                session["role"] = "admin"
                conn.close()
                return redirect("/admin_dashboard")
        elif user_type == "police":
            police = cur.execute(
                "SELECT * FROM police WHERE email=?",
                (email,)).fetchone()
            if police and check_password_hash(police[3], password):
                session["police_id"] = police[0]
                session["role"] = "police"
                conn.close()
                return redirect("/police_dashboard")
        else:  # Regular user
            user = cur.execute(
                "SELECT * FROM users WHERE email=?",
                (email,)).fetchone()
            if user and check_password_hash(user[3], password):
                session["user_id"] = user[0]
                session["role"] = "user"
                conn.close()
                return redirect("/user_dashboard")
        
        conn.close()
        return "Invalid Login"
    
    return render_template("login.html")


# REGISTER
@app.route("/register",methods=["GET","POST"])
def register():
    if request.method == "POST":
        name = request.form["name"]
        email = request.form["email"]
        password = request.form["password"]
        address = request.form.get("address", "")
        phone = request.form.get("phone", "")
        
        hashed_password = generate_password_hash(password)
        
        conn = get_db()
        cur = conn.cursor()
        
        cur.execute(
            "INSERT INTO users(name,email,password,address,phone) VALUES(?,?,?,?,?)",
            (name, email, hashed_password, address, phone))
        
        conn.commit()
        conn.close()
        
        return redirect("/")
    
    return render_template("register.html")


# ADMIN DASHBOARD
@app.route("/admin_dashboard")
def admin_dashboard():
    if session.get("role") != "admin":
        return redirect("/")
    
    conn = get_db()
    cur = conn.cursor()
    
    users = cur.execute("SELECT * FROM users").fetchall()
    police = cur.execute("SELECT * FROM police").fetchall()
    complaints = cur.execute("SELECT * FROM complaints").fetchall()
    
    conn.close()
    
    return render_template("admin_dashboard.html", users=users, police=police, complaints=complaints)


# POLICE DASHBOARD
@app.route("/police_dashboard")
def police_dashboard():
    if session.get("role") != "police":
        return redirect("/")
    
    police_id = session["police_id"]
    conn = get_db()
    cur = conn.cursor()
    
    complaints = cur.execute("""
        SELECT * FROM complaints 
        WHERE assigned_police_id = ? OR assigned_police_id IS NULL
    """, (police_id,)).fetchall()
    
    conn.close()
    
    return render_template("police_dashboard.html", complaints=complaints)


# USER DASHBOARD
@app.route("/user_dashboard")
def user_dashboard():
    if session.get("role") != "user":
        return redirect("/")
    
    user_id = session["user_id"]
    conn = get_db()
    cur = conn.cursor()
    
    complaints = cur.execute("""
        SELECT c.*, p.name as police_name, p.department 
        FROM complaints c 
        LEFT JOIN police p ON c.assigned_police_id = p.police_id 
        WHERE c.user_id = ?
    """, (user_id,)).fetchall()
    
    conn.close()
    
    return render_template("user_dashboard.html", complaints=complaints)


# COMPLAINT FORM (User Only)
@app.route("/complaint", methods=["GET", "POST"])
def complaint():
    if session.get("role") != "user":
        return redirect("/")
    
    if request.method == "POST":
        user_id = session["user_id"]
        description = request.form["description"]
        tracking_id = str(uuid.uuid4())[:8]
        
        # Get user details from database
        conn = get_db()
        cur = conn.cursor()
        user = cur.execute("SELECT * FROM users WHERE user_id=?", (user_id,)).fetchone()
        
        evidence = ""
        if "evidence" in request.files:
            file = request.files["evidence"]
            if file and file.filename != "":
                filename = secure_filename(file.filename)
                file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))
                evidence = filename
        
        cur.execute("""
            INSERT INTO complaints(user_id, name, email, phone, description, evidence, tracking_id) 
            VALUES(?,?,?,?,?,?,?)
        """, (user_id, user[1], user[2], user[5], description, evidence, tracking_id))
        
        conn.commit()
        conn.close()
        
        return f"Complaint Submitted. Tracking ID: {tracking_id}"
    
    return render_template("complaint.html")


# ASSIGN COMPLAINT (Police)
@app.route("/assign/<int:complaint_id>", methods=["POST"])
def assign_complaint(complaint_id):
    if session.get("role") != "police":
        return redirect("/")
    
    police_id = session["police_id"]
    
    conn = get_db()
    cur = conn.cursor()
    
    cur.execute("""
        UPDATE complaints 
        SET assigned_police_id = ?, status = 'Investigating' 
        WHERE complaint_id = ?
    """, (police_id, complaint_id))
    
    conn.commit()
    conn.close()
    
    return redirect("/police_dashboard")


# UPDATE STATUS (Police)
@app.route("/update_status/<int:complaint_id>", methods=["POST"])
def update_status(complaint_id):
    if session.get("role") != "police":
        return redirect("/")
    
    status = request.form["status"]
    
    conn = get_db()
    cur = conn.cursor()
    
    cur.execute("""
        UPDATE complaints 
        SET status = ? 
        WHERE complaint_id = ?
    """, (status, complaint_id))
    
    conn.commit()
    conn.close()
    
    return redirect("/police_dashboard")


# LOGOUT
@app.route("/logout")
def logout():

    session.clear()

    return redirect("/")


if __name__=="__main__":
    app.run(debug=True)