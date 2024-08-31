CREATE DATABASE IF NOT EXISTS hh_icare_assignment_6;

USE hh_icare_assignment_6;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(15) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT DEFAULT 0
);
        
CREATE TABLE IF NOT EXISTS transactions (
    id VARCHAR(15),
    user_id VARCHAR(15) NOT NULL,
    customer_id VARCHAR(15) NOT NULL,
    amount FLOAT NOT NULL,
    transaction_date VARCHAR(255) NOT NULL,
    transaction_type TINYINT
);

INSERT INTO users (user_id,`name`,email,`password`,`is_admin`) VALUES 
   ("669857aa97cbb", "Hasan Hafiz", "hasanhafizsakil@gmail.com","8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92", 1),
   ("66c0a6d492b2d", "Faisal", "faisal@gmail.com","8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92", 0),
   ("66c0a7d486152", "Hasan Samaun", "samaun@gmail.com","8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92", 0);
