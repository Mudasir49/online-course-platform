# 🚀 How to Run "Mini-Coursera" on XAMPP

This guide will help you set up the project using XAMPP on Windows.

## 1. Install & Setup XAMPP
1. **Download XAMPP**: [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html) (PHP 8.0 or higher recommended).
2. **Install**: Follow the installer steps. Default location is `C:\xampp`.
3. **Start Servers**:
   - Open **XAMPP Control Panel**.
   - Click **Start** next to **Apache**.
   - Click **Start** next to **MySQL**.
   - Ensure both turn green.

## 2. Deploy Code
1. Navigate to the `htdocs` folder: `C:\xampp\htdocs`.
2. Create a folder named `dsa`.
3. Copy **all** project files into `C:\xampp\htdocs\dsa`.
   - You should see folders like `backend`, `frontend`, `sql` inside `C:\xampp\htdocs\dsa`.

## 3. Setup Database
1. Open your browser and go to: [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
2. Click **New** in the sidebar.
3. Database name: `coursera_db`.
4. Click **Create**.
5. Click on the new `coursera_db` database in the left sidebar.
6. Click the **Import** tab at the top.
7. Click **Choose File** and select `C:\xampp\htdocs\dsa\sql\course_dump.sql`.
8. Click **Import** (or Go) at the bottom.
   - *Success message: "Import has been successfully finished..."*

## 4. Verify Configuration
- The project is pre-configured for XAMPP defaults:
  - **Host**: `localhost`
  - **User**: `root`
  - **Password**: (empty)
- If you set a root password for MySQL, open `backend/db.php` and update line 7:
  ```php
  $pass = 'YOUR_PASSWORD';
  ```

## 5. View the Project
1. **Open Browser**: Go to [http://localhost/dsa/backend/dashboard.php](http://localhost/dsa/backend/dashboard.php).
2. **Login**:
   - **Email**: `student@example.com`
   - **Password**: `Password123!`

## 6. Validating Features
- **Dashboard**: You should see 5 courses.
- **Purchase**: Click a course -> Purchase (Mock).
- **Lecture**: Complete Lecture 1 MCQs (Answer 5+ correctly).
- **Final Exam**: Complete all lectures to unlock. Pass >80% to get Certificate.
- **DSA Page**: Go to [http://localhost/dsa/backend/progress.php](http://localhost/dsa/backend/progress.php) to see the Graph/Heap demo.

## Troubleshooting
- **404 Not Found**: Check if your folder name in `htdocs` is exactly `dsa`.
- **Database Error**: Ensure MySQL is running in XAMPP control panel.
