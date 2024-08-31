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
   ("66be4b42571cd", "Adury Afrose", "adury@gmail.com","8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92", 0),
   ("66be4fb505907", "Sumon Nasir", "nasir@gmail.com","8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92", 0),
   ("66be5056058b9", "Muhammad Faijullah", "faijullah@gmail.com","8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92", 0),
   ("66c0a6d492b2d", "Faisal", "faisal@gmail.com","8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92", 0),
   ("66c0a7d486152", "Hasan Samaun", "samaun@gmail.com","8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92", 0);
   
   INSERT INTO `transactions` (`id`,`user_id`,`customer_id`,`amount`,`transaction_date`,`transaction_type`) VALUES 
("66c0a6f689a74","66c0a6d492b2d","66c0a6d492b2d","100","17 Aug 2024, 13:34:46 PM","1"),
("66c0a6f9c9881","66c0a6f9c9881","66c0a6d492b2d","500","17 Aug 2024, 13:34:49 PM", "1"),
("66c0a6fd28018","66c0a6d492b2d","66c0a6d492b2d","300","17 Aug 2024, 13:34:53 PM","1"),
("66c0a7004746f","66c0a6d492b2d","66c0a6d492b2d","100","17 Aug 2024, 13:34:56 PM","1"),
("66c0a709bd371","66c0a6d492b2d","66c0a6d492b2d","300","17 Aug 2024, 13:35:05 PM","2"),
("66c0a70d4dc40","66c0a6d492b2d","66c0a6d492b2d","100","17 Aug 2024, 13:35:09 PM","2"),
("66c0a72365f99","66c0a6d492b2d","66be4fb505907","300","17 Aug 2024, 13:35:31 PM","2"),
("66c0a7236623a","66be4fb505907","66c0a6d492b2d","300","17 Aug 2024, 13:35:31 PM","1");