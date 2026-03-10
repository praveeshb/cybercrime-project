from flask import Flask,render_template,request,redirect
import sqlite3
import os

app = Flask(__name__)

UPLOAD_FOLDER = "static/uploads"
app.config["UPLOAD_FOLDER"] = UPLOAD_FOLDER

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

        user=request.form["user"]
        description=request.form["description"]

        file=request.files["evidence"]

        filename=file.filename

        file.save(os.path.join(app.config["UPLOAD_FOLDER"],filename))

        conn=db()

        conn.execute(
        "INSERT INTO complaint(user,description,status,evidence) VALUES(?,?,?,?)",
        (user,description,"Pending",filename))

        conn.commit()
        conn.close()

        return redirect("/status")

    return render_template("complaint.html")


@app.route("/status")
def status():

    conn=db()

    data=conn.execute("SELECT * FROM complaint").fetchall()

    conn.close()

    return render_template("status.html",data=data)


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