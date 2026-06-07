<?php
require_once 'backend/db.php';

$sql = file_get_contents('sql/update_thumbnails.sql');

try {
    $pdo->exec($sql);
    echo "Thumbnails updated successfully!";
} catch (PDOException $e) {
    echo "Error updating thumbnails: " . $e->getMessage();
}
?>
