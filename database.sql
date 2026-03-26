
CREATE TABLE IF NOT EXISTS users(
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100),
email VARCHAR(100),
password VARCHAR(100),
role VARCHAR(20)
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
INSERT INTO users(name,email,password,role) VALUES
('Admin User', 'admin@cybercrime.gov', 'admin123', 'admin'),
('Police Officer', 'police@cybercrime.gov', 'police123', 'police'),
('John Doe', 'user@gmail.com', 'user123', 'user');
