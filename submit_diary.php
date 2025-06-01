<?php
include 'db.php';

$alias = $_POST['alias'] ?? 'Anonymous';
$mood = $_POST['mood'] ?? '';
$entry = $_POST['entry'] ?? '';

if (!empty($mood) && !empty($entry)) {
    $stmt = $conn->prepare("INSERT INTO dark_diary_entries (alias, mood, entry, date_submitted) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $alias, $mood, $entry);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: darkdiary.php?success=1");
    exit();
} else {
    header("Location: darkdiary.php?error=1");
    exit();
}
?>
