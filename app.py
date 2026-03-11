from flask import Flask,render_template,request,redirect,session
import sqlite3
import uuid
import os
from werkzeug.utils import secure_filename

app = Flask(__name__)
app.secret_key="cybercrime_secret"
app.config['UPLOAD_FOLDER'] = 'static/uploads'

# Create uploads folder if it doesn't exist
os.makedirs(app.config['UPLOAD_FOLDER'], exist_ok=True)


def get_db():
    return sqlite3.connect("database.db")


# LOGIN (HOMEPAGE)
@app.route("/", methods=["GET","POST"])
def login():

    if request.method=="POST":

        email=request.form["email"]
        password=request.form["password"]

        conn=get_db()
        cur=conn.cursor()

        user=cur.execute(
        "SELECT * FROM users WHERE email=? AND password=?",
        (email,password)).fetchone()

        conn.close()

        if user is None:
            return "Invalid Login"

        # Save session
        session["user_id"]=user[0]
        session["role"]=user[4]

        if user[4]=="admin":
            return redirect("/admin_dashboard")

        elif user[4]=="police":
            return redirect("/police_dashboard")

        else:
            return redirect("/user_dashboard")

    return render_template("login.html")


# REGISTER
@app.route("/register",methods=["GET","POST"])
def register():

    if request.method=="POST":

        name=request.form["name"]
        email=request.form["email"]
        password=request.form["password"]

        conn=get_db()
        cur=conn.cursor()

        cur.execute(
        "INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)",
        (name,email,password,"user"))

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

    conn.close()

    return render_template("admin_dashboard.html",users=users)


# POLICE DASHBOARD
@app.route("/police_dashboard")
def police_dashboard():

    if session.get("role")!="police":
        return redirect("/")

    return render_template("police_dashboard.html")


# USER DASHBOARD
@app.route("/user")
def user():

    if session.get("role")!="user":
        return redirect("/")

    return render_template("user_dashboard.html")


# SUBMIT COMPLAINT
@app.route("/complaint",methods=["GET","POST"])
def complaint():

    if request.method=="POST":

        description=request.form["description"]
        tracking_id=str(uuid.uuid4())[:8]
        user_id=session["user_id"]
        evidence=""

        # Handle file upload
        if "evidence" in request.files:
            file=request.files["evidence"]
            if file and file.filename!="":
                filename=secure_filename(file.filename)
                file.save(os.path.join(app.config['UPLOAD_FOLDER'],filename))
                evidence=filename

        conn=get_db()
        cur=conn.cursor()

        cur.execute(
        "INSERT INTO complaints(tracking_id,user_id,description,evidence,status) VALUES(?,?,?,?,?)",
        (tracking_id,user_id,description,evidence,"Pending"))

        conn.commit()
        conn.close()

        return f"Complaint Submitted. Tracking ID: {tracking_id}"

    return render_template("complaint.html")


# UPDATE STATUS (POLICE)
@app.route("/update/<int:id>")
def update(id):

    conn=get_db()
    cur=conn.cursor()

    cur.execute("UPDATE complaints SET status='Investigating' WHERE id=?",(id,))

    conn.commit()
    conn.close()

    return redirect("/police")


# LOGOUT
@app.route("/logout")
def logout():

    session.clear()

    return redirect("/")


if __name__=="__main__":
    app.run(debug=True)