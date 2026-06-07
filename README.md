# 📚 ONLINE COURSE PLATFORM

A **Data Structures & Algorithms e-learning web application** built with PHP, MySQL, and Tailwind CSS.
The platform uses real DSA concepts — a **Graph (BFS)** for lecture unlocking and a **Max-Heap** for the leaderboard — making it both a learning tool and a demonstration of applied DSA.



---

## 🎯 What Makes This Project Unique

Most student projects just display data. This one **uses DSA internally:**

| DSA Concept | Where It's Used |
|-------------|----------------|
| **Graph + BFS** | Lecture dependency system — unlocks next lecture only when prerequisites are completed |
| **Max-Heap (Priority Queue)** | Leaderboard system — always returns top-K users by score efficiently |

---

## ✨ Features

- 🔐 User authentication (register, login, logout, session management)
- 📖 Course browsing and enrollment
- 🔒 Sequential lecture unlocking using Graph BFS traversal
- 📝 MCQ quizzes after each lecture
- 🏆 Final exam to earn course certificate
- 📜 Auto-generated PDF certificates (FPDF library)
- 📊 Leaderboard using Max-Heap priority queue
- 📈 User progress tracking
- 🎨 Clean responsive UI with Tailwind CSS

---

## 🛠️ Tech Stack

**Backend**
![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)

**Frontend**
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat-square&logo=html5&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-06B6D4?style=flat-square&logo=tailwind-css&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black)

**Libraries**
- FPDF — PDF certificate generation
- Lucide Icons — UI icons

**Server**
- XAMPP (Apache + MySQL)

---

## 📁 Project Structure

```
online-course-platform/
│
├── backend/
│   ├── auth/
│   │   ├── login.php          # User login
│   │   ├── logout.php         # Session destroy
│   │   ├── register.php       # User registration
│   │   └── session.php        # Session management helpers
│   │
│   ├── api/
│   │   ├── purchase.php       # Course enrollment handler
│   │   ├── submit_mcq.php     # MCQ quiz submission
│   │   └── submit_final.php   # Final exam submission
│   │
│   ├── dsa/
│   │   ├── Graph.php          # LectureGraph class — BFS unlocking logic
│   │   └── Leaderboard.php    # LeaderboardHeap class — Max-Heap scoring
│   │
│   ├── includes/
│   │   ├── header.php         # Shared header/nav
│   │   └── footer.php         # Shared footer
│   │
│   ├── db.php                 # PDO database connection
│   ├── index.php              # Entry point (redirects to login)
│   ├── dashboard.php          # User dashboard — course list
│   ├── course.php             # Course detail + lecture list
│   ├── lecture.php            # Lecture viewer + MCQ
│   ├── final_test.php         # Final exam page
│   ├── leaderboard.php        # Leaderboard page
│   ├── progress.php           # User progress tracker
│   ├── certificate.php        # Certificate viewer/download
│   └── composer.json          # Composer dependencies (FPDF)
```

---

## 🧠 DSA Implementation Details

### Graph — Lecture Unlocking System
```
File: backend/dsa/Graph.php
Class: LectureGraph
```
Each lecture is a **node** in a directed graph. Edges represent dependencies (Lecture 1 → Lecture 2 → Lecture 3). When a user completes a lecture, **BFS traversal** determines which lecture becomes unlocked next. This ensures sequential learning and prevents skipping ahead.

```
Lecture 1 ──► Lecture 2 ──► Lecture 3 ──► Final Exam
  (root)      (unlocks       (unlocks      (unlocks after
              after L1)      after L2)      all lectures)
```

### Max-Heap — Leaderboard System
```
File: backend/dsa/Leaderboard.php
Class: LeaderboardHeap extends SplPriorityQueue
```
Uses PHP's built-in **SplPriorityQueue** as a Max-Heap. Every time a user completes a quiz, their score is inserted into the heap. The `getTop($k)` method efficiently extracts the top-K scoring users in O(K log N) time — much faster than sorting the entire database for every leaderboard request.

---

## 🚀 Setup & Installation

### Requirements
- XAMPP (PHP 8.0+, MySQL 5.7+)
- Composer

### Steps

**1. Clone the repository:**
```bash
git clone https://github.com/Mudasir49/online-course-platform.git
```

**2. Move to XAMPP htdocs:**
```bash
# Windows
xcopy /E /I online-course-platform C:\xampp\htdocs\online-course-platform

# Mac/Linux
cp -r online-course-platform /Applications/XAMPP/htdocs/
```

**3. Install dependencies:**
```bash
cd online-course-platform/backend
composer install
```

**4. Create the database:**
- Open XAMPP → Start Apache + MySQL
- Open [phpMyAdmin](http://localhost/phpmyadmin)
- Create a new database named: `coursera_db`
- Import the `database.sql` file

**5. Configure database (if needed):**

Edit `backend/db.php`:
```php
$host = 'localhost';
$db   = 'coursera_db';
$user = 'root';
$pass = '';        // your MySQL password
```

**6. Run the project:**

Open browser → [http://localhost/online-course-platform/backend](http://localhost/online-course-platform/backend)

---

## 📸 Pages Overview

| Page | Description |
|------|-------------|
| Login / Register | User authentication |
| Dashboard | Browse and enroll in courses |
| Course Page | Lecture list with lock/unlock status |
| Lecture Page | Video/content viewer + MCQ quiz |
| Final Exam | Timed exam after all lectures |
| Leaderboard | Top scorers via Max-Heap |
| Certificate | Auto-generated PDF on course completion |
| Progress | Track completed lectures per course |

---

## 💡 Key Concepts Demonstrated

- Full-stack PHP + MySQL web application
- PDO prepared statements (SQL injection prevention)
- Session-based authentication
- Applied Graph theory (BFS) in a real web feature
- Applied Heap data structure in a real web feature
- MVC-like separation (auth, api, dsa, includes)
- PDF generation with FPDF
- Responsive UI with Tailwind CSS
- Composer dependency management

---

## 📬 Contact

**Mudasir Ahmad**
📧 me.mudasirr@gmail.com
💼 [LinkedIn](https://www.linkedin.com/in/muhammad-mudasir-ahmad/)
