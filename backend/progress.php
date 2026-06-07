<?php
// backend/progress.php
require_once 'db.php';
require_once 'auth/session.php';
require_once 'dsa/Graph.php';

requireLogin();
$userId = getCurrentUserId();

// 2. DAG Status (Graph)
// Visualize the lecture dependency for the first course
$courseId = 1; 
$stmt = $pdo->prepare("SELECT * FROM lectures WHERE course_id = ? ORDER BY lecture_order ASC");
$stmt->execute([$courseId]);
$lectures = $stmt->fetchAll();

// Build Graph
$courseGraph = new LectureGraph();
foreach ($lectures as $l) {
    $courseGraph->addLecture($l['id'], $l['title']);
}
// Add linear dependencies
for ($i = 0; $i < count($lectures) - 1; $i++) {
    $courseGraph->addDependency($lectures[$i]['id'], $lectures[$i+1]['id']);
}

// Get completed
$stmt = $pdo->prepare("SELECT lecture_id FROM user_progress WHERE user_id = ? AND course_id = ?");
$stmt->execute([$userId, $courseId]);
$completedIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

$nextId = $courseGraph->getNextUnlocked($completedIds);
$nextTitle = "None (Course Completed or Not Started)";
foreach ($lectures as $l) {
    if ($l['id'] == $nextId) {
        $nextTitle = $l['title'];
        break;
    }
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
            <h1 class="text-4xl font-bold mb-4">My Progress</h1>
            <p class="text-xl text-muted-foreground">Track your learning journey with DAG optimization</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto space-y-8">
            <!-- Next Recommendation Card -->
            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="p-6 border-b border-border">
                    <h2 class="text-xl font-semibold flex items-center gap-2">
                        <i data-lucide="git-pull-request" class="h-6 w-6 text-primary"></i>
                        Next Up (DAG Traversal)
                    </h2>
                </div>
                <div class="p-6">
                    <p class="mb-4 text-muted-foreground">Based on your current progress in <strong>PHP for Beginners</strong>:</p>
                    <div class="bg-primary/10 border-l-4 border-primary p-4 rounded-r-md">
                        <h3 class="font-semibold text-lg text-foreground flex items-center gap-2">
                            <i data-lucide="play" class="h-5 w-5 text-primary"></i>
                            Recommended: <?= htmlspecialchars($nextTitle) ?>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Completed List -->
            <div class="bg-card border border-border rounded-lg shadow-sm">
                <div class="p-6 border-b border-border">
                    <h2 class="text-xl font-semibold flex items-center gap-2">
                        <i data-lucide="check-square" class="h-6 w-6 text-green-600"></i>
                        Your Completed Lectures
                    </h2>
                </div>
                <div class="p-0">
                    <?php if (empty($completedIds)): ?>
                        <div class="p-8 text-center text-muted-foreground">
                            No lectures completed yet.
                        </div>
                    <?php else: ?>
                        <ul class="divide-y divide-border">
                            <?php foreach ($completedIds as $cid): 
                                // Find title (inefficient but fine for small set)
                                $title = "Unknown";
                                foreach ($lectures as $l) { if ($l['id'] == $cid) { $title = $l['title']; break; } }
                            ?>
                                <li class="p-4 flex items-center gap-3 hover:bg-muted/50 transition-colors">
                                    <i data-lucide="check" class="h-5 w-5 text-green-500"></i>
                                    <span class="font-medium"><?= htmlspecialchars($title) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>
