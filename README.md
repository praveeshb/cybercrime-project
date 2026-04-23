# Cybercrime Reporting System (PHP)

## Setup Instructions

### 1. XAMPP Installation
1. Download and install XAMPP from https://www.apachefriends.org/
2. Start Apache and MySQL services from XAMPP Control Panel

### 2. Database Setup
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Create a new database named `cybercrime`
3. Import the `database.sql` file
4. The database will be created with demo users

### 3. Project Setup
1. Copy the cybercrime_project folder to: `C:\xampp\htdocs\`
2. Access the application: http://localhost/cybercrime_project/

### 4. Demo Accounts
- **Admin**: admin@cybercrime.gov / admin123
- **Police**: police@cybercrime.gov / police123  
- **User**: user@gmail.com / user123

## Features
- User registration and login
- Complaint submission with file upload
- Admin dashboard for system management
- Police dashboard for case investigation
- Complaint tracking system

## Data Flow Diagram (DFD)

This project follows a role-based complaint handling flow between three external entities:
- User
- Admin
- Cyber Crime Department (Police)

### Level-0 (Context-Level DFD)

At a high level, all actors interact with the Cybercrime Reporting System:
- User sends registration/login details, complaint details, and receives complaint status.
- Admin sends login data, retrieves complaint/user records, and sends verification or update actions.
- Police sends login data, receives approved complaint details, and sends investigation status updates.

### Level-1 (Process-Level DFD)

#### User Flow
- Register/Login process stores and retrieves user login details.
- Complaint process stores complaint details.
- Complaint Status process retrieves case status for tracking.

#### Admin Flow
- Admin Login retrieves admin credentials.
- View Complaint Verification retrieves complaint details for validation.
- Complaint Update/Delete updates and manages complaint records.

#### Police Flow
- Police Register/Login saves and retrieves police login details.
- Complaint Approval process retrieves complaint details and marks accepted cases.
- Investigation Update process saves and retrieves investigation status updates.

### Data Stores Referenced in DFD
- User Login data
- Admin Login data
- Police Login data
- Complaint Details
- Complaint Status / Investigation Status

## Project Structure
```
cybercrime_project/
├── config.php              # Database connection
├── index.php               # Main login page
├── register.php            # User registration
├── logout.php              # Logout functionality
├── database.sql            # Database schema
├── test.php                # Database connection test
├── uploads/                # File upload directory
├── admin/
│   └── dashboard.php     # Admin dashboard
├── police/
│   ├── dashboard.php      # Police dashboard
│   └── update.php         # Status update
└── user/
    ├── dashboard.php      # User dashboard
    ├── complaint.php      # Submit complaints
    └── track.php          # Track complaints
```

## Security Notice
⚠️ **This is a basic prototype with significant security vulnerabilities including SQL injection, plain text passwords, and broken access controls. Do not use in production without proper security hardening.**
