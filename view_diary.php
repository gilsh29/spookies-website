<?php include 'header.php'; include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dark Diary - Submissions</title>
    <link rel="stylesheet" href="MainPage.css">
    <style>
        .entry {
            background-color: #111;
            border-left: 4px solid crimson;
            padding: 15px;
            margin-bottom: 20px;
            color: #eee;
        }
        .entry small {
            color: #888;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
            background-color: #111;
            color: #eee;
        }
        th, td {
            padding: 10px;
            border: 1px solid crimson;
        }
        th {
            background-color: crimson;
            color: white;
        }
        h3 {
            margin-top: 60px;
            color: crimson;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>📖 Dark Diary Submissions</h2>

    <?php
    $result = $conn->query("SELECT alias, mood, entry, date_submitted FROM dark_diary_entries ORDER BY date_submitted DESC");
    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
        <div class="entry">
            <p><strong><?= htmlspecialchars($row['alias']) ?></strong> <em>(<?= htmlspecialchars($row['mood']) ?>)</em></p>
            <p><?= nl2br(htmlspecialchars($row['entry'])) ?></p>
            <small>Submitted on <?= $row['date_submitted'] ?></small>
        </div>
    <?php
        endwhile;
    else:
        echo "<p>No diary entries yet. Be the first to share a chilling tale...</p>";
    endif;
    $conn->close();
    ?>

    <!-- SQL Table View -->
    <h3>📊 Raw Table View</h3>
    <table>
        <thead>
            <tr>
                <th>Alias</th>
                <th>Mood</th>
                <th>Entry</th>
                <th>Date Submitted</th>
            </tr>
        </thead>
        <tbody>
        <?php
        include 'db.php';
        $result = $conn->query("SELECT alias, mood, entry, date_submitted FROM dark_diary_entries ORDER BY date_submitted DESC");
        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
            <tr>
                <td><?= htmlspecialchars($row['alias']) ?></td>
                <td><?= htmlspecialchars($row['mood']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['entry'])) ?></td>
                <td><?= $row['date_submitted'] ?></td>
            </tr>
        <?php
            endwhile;
        else:
            echo "<tr><td colspan='4'>No diary entries yet.</td></tr>";
        endif;
        $conn->close();
        ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>

<script src="logo.js"></script>

</body>
</html>
