# Student Registration Portal

This PHP project stores accounts and student details in MySQL. PHP sessions are used only to keep track of the logged-in user.

## Set up the database

1. Start Apache and MySQL in XAMPP (or start your local PHP and MySQL services). Make sure PHP's `pdo_mysql` extension is enabled.
2. Open phpMyAdmin, select **Import**, and import [`schema.sql`](./schema.sql). Alternatively, run the SQL file in the MySQL command line.
3. Configure the database connection. The defaults are `localhost`, database `student_registration`, user `root`, and an empty password, which match a standard local XAMPP installation. Override them with the `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` environment variables if your MySQL setup uses different values.
4. Put the project in your web server's document root (for example, `htdocs/student_registration` in XAMPP) and open `http://localhost/student_registration/login.php`.

The database user needs permission to read and write the `users` and `student_details` tables. Do not use the empty-password `root` account outside a local development setup.

## How it works

- Signup stores the user's name, email, and a password hash in `users`.
- Login verifies the password hash from MySQL.
- Student details are saved in `student_details` and linked to the logged-in account. Submitting again updates that account's saved details.
- The database connection can be configured through `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD`.
