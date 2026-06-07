<?php
// backend/api/submit_final.php
require_once '../db.php';
require_once '../auth/session.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = getCurrentUserId();
    $courseId = $_POST['course_id'];
    $userAnswers = $_POST['answers'] ?? [];

    // Fetch correct answers
    $stmt = $pdo->prepare("SELECT id, correct_option FROM final_tests WHERE course_id = ?");
    $stmt->execute([$courseId]);
    $correctKeys = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $totalQuestions = count($correctKeys);
    $correctCount = 0;

    foreach ($userAnswers as $qId => $ans) {
        if (isset($correctKeys[$qId]) && $correctKeys[$qId] === $ans) {
            $correctCount++;
        }
    }

    // 10 marks per question
    $marks = $correctCount * 10;
    $totalMarks = $totalQuestions * 10;
    $percentage = ($totalMarks > 0) ? ($marks / $totalMarks) * 100 : 0;

    if ($percentage >= 80) {
        // PASS
        // 1. Generate Certificate Data
        $certUuid = uniqid('CERT-');
        // Use ON DUPLICATE KEY UPDATE to ensure the new UUID is saved if they retake the exam
        $stmt = $pdo->prepare("INSERT INTO certificates (certificate_uuid, user_id, course_id) VALUES (?, ?, ?) 
                               ON DUPLICATE KEY UPDATE certificate_uuid = VALUES(certificate_uuid), issued_at = CURRENT_TIMESTAMP");
        $stmt->execute([$certUuid, $userId, $courseId]);

        // Redirect to success/certificate page
        header("Location: ../certificate.php?course_id=$courseId");
        exit;
    } else {
        // FAIL -> RESET PROGRESS
        // Delete user_progress for this course
        $stmt = $pdo->prepare("DELETE FROM user_progress WHERE user_id = ? AND course_id = ?");
        $stmt->execute([$userId, $courseId]);

        $failMsg = "You scored $marks / $totalMarks ($percentage%). The passing score is 80%. Your progress has been reset.";
        // Redirect to dashboard with failure message
        echo "<script>alert('$failMsg'); window.location.href = '../dashboard.php';</script>";
        exit;
    }
}
