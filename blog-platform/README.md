# Blog Platform

A simple PHP blog system with user registration, login, and personal vlogs.

## Setup

1. Create a MySQL database named `blog_platform`.
2. Update `db.php` with your database credentials.
3. Run the SQL below in your database manager:

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE vlogs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Usage

- Open `register.php` to create an account.
- Open `login.php` to sign in.
- After login, `dashboard.php` lets the user write a vlog and view only their own vlogs.
