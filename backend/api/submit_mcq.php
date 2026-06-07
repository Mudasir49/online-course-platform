<?php
// backend/api/submit_mcq.php
require_once '../db.php';
require_once '../auth/session.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = getCurrentUserId();
    $courseId = $_POST['course_id'];
    $lectureId = $_POST['lecture_id'];
    $userAnswers = $_POST['answers'] ?? [];

    // Fetch correct answers
    $stmt = $pdo->prepare("SELECT id, correct_option FROM mcqs WHERE lecture_id = ?");
    $stmt->execute([$lectureId]);
    $correctAnswers = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // id => correct_option

    $score = 0;
    $total = count($correctAnswers);

    foreach ($userAnswers as $qId => $ans) {
        if (isset($correctAnswers[$qId]) && $correctAnswers[$qId] === $ans) {
            $score++;
        }
    }

    $passed = ($score >= 5);
    $msg = "You scored $score / $total.";

    if ($passed) {
        // Record progress
        $stmt = $pdo->prepare("INSERT IGNORE INTO user_progress (user_id, course_id, lecture_id) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $courseId, $lectureId]);
        
        $msg .= " Passed! Lecture marked as complete.";
        echo "<script>alert('$msg'); window.location.href = '../course.php?id=$courseId';</script>";
    } else {
        $msg .= " You need at least 5 correct to pass. Try again.";
        echo "<script>alert('$msg'); window.history.back();</script>";
    }
}
