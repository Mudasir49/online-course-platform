<?php
// backend/lecture.php
require_once 'db.php';
require_once 'auth/session.php';
requireLogin();

$userId = getCurrentUserId();
$courseId = $_GET['course'] ?? 0;
$lectureId = $_GET['lecture'] ?? 0;

if (!$courseId || !$lectureId) { header('Location: dashboard.php'); exit; }

// 1. Verify access to course (Purchased?)
$stmt = $pdo->prepare("SELECT id FROM user_courses WHERE user_id = ? AND course_id = ?");
$stmt->execute([$userId, $courseId]);
if (!$stmt->fetch()) {
    die("Access Denied: You must purchase this course first.");
}

// 2. Fetch Lecture Details
$stmt = $pdo->prepare("SELECT * FROM lectures WHERE id = ? AND course_id = ?");
$stmt->execute([$lectureId, $courseId]);
$lecture = $stmt->fetch();
if (!$lecture) { die("Lecture not found."); }

// 3. Verify Locking (Gating Logic)
// Get all course lectures ordered
$stmt = $pdo->prepare("SELECT id FROM lectures WHERE course_id = ? ORDER BY lecture_order ASC");
$stmt->execute([$courseId]);
$allLectures = $stmt->fetchAll(PDO::FETCH_COLUMN);

$currentPos = array_search($lectureId, $allLectures);

if ($currentPos > 0) {
    $prevLectureId = $allLectures[$currentPos - 1];
    $stmt = $pdo->prepare("SELECT id FROM user_progress WHERE user_id = ? AND lecture_id = ?");
    $stmt->execute([$userId, $prevLectureId]);
    if (!$stmt->fetch()) {
        die("Access Denied: Previous lecture not completed.");
    }
}

// 4. Fetch MCQs
$stmt = $pdo->prepare("SELECT id, question, option_a, option_b, option_c, option_d FROM mcqs WHERE lecture_id = ?");
$stmt->execute([$lectureId]);
$mcqs = $stmt->fetchAll();

// Check if already completed?
$stmt = $pdo->prepare("SELECT id FROM user_progress WHERE user_id = ? AND lecture_id = ?");
$stmt->execute([$userId, $lectureId]);
$isCompleted = (bool)$stmt->fetch();

?>
<!DOCTYPE html>
<html>
<head>
    <!-- Redirecting logic to use the includes -->
</head>
<body class="bg-background text-foreground">
<?php require_once 'includes/header.php'; ?>

    <div class="bg-secondary/30 py-6 border-b border-border">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-2 text-sm text-muted-foreground mb-2">
                <a href="dashboard.php" class="hover:text-primary">Dashboard</a>
                <i data-lucide="chevron-right" class="h-3 w-3"></i>
                <a href="course.php?id=<?= $courseId ?>" class="hover:text-primary">Course</a>
                <i data-lucide="chevron-right" class="h-3 w-3"></i>
                <span>Lecture <?= ($currentPos + 1) ?></span>
            </div>
            <h1 class="text-2xl font-bold"><?= htmlspecialchars($lecture['title']) ?></h1>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content (Video + Text) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Video Container -->
                 <?php
                function getYoutubeEmbedUrl($url) {
                    $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
                    $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';
    
                    if (preg_match($longUrlRegex, $url, $matches)) {
                        $id = $matches[3];
                        return "https://www.youtube.com/embed/" . $id;
                    }
    
                    if (preg_match($shortUrlRegex, $url, $matches)) {
                        $id = $matches[1];
                        return "https://www.youtube.com/embed/" . $id;
                    }
                    return null;
                }
                ?>
                <div class="bg-black rounded-lg overflow-hidden shadow-lg aspect-video relative">
                     <iframe class="absolute inset-0 w-full h-full" src="https://www.youtube.com/embed/2s6mIboARCM?si=FuaxHTWJjsoSwyZI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>

                <div class="prose dark:prose-invert max-w-none">
                     <h3 class="text-xl font-semibold mb-2">Lecture Notes</h3>
                     <div class="bg-card border border-border p-6 rounded-lg shadow-sm">
                        <?= nl2br(htmlspecialchars($lecture['content'])) ?>
                     </div>
                </div>
            </div>

            <!-- Quiz Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 bg-card border border-border rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <i data-lucide="help-circle" class="h-5 w-5 text-primary"></i>
                        Quiz
                    </h3>
                    <p class="text-xs text-muted-foreground mb-4">Pass requirements: 5/10</p>
                    
                    <?php if ($isCompleted): ?>
                        <div class="bg-green-100 text-green-800 p-4 rounded-md mb-6 text-sm flex items-start gap-2">
                            <i data-lucide="check-circle" class="h-5 w-5 flex-shrink-0"></i>
                            <div>
                                You have already completed this lecture! <br/>
                                <a href="course.php?id=<?= $courseId ?>" class="font-bold underline mt-1 inline-block">Return to Course</a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="api/submit_mcq.php" method="POST" class="space-y-6">
                        <input type="hidden" name="course_id" value="<?= $courseId ?>">
                        <input type="hidden" name="lecture_id" value="<?= $lectureId ?>">
                        
                        <?php foreach ($mcqs as $index => $mcq): ?>
                            <div class="space-y-3">
                                <p class="font-medium text-sm"><strong>Q<?= $index+1 ?>:</strong> <?= htmlspecialchars($mcq['question']) ?></p>
                                <div class="space-y-2">
                                    <label class="flex items-start gap-2 text-sm cursor-pointer hover:bg-accent p-2 rounded-md transition-colors">
                                        <input type="radio" name="answers[<?= $mcq['id'] ?>]" value="A" required class="mt-0.5"> 
                                        <span><?= htmlspecialchars($mcq['option_a']) ?></span>
                                    </label>
                                    <label class="flex items-start gap-2 text-sm cursor-pointer hover:bg-accent p-2 rounded-md transition-colors">
                                        <input type="radio" name="answers[<?= $mcq['id'] ?>]" value="B" class="mt-0.5"> 
                                        <span><?= htmlspecialchars($mcq['option_b']) ?></span>
                                    </label>
                                    <label class="flex items-start gap-2 text-sm cursor-pointer hover:bg-accent p-2 rounded-md transition-colors">
                                        <input type="radio" name="answers[<?= $mcq['id'] ?>]" value="C" class="mt-0.5"> 
                                        <span><?= htmlspecialchars($mcq['option_c']) ?></span>
                                    </label>
                                    <label class="flex items-start gap-2 text-sm cursor-pointer hover:bg-accent p-2 rounded-md transition-colors">
                                        <input type="radio" name="answers[<?= $mcq['id'] ?>]" value="D" class="mt-0.5"> 
                                        <span><?= htmlspecialchars($mcq['option_d']) ?></span>
                                    </label>
                                </div>
                            </div>
                            <div class="h-px bg-border"></div>
                        <?php endforeach; ?>

                        <button type="submit" class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                            Submit Quiz
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>
