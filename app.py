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
            user = cur.execute(
                "SELECT * FROM admin WHERE email=?",
                (email,)).fetchone()
            if user and check_password_hash(user[3], password):
                session["admin_id"] = user[0]
                session["role"] = "admin"
                conn.close()
                return redirect("/admin_dashboard")
        elif user_type == "police":
            dept = cur.execute(
                "SELECT * FROM police_department WHERE email=?",
                (email,)).fetchone()
            if dept and check_password_hash(dept[4], password):
                session["dept_id"] = dept[0]
                session["role"] = "police"
                conn.close()
                return redirect("/police_dashboard")
        else:
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

    if session.get("role")!="admin":
        return redirect("/")

    conn=get_db()
    cur=conn.cursor()

    users=cur.execute("SELECT * FROM users").fetchall()
    departments=cur.execute("SELECT * FROM police_department").fetchall()

    conn.close()

    return render_template("admin_dashboard.html",users=users, departments=departments)


# POLICE DASHBOARD
@app.route("/police_dashboard")
def police_dashboard():
    if session.get("role") != "police":
        return redirect("/")
    
    dept_id = session["dept_id"]
    conn = get_db()
    cur = conn.cursor()
    
    complaints = cur.execute("""
        SELECT c.*, u.name as user_name 
        FROM complaints c 
        JOIN users u ON c.user_id = u.user_id 
        WHERE c.dept_id = ?
    """, (dept_id,)).fetchall()
    
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
        SELECT c.*, pd.officer_name, s.progress 
        FROM complaints c 
        LEFT JOIN police_department pd ON c.dept_id = pd.dept_id 
        LEFT JOIN status s ON c.complaint_id = s.complaint_id 
        WHERE c.user_id = ?
    """, (user_id,)).fetchall()
    
    conn.close()
    
    return render_template("user_dashboard.html", complaints=complaints)


# SUBMIT COMPLAINT
@app.route("/complaint",methods=["GET","POST"])
def complaint():
    if request.method == "POST":
        if session.get("role") != "user":
            return redirect("/")
        
        description = request.form["description"]
        dept_id = request.form["dept_id"]
        tracking_id = str(uuid.uuid4())[:8]
        user_id = session["user_id"]
        
        evidence = ""
        if "evidence" in request.files:
            file = request.files["evidence"]
            if file and file.filename != "":
                filename = secure_filename(file.filename)
                file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))
                evidence = filename
        
        conn = get_db()
        cur = conn.cursor()
        
        cur.execute("""
            INSERT INTO complaints(user_id, description, dept_id, complaint_data, tracking_id) 
            VALUES(?,?,?,?,?)
        """, (user_id, description, dept_id, evidence, tracking_id))
        
        complaint_id = cur.lastrowid
        
        cur.execute("""
            INSERT INTO status(complaint_id, department_id, progress) 
            VALUES(?,?,?)
        """, (complaint_id, dept_id, "Pending"))
        
        conn.commit()
        conn.close()
        
        return f"Complaint Submitted. Tracking ID: {tracking_id}"
    
    # Get departments for dropdown
    conn = get_db()
    cur = conn.cursor()
    departments = cur.execute("SELECT * FROM police_department").fetchall()
    conn.close()
    
    return render_template("complaint.html", departments=departments)


# UPDATE STATUS (POLICE)
@app.route("/update_status/<int:complaint_id>", methods=["POST"])
def update_status(complaint_id):
    if session.get("role") != "police":
        return redirect("/")
    
    progress = request.form["progress"]
    dept_id = session["dept_id"]
    
    conn = get_db()
    cur = conn.cursor()
    
    cur.execute("""
        UPDATE status 
        SET progress = ? 
        WHERE complaint_id = ? AND department_id = ?
    """, (progress, complaint_id, dept_id))
    
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