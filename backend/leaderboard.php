<?php
// backend/leaderboard.php
require_once 'db.php';
require_once 'auth/session.php';
require_once 'dsa/Leaderboard.php';

requireLogin();

// 1. Leaderboard Demo (Heap)
$lbSystem = new LeaderboardSystem();

// Fetch all users progress (simplified: count completed lectures)
// Fetch all users progress (simplified: count completed lectures)
$stmt = $pdo->query("
    SELECT u.full_name, COUNT(p.id) as score 
    FROM user_progress p 
    JOIN users u ON p.user_id = u.id 
    GROUP BY p.user_id
");
$scores = $stmt->fetchAll();

foreach ($scores as $s) {
    $name = $s['full_name'] ?: "User";
    $lbSystem->addScore($name, $s['score']);
}

$topUsers = $lbSystem->getTop(5);
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
            <h1 class="text-4xl font-bold mb-4">Leaderboard</h1>
            <p class="text-xl text-muted-foreground">Top students based on completed lectures</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-card border border-border rounded-lg shadow-sm overflow-hidden text-card-foreground">
                <div class="p-6 border-b border-border">
                    <h2 class="text-xl font-semibold flex items-center gap-2">
                         <i data-lucide="trophy" class="h-6 w-6 text-yellow-500"></i>
                         Top Students (Max-Heap)
                    </h2>
                </div>
                <div class="p-0">
                    <?php if (empty($topUsers)): ?>
                        <div class="p-8 text-center text-muted-foreground">
                            No data yet. Start learning to get on the board!
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-muted-foreground uppercase bg-muted/50">
                                    <tr>
                                        <th class="px-6 py-3 font-medium">Rank</th>
                                        <th class="px-6 py-3 font-medium">Student</th>
                                        <th class="px-6 py-3 font-medium text-right">Lectures Completed</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border">
                                    <?php foreach ($topUsers as $index => $node): ?>
                                        <tr class="bg-card hover:bg-muted/50 transition-colors">
                                            <td class="px-6 py-4 font-medium">
                                                <?php if ($index === 0): ?>
                                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100 text-yellow-700 font-bold">1</span>
                                                <?php elseif ($index === 1): ?>
                                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 text-gray-700 font-bold">2</span>
                                                <?php elseif ($index === 2): ?>
                                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-orange-100 text-orange-700 font-bold">3</span>
                                                <?php else: ?>
                                                    <span class="ml-2 text-muted-foreground font-semibold">#<?= $index + 1 ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                                                        <?= strtoupper(substr($node['data'], 0, 1)) ?>
                                                    </div>
                                                    <span class="font-medium"><?= htmlspecialchars($node['data']) ?></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary text-secondary-foreground">
                                                    <?= $node['priority'] ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>
