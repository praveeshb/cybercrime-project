from flask import Flask, render_template, request, redirect, session
import sqlite3
import datetime

app = Flask(__name__)
app.secret_key = "cybercrime"

# DATABASE CONNECTION
def get_db():
    conn = sqlite3.connect("database.db")
    conn.row_factory = sqlite3.Row
    return conn


# HOME PAGE
@app.route("/")
def index():
    return render_template("index.html")


# USER REGISTER
@app.route("/register", methods=["GET","POST"])
def register():

    if request.method == "POST":

        name = request.form["name"]
        email = request.form["email"]
        password = request.form["password"]
        address = request.form["address"]
        phone = request.form["phone"]

        conn = get_db()
        conn.execute("""
        INSERT INTO users(name,email,password,address,phone)
        VALUES(?,?,?,?,?)
        """,(name,email,password,address,phone))

        conn.commit()
        conn.close()

        return redirect("/login")

    return render_template("register.html")


# USER LOGIN
@app.route("/login", methods=["GET","POST"])
def login():

    if request.method == "POST":

        email = request.form["email"]
        password = request.form["password"]

        conn = get_db()

        user = conn.execute("""
        SELECT * FROM users WHERE email=? AND password=?
        """,(email,password)).fetchone()

        conn.close()

        if user:
            session["user_id"] = user["user_id"]
            return redirect("/dashboard")

    return render_template("login.html")


# USER DASHBOARD
@app.route("/dashboard")
def dashboard():

    if "user_id" not in session:
        return redirect("/login")

    return render_template("dashboard.html")


# SUBMIT COMPLAINT
@app.route("/complaint", methods=["GET","POST"])
def complaint():

    if request.method == "POST":

        description = request.form["description"]
        dept_id = request.form["dept_id"]

        user_id = session["user_id"]

        date = datetime.datetime.now()

        conn = get_db()

        conn.execute("""
        INSERT INTO complaint(user_id,description,dept_id,complaint_date)
        VALUES(?,?,?,?)
        """,(user_id,description,dept_id,date))

        conn.commit()
        conn.close()

        return redirect("/dashboard")

    return render_template("complaint.html")


# USER VIEW STATUS
@app.route("/status")
def status():

    user_id = session["user_id"]

    conn = get_db()

    data = conn.execute("""
    SELECT complaint.complaint_id, description, progress
    FROM complaint
    LEFT JOIN status
    ON complaint.complaint_id = status.complaint_id
    WHERE complaint.user_id=?
    """,(user_id,)).fetchall()

    conn.close()

    return render_template("status.html", data=data)


# ADMIN LOGIN
@app.route("/admin_login", methods=["GET","POST"])
def admin_login():

    if request.method == "POST":

        email = request.form["email"]
        password = request.form["password"]

        conn = get_db()

        admin = conn.execute("""
        SELECT * FROM admin WHERE email=? AND password=?
        """,(email,password)).fetchone()

        conn.close()

        if admin:
            return redirect("/admin_dashboard")

    return render_template("admin_login.html")


# ADMIN DASHBOARD
@app.route("/admin_dashboard")
def admin_dashboard():

    conn = get_db()

    complaints = conn.execute("""
    SELECT * FROM complaint
    """).fetchall()

    conn.close()

    return render_template("admin_dashboard.html", complaints=complaints)


# POLICE LOGIN
@app.route("/police_login", methods=["GET","POST"])
def police_login():

    if request.method == "POST":

        officer = request.form["officer"]

        session["officer"] = officer

        return redirect("/police_dashboard")

    return render_template("police_login.html")


# POLICE DASHBOARD
@app.route("/police_dashboard")
def police_dashboard():

    conn = get_db()

    complaints = conn.execute("""
    SELECT * FROM complaint
    """).fetchall()

    conn.close()

    return render_template("police_dashboard.html", complaints=complaints)


# UPDATE STATUS
@app.route("/update_status/<int:id>", methods=["POST"])
def update_status(id):

    progress = request.form["progress"]

    conn = get_db()

    conn.execute("""
    INSERT INTO status(complaint_id,department_id,progress)
    VALUES(?,?,?)
    """,(id,1,progress))

    conn.commit()
    conn.close()

    return redirect("/police_dashboard")


# LOGOUT
@app.route("/logout")
def logout():
    session.clear()
    return redirect("/")


if __name__ == "__main__":
    app.run(debug=True)