CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(250) NOT NULL
);

INSERT INTO users (username, password) VALUES ('admin', 'password123');
