from flask import Flask,render_template,request,redirect
import sqlite3
import os
import random
import string
from werkzeug.utils import secure_filename

app = Flask(__name__)

UPLOAD_FOLDER = "uploads"
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

def generate_tracking_id():
    return ''.join(random.choices(string.ascii_uppercase + string.digits, k=8))

def db():
    return sqlite3.connect("database.db")

@app.route("/")
def home():
    return render_template("index.html")

@app.route("/register",methods=["GET","POST"])
def register():

    if request.method=="POST":

        name=request.form["name"]
        email=request.form["email"]
        password=request.form["password"]

        conn=db()

        conn.execute(
        "INSERT INTO users(name,email,password) VALUES(?,?,?)",
        (name,email,password))

        conn.commit()
        conn.close()

        return redirect("/login")

    return render_template("register.html")


@app.route("/login",methods=["GET","POST"])
def login():

    if request.method=="POST":

        email=request.form["email"]
        password=request.form["password"]

        conn=db()

        user=conn.execute(
        "SELECT * FROM users WHERE email=? AND password=?",
        (email,password)).fetchone()

        conn.close()

        if user:
            return redirect("/dashboard")

    return render_template("login.html")


@app.route("/dashboard")
def dashboard():
    return render_template("dashboard.html")


@app.route("/complaint",methods=["GET","POST"])
def complaint():

    if request.method=="POST":

        name = request.form['name']
        crime = request.form['crime']
        description = request.form['description']

        file = request.files['evidence']

        filename = secure_filename(file.filename)

        file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))

        tracking_id = generate_tracking_id()

        conn = sqlite3.connect("database.db")
        cur = conn.cursor()

        cur.execute("INSERT INTO complaint(tracking_id,name,crime,description,status,evidence) VALUES(?,?,?,?,?,?)",
        (tracking_id,name,crime,description,"Pending",filename))

        conn.commit()
        conn.close()

        return "Complaint Submitted. Your Tracking ID: " + tracking_id

    return render_template("complaint.html")


@app.route('/status', methods=['GET','POST'])
def status():

    status = None

    if request.method == "POST":

        tracking_id = request.form['tracking_id']

        conn = db()

        cur = conn.cursor()

        cur.execute("SELECT status FROM complaint WHERE tracking_id=?", (tracking_id,))

        data = cur.fetchone()

        if data:
            status = data[0]
        else:
            status = "Complaint Not Found"

        conn.close()

    return render_template("status.html", status=status)


@app.route('/police_dashboard')
def police_dashboard():

    conn = db()

    cur = conn.cursor()

    cur.execute("SELECT * FROM complaint")

    data = cur.fetchall()

    conn.close()

    return render_template("police_dashboard.html", complaints=data)


@app.route('/approve/<int:id>')
def approve(id):

    conn = sqlite3.connect("database.db")
    cur = conn.cursor()

    cur.execute("UPDATE complaint SET status='Approved' WHERE id=?", (id,))

    conn.commit()
    conn.close()

    return redirect("/police_dashboard")


@app.route('/reject/<int:id>')
def reject(id):

    conn = sqlite3.connect("database.db")
    cur = conn.cursor()

    cur.execute("UPDATE complaint SET status='Rejected' WHERE id=?", (id,))

    conn.commit()
    conn.close()

    return redirect("/police_dashboard")


@app.route('/users')
def users():

    conn = sqlite3.connect("database.db")
    cur = conn.cursor()

    cur.execute("SELECT * FROM users")

    data = cur.fetchall()

    conn.close()

    return render_template("users.html", users=data)


@app.route("/admin",methods=["GET","POST"])
def admin():

    if request.method=="POST":

        email=request.form["email"]
        password=request.form["password"]

        conn=db()

        admin=conn.execute(
        "SELECT * FROM admin WHERE email=? AND password=?",
        (email,password)).fetchone()

        conn.close()

        if admin:
            return redirect("/admin_dashboard")

    return render_template("admin_login.html")


@app.route('/admin_dashboard')
def admin_dashboard():

    return render_template("admin_dashboard.html")


@app.route("/admin_dashboard")
def admin_dashboard():

    conn=db()

    data=conn.execute("SELECT * FROM complaint").fetchall()

    conn.close()

    return render_template("admin_dashboard.html",data=data)


@app.route("/police")
def police():

    conn=db()

    data=conn.execute("SELECT * FROM complaint").fetchall()

    conn.close()

    return render_template("police_dashboard.html",data=data)


if __name__ == "__main__":
    port = int(os.environ.get("PORT", 5000))
    app.run(host="0.0.0.0", port=port)