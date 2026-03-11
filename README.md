# Cybercrime Project

A simple Flask application for filing and tracking complaints, with separate user, admin, and police dashboards. Designed for deployment on Render.

## Structure

- `app.py` - main Flask application
- `create_db.py` - script to initialize the SQLite database
- `templates/` - HTML templates for pages
- `static/` - CSS and uploads

## Deployment

1. Push to GitHub
2. Render automatically deploys based on the `Procfile`

## Usage

Visit the following paths in your browser:

- `/` → Home
- `/register` → User registration
- `/login` → User login
- `/dashboard` → User dashboard
- `/complaint` → File a complaint
- `/status` → Track a complaint by ID
- `/admin_login` & `/admin_dashboard` → Admin panel
- `/police_login` & `/police` → Police dashboard
