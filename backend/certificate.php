<?php
require_once 'db.php';
require_once 'auth/session.php';

requireLogin();

$userId = getCurrentUserId();
$courseId = $_GET['course_id'] ?? 0;

if (!$courseId) {
    header("Location: dashboard.php");
    exit;
}

// Verify Enrollment
$stmt = $pdo->prepare("SELECT id FROM user_courses WHERE user_id = ? AND course_id = ?");
$stmt->execute([$userId, $courseId]);
if (!$stmt->fetch()) {
    die("Access Denied: Not enrolled.");
}

// Fetch Certificate (Proof of passing)
$stmt = $pdo->prepare("SELECT * FROM certificates WHERE user_id = ? AND course_id = ?");
$stmt->execute([$userId, $courseId]);
$cert = $stmt->fetch();

if (!$cert) {
    die("Certificate not found. You must pass the Final Exam to receive your certificate. <a href='final_test.php?course_id=$courseId'>Take Exam</a>");
}

$certCode = $cert['certificate_uuid'];
$date = date('F j, Y', strtotime($cert['issued_at']));

// Fetch Course Info
$stmt = $pdo->prepare("SELECT title FROM courses WHERE id = ?");
$stmt->execute([$courseId]);
$course = $stmt->fetch();
$courseTitle = $course['title'];
$instructorName = "Mini Coursera Instructor";

// Fetch User Info
$stmt = $pdo->prepare("SELECT full_name FROM users WHERE id = ?");
$stmt->execute([$userId]);
$studentName = $stmt->fetchColumn();

// Fallback if DB fetch fails or is empty
if (empty($studentName)) {
    $studentName = $_SESSION['full_name'] ?? 'Student';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - <?= htmlspecialchars($courseTitle); ?></title>
    <style>
        body { font-family: 'Georgia', serif; background-color: #f0f0f0; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .certificate-container { background: white; width: 800px; padding: 50px; text-align: center; border: 20px solid #0056d2; box-shadow: 0 0 20px rgba(0,0,0,0.2); position: relative; }
        .header { font-size: 3rem; color: #0056d2; margin-bottom: 10px; }
        .sub-header { font-size: 1.5rem; color: #555; margin-bottom: 40px; }
        .student-name { font-size: 2.5rem; font-weight: bold; border-bottom: 2px solid #333; display: inline-block; padding: 0 20px 10px; margin-bottom: 20px; font-family: 'Arial', sans-serif; }
        .course-title { font-size: 2rem; color: #333; margin: 20px 0; font-weight: bold; }
        .date { font-size: 1.2rem; color: #666; margin-top: 40px; }
        .code { font-family: monospace; color: #999; font-size: 0.9rem; margin-top: 20px; }
        .stamp { position: absolute; bottom: 50px; right: 50px; border: 3px solid #0056d2; color: #0056d2; padding: 10px; transform: rotate(-15deg); font-weight: bold; font-size: 1.2rem; border-radius: 5px; opacity: 0.8; }
        .btn-print { margin-top: 30px; padding: 10px 20px; background: #333; color: white; border: none; cursor: pointer; display: inline-block; text-decoration: none; border-radius: 4px; font-family: sans-serif; }
        @media print { .btn-print { display: none; } body { background: white; } .certificate-container { box-shadow: none; border: 5px solid #0056d2; width: 100%; } }
    </style>
</head>
<body>
    <div class="certificate-container">
        <h1 class="header">Certificate of Completion</h1>
        <p class="sub-header">This is to certify that</p>
        
        <div class="student-name"><?= htmlspecialchars($studentName); ?></div>
        
        <p class="sub-header">has successfully completed the course</p>
        
        <div class="course-title"><?= htmlspecialchars($courseTitle); ?></div>
        
        <p>Instructor: <?= htmlspecialchars($instructorName); ?></p>
        
        <div class="date">Issued on: <?= $date; ?></div>
        <div class="code">Certificate ID: <?= $certCode; ?></div>
        
        <div class="stamp">VERIFIED</div>
        
        <a href="javascript:window.print()" class="btn-print">Print Certificate</a>
        <a href="dashboard.php" class="btn-print" style="background-color: #0056d2;">Back to Dashboard</a>
    </div>
</body>
</html>
