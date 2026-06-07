<?php
// backend/course.php
require_once 'db.php';
require_once 'auth/session.php';
requireLogin();

$courseId = $_GET['id'] ?? 0;
$userId = getCurrentUserId();

if (!$courseId) { header('Location: dashboard.php'); exit; }

// Fetch Course
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$courseId]);
$course = $stmt->fetch();

if (!$course) { echo "Course not found."; exit; }

// Check Purchase
$stmt = $pdo->prepare("SELECT id FROM user_courses WHERE user_id = ? AND course_id = ?");
$stmt->execute([$userId, $courseId]);
$isPurchased = (bool)$stmt->fetch();

// Fetch Lectures if purchased
$lectures = [];
$progressMap = [];
if ($isPurchased) {
    $stmt = $pdo->prepare("SELECT * FROM lectures WHERE course_id = ? ORDER BY lecture_order ASC");
    $stmt->execute([$courseId]);
    $lectures = $stmt->fetchAll();

    // Fetch progress
    $stmt = $pdo->prepare("SELECT lecture_id FROM user_progress WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$userId, $courseId]);
    $progressMap = $stmt->fetchAll(PDO::FETCH_COLUMN); // Array of completed lecture IDs
}

// Logic for unlocking: simple linear. Unlocked if prev completed.
// First lecture always unlocked.
function isUnlocked($index, $lectures, $progressMap) {
    if ($index === 0) return true;
    $prevLectureId = $lectures[$index - 1]['id'];
    return in_array($prevLectureId, $progressMap);
}
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Redirecting logic to use the includes -->
</head>
<body class="bg-background text-foreground">
<?php require_once 'includes/header.php'; ?>

    <div class="bg-secondary/30 py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($course['title']) ?></h1>
            <p class="text-xl text-muted-foreground max-w-2xl mx-auto"><?= htmlspecialchars($course['description']) ?></p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <?php if (!$isPurchased): ?>
            <div class="max-w-md mx-auto bg-card text-card-foreground rounded-lg border border-border shadow-lg p-8 text-center">
                <div class="mb-6">
                    <div class="h-16 w-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="lock" class="h-8 w-8 text-primary"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-2">Enroll Now</h3>
                    <p class="text-muted-foreground">Get full access to all lectures and certification.</p>
                </div>
                
                <form action="api/purchase.php" method="POST">
                    <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                    <!-- Optional Stripe integration comment -->
                    <!-- Stripe.js would go here. For now, mock button. -->
                    <button type="submit" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-11 px-8">
                        Purchase (Mock)
                    </button>
                    <p class="text-xs text-muted-foreground mt-4">Safe and secure payment</p>
                </form>
            </div>
        <?php else: ?>
            <div class="max-w-4xl mx-auto">
                <h3 class="text-2xl font-semibold mb-6">Course Content</h3>
                <div class="bg-card border border-border rounded-lg shadow-sm overflow-hidden">
                    <ul class="divide-y divide-border">
                        <?php foreach ($lectures as $index => $lec): ?>
                            <?php 
                                $isCompleted = in_array($lec['id'], $progressMap);
                                $unlocked = isUnlocked($index, $lectures, $progressMap);
                            ?>
                            <li class="p-4 flex items-center justify-between hover:bg-muted/50 transition-colors <?= $unlocked ? '' : 'opacity-75 bg-muted/20' ?>">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0">
                                        <?php if ($isCompleted): ?>
                                            <i data-lucide="check-circle" class="h-6 w-6 text-green-500"></i>
                                        <?php elseif ($unlocked): ?>
                                            <i data-lucide="play-circle" class="h-6 w-6 text-primary"></i>
                                        <?php else: ?>
                                            <i data-lucide="lock" class="h-6 w-6 text-muted-foreground"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-muted-foreground">Lecture <?= $index + 1 ?></span>
                                        <h4 class="font-semibold <?= $unlocked ? 'text-foreground' : 'text-muted-foreground' ?>">
                                            <?= htmlspecialchars($lec['title']) ?>
                                        </h4>
                                    </div>
                                </div>
                                
                                <div>
                                    <?php if ($unlocked): ?>
                                        <a href="lecture.php?course=<?= $courseId ?>&lecture=<?= $lec['id'] ?>" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                                            <?= $isCompleted ? 'Review' : 'Start' ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-xs font-medium px-2.5 py-0.5 rounded bg-secondary text-secondary-foreground">Locked</span>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                        
                        <!-- Final Exam Link -->
                        <?php 
                           $allLecturesCompleted = count($progressMap) >= count($lectures) && count($lectures) > 0;
                        ?>
                        <li class="p-4 flex items-center justify-between bg-primary/5 hover:bg-primary/10 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0">
                                    <i data-lucide="award" class="h-6 w-6 <?= $allLecturesCompleted ? 'text-primary' : 'text-muted-foreground' ?>"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold">Final Exam</h4>
                                    <p class="text-sm text-muted-foreground">Pass the exam to earn your certificate</p>
                                </div>
                            </div>
                            <div>
                                <?php if ($allLecturesCompleted): ?>
                                    <a href="final_test.php?course_id=<?= $courseId ?>" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                                        Take Exam
                                    </a>
                                <?php else: ?>
                                    <span class="text-sm text-muted-foreground flex items-center gap-1">
                                        <i data-lucide="lock" class="h-3 w-3"></i> Locked
                                    </span>
                                <?php endif; ?>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>
