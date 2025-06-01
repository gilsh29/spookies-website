<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dark Diary Submission - Spookies</title>
    <link rel="stylesheet" href="MainPage.css">
    <style>
        #darkDiaryForm {
            display: block !important;
        }
        #darkDiaryForm label,
        #darkDiaryForm input,
        #darkDiaryForm select,
        #darkDiaryForm textarea,
        #darkDiaryForm button {
            display: block;
            width: 100% !important;
            margin-top: 10px;
            box-sizing: border-box;
        }
        #darkDiaryForm button {
            margin-top: 20px;
            width: auto;
        }
        .success-message {
        color: limegreen;
        text-align: center;
        margin-top: 20px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <h2>🕯️ Submit Your Dark Diary Entry</h2>

    <?php if (isset($_GET['success'])): ?>
        <p class="success-message">Your entry was submitted successfully.</p>
    <?php endif; ?>


    <form id="darkDiaryForm" action="submit_diary.php" method="POST">
        <label for="alias">Alias (optional)</label>
        <input type="text" id="alias" name="alias" placeholder="GhostHunter88">

        <label for="mood">Your Current Mood</label>
        <select id="mood" name="mood" required>
            <option value="Chilled">Chilled</option>
            <option value="Disturbed">Disturbed</option>
            <option value="Terrified">Terrified</option>
            <option value="Possessed">Possessed</option>
        </select>

        <label for="entry">Your Story</label>
        <textarea id="entry" name="entry" rows="6" placeholder="Describe what happened..." required></textarea>

        <button type="submit">Submit to the Diary</button>
    </form>

    <p><a href="view_diary.php">📖 Read the Dark Diary</a></p>
</div>

<?php include 'footer.php'; ?>

<script src="logo.js"></script>


</body>
</html>
