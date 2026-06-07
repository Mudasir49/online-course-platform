<?php
// backend/api/purchase.php
require_once '../db.php';
require_once '../auth/session.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseId = $_POST['course_id'];
    $userId = getCurrentUserId();

    // Verify course exists
    // Insert into user_courses
    $stmt = $pdo->prepare("INSERT IGNORE INTO user_courses (user_id, course_id) VALUES (?, ?)");
    $stmt->execute([$userId, $courseId]);

    // Redirect to course page
    header("Location: ../course.php?id=$courseId");
    exit;
}
