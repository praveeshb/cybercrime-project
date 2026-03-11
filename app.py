from flask import Flask,render_template,request,redirect,session
import sqlite3

app = Flask(__name__)
app.secret_key="secret"


def get_db():
    return sqlite3.connect("database.db")


# LOGIN
@app.route("/",methods=["GET","POST"])
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

        if user:

            session["user_id"]=user[0]
            session["role"]=user[4]

            if user[4]=="admin":
                return redirect("/admin")

            elif user[4]=="police":
                return redirect("/police")

            else:
                return redirect("/user")

    return render_template("login.html")


# ADMIN DASHBOARD
@app.route("/admin")
def admin():

    if session.get("role")!="admin":
        return redirect("/")

    conn=get_db()
    cur=conn.cursor()

    complaints=cur.execute("SELECT * FROM complaint").fetchall()

    conn.close()

    return render_template("admin_dashboard.html",complaints=complaints)


# POLICE DASHBOARD
@app.route("/police")
def police():

    if session.get("role")!="police":
        return redirect("/")

    conn=get_db()
    cur=conn.cursor()

    complaints=cur.execute("SELECT * FROM complaint").fetchall()

    conn.close()

    return render_template("police_dashboard.html",complaints=complaints)


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
        user_id=session["user_id"]

        conn=get_db()
        cur=conn.cursor()

        cur.execute(
        "INSERT INTO complaint(user_id,description,status) VALUES(?,?,?)",
        (user_id,description,"Pending"))

        conn.commit()
        conn.close()

        return redirect("/user")

    return render_template("complaint.html")


# UPDATE STATUS (POLICE)
@app.route("/update/<int:id>")
def update(id):

    conn=get_db()
    cur=conn.cursor()

    cur.execute("UPDATE complaint SET status='Investigating' WHERE id=?",(id,))

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