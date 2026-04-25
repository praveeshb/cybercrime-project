
CREATE TABLE IF NOT EXISTS users(
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100),
email VARCHAR(100),
password VARCHAR(100),
role VARCHAR(20),
aadhaar VARCHAR(20),
phone VARCHAR(20),
address TEXT
);

CREATE TABLE IF NOT EXISTS complaints(
id INT AUTO_INCREMENT PRIMARY KEY,
tracking_id VARCHAR(20),
user_id INT,
description TEXT,
evidence VARCHAR(200),
status VARCHAR(50)
);

-- Insert demo data
INSERT INTO users(name,email,password,role,aadhaar,phone,address) VALUES
('Admin User', 'admin@cybercrime.gov', 'admin123', 'admin', '123456789012', '9876543210', 'Head Office, Cybercrime HQ'),
('Police Officer', 'police@cybercrime.gov', 'police123', 'police', '111122223333', '9123456780', 'City Police Cyber Cell'),
('John Doe', 'user@gmail.com', 'user123', 'user', '999988887777', '9000011111', '42 Lake View Street');
