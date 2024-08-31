# **Project Name**
Bangubank (Assignment 05 )

# **Project Description**

There are two types of users: 'Admin' and 'Customer'. 

**Admin Features**
- See all transactions made by all users.
- Search and view transactions by a specific user using their email.
- View a list of all registered customers.

**Customer Features**
- Customers can register using their name, email, and password.
- Customers can log in using their registered email and password.
- See a list of all of their transactions.
- Deposit money to their account.
- Withdraw money from their account.
- Transfer money to another customer's account by specifying their email address.
- See the current balance of their account.

**Note:**
- Use OOP concepts.
- Use 'File' for storage.
- You’ll need to use ‘Session’ for the Logged in users functionality.
- You must use ‘Composer’ to autoload your files. Also, make sure to use Namespaces.
- You must share your Github repo link for this submission.

**Important**
Make sure to keep both 'File' and 'Database' for storage options that can be changed in the configuration file.

# Config File
Config.php file is inside src/Config directory. Default storage option is 'file.'

To work with File Storage, just change 'storage_type' to 'file'. 
To work with Database Storage, just change 'storage_type' to 'database'

# Creating Database
A db.sql file exists inside database folder. Copy the sql file content and run it in  phpmyadmin client, it will create database with two tables 'users' and 'transactions'


