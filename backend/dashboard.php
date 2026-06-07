<?php
// backend/dashboard.php
require_once 'db.php';
require_once 'auth/session.php';
requireLogin();

$userId = getCurrentUserId();
$stmt = $pdo->query("SELECT * FROM courses");
$courses = $stmt->fetchAll();

// Get purchased courses
$stmt = $pdo->prepare("SELECT course_id FROM user_courses WHERE user_id = ?");
$stmt->execute([$userId]);
$purchasedIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Get completed courses (certificates)
$stmt = $pdo->prepare("SELECT course_id FROM certificates WHERE user_id = ?");
$stmt->execute([$userId]);
$completedIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Calc progress for visualization (optional simple count)
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Redirecting logic to use the includes -->
</head>
<body>
<?php
// We don't need the HTML boilerplate here anymore as it's in header.php
require_once 'includes/header.php';
?>

    <div class="bg-secondary py-8">
        <div class="container mx-auto px-4">
          <h1 class="text-3xl font-bold text-secondary-foreground">My Learning</h1>
          <p class="text-secondary-foreground/80 mt-1">
            Track your progress and continue learning
          </p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-semibold mb-6">Available Courses</h2>
        
        <div class="space-y-4">
            <?php foreach ($courses as $course): ?>
                <?php 
                    $isOwned = in_array($course['id'], $purchasedIds); 
                    $isCompleted = in_array($course['id'], $completedIds);
                ?>
                <div class="bg-card text-card-foreground rounded-lg border border-border shadow-sm overflow-hidden">
                    <div class="flex flex-col sm:flex-row">
                        <!-- Course Thumbnail -->
                        <div class="sm:w-48 h-32 sm:h-auto bg-muted relative">
                            <?php if (!empty($course['thumbnail'])): ?>
                                <img src="<?= htmlspecialchars($course['thumbnail']) ?>" alt="<?= htmlspecialchars($course['title']) ?>" class="w-full h-full object-cover absolute inset-0">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                    <i data-lucide="book" class="h-10 w-10 text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 p-4 sm:p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase mb-1">Course</p>
                                        <h3 class="font-semibold text-lg hover:text-primary transition-colors">
                                            <?= htmlspecialchars($course['title']) ?>
                                        </h3>
                                    </div>
                                    <?php if ($isCompleted): ?>
                                        <span class="inline-flex items-center rounded-full border border-transparent bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">
                                            Completed
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-muted-foreground text-sm mb-4 line-clamp-2">
                                    <?= htmlspecialchars($course['description']) ?>
                                </p>
                            </div>

                            <div class="flex items-center gap-2 mt-2">
                                <?php if ($isCompleted): ?>
                                    <a href="certificate.php?course_id=<?= $course['id'] ?>" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                                        <i data-lucide="award" class="mr-2 h-4 w-4"></i> View Certificate
                                    </a>
                                <?php elseif ($isOwned): ?>
                                    <a href="course.php?id=<?= $course['id'] ?>" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                                        <i data-lucide="play-circle" class="mr-2 h-4 w-4"></i> Continue Learning
                                    </a>
                                <?php else: ?>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-green-600">Free (Mock)</span>
                                        <a href="course.php?id=<?= $course['id'] ?>" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                                            View Course
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>
