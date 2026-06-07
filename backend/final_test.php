<?php
// backend/final_test.php
require_once 'db.php';
require_once 'auth/session.php';
requireLogin();

$userId = getCurrentUserId();
$courseId = $_GET['course_id'] ?? 0;

if (!$courseId) { header('Location: dashboard.php'); exit; }

// Check if all lectures completed
$stmt = $pdo->prepare("SELECT COUNT(id) FROM lectures WHERE course_id = ?");
$stmt->execute([$courseId]);
$totalLectures = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(id) FROM user_progress WHERE user_id = ? AND course_id = ?");
$stmt->execute([$userId, $courseId]);
$completedLectures = $stmt->fetchColumn();

if ($completedLectures < $totalLectures) {
    die("You must complete all lectures before taking the final exam.");
}

// Fetch Final Test Questions
$stmt = $pdo->prepare("SELECT * FROM final_tests WHERE course_id = ?");
$stmt->execute([$courseId]);
$questions = $stmt->fetchAll();

if (!$questions) { die("No final exam found for this course."); }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Final Exam</title>
    <link rel="stylesheet" href="../frontend/style.css">
    <style>
        .exam-container { background: #fff; padding: 30px; border-radius: 8px; max-width: 800px; margin: 20px auto; }
        .question-block { margin-bottom: 25px; border-bottom: 2px solid #f0f0f0; padding-bottom: 20px; }
    </style>
</head>
<body>
    <header>
        <h1>Final Exam</h1>
    </header>
    <div class="container">
        <div class="exam-container">
            <h2>Course Final Exam</h2>
            <p class="alert"><strong>Warning:</strong> You must score at least <strong>80%</strong> to pass. If you fail, your progress for this course will be <strong>RESET</strong> and you will have to retake all lectures.</p>
            
            <form action="api/submit_final.php" method="POST" onsubmit="return confirm('Are you sure you want to submit? This cannot be undone.');">
                <input type="hidden" name="course_id" value="<?= $courseId ?>">
                
                <?php foreach ($questions as $index => $q): ?>
                    <div class="question-block">
                        <p><strong>Q<?= $index+1 ?>: <?= htmlspecialchars($q['question']) ?> (10 marks)</strong></p>
                        <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="A" required> <?= htmlspecialchars($q['option_a']) ?></label><br>
                        <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="B"> <?= htmlspecialchars($q['option_b']) ?></label><br>
                        <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="C"> <?= htmlspecialchars($q['option_c']) ?></label><br>
                        <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="D"> <?= htmlspecialchars($q['option_d']) ?></label>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="btn btn-success btn-block">Submit Final Exam</button>
            </form>
        </div>
    </div>
</body>
</html>
