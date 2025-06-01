<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Submit a Lost Tape - Spookies</title>
    <link rel="stylesheet" href="MainPage.css">
    <link rel="stylesheet" href="losttape.css?v=2" />
</head>
<body>
        <?php include 'header.php'; ?>

    <div class="container">
        <h2>Submit a Lost Tape 🎃</h2>
        <form id="lostTapeForm" action="https://formsubmit.co/Gilshimoni29@gmail.com" method="POST" enctype="multipart/form-data">

            <label for="title">Codename of the Tape</label>
            <input type="text" id="title" name="title" required>

            <label for="location">Where Was It Found?</label>
            <input list="locations" id="location" name="location">
            <datalist id="locations">
                <option value="Abandoned House">
                <option value="Dark Forest">
                <option value="Basement">
                <option value="Cemetery">
                <option value="School Locker">
            </datalist>

            <label for="tape-type">Tape Type</label>
            <select id="tape-type" name="tape-type">
                <option>Video</option>
                <option>Audio</option>
                <option>Photograph</option>
                <option>Unknown Format</option>
            </select>

            <label for="password">Insider Code (if any)</label>
            <input type="password" id="password" name="password">

            <label for="email">Submitter's Email</label>
            <input type="email" id="email" name="email" required>

            <label for="url">External Tape Link</label>
            <input type="url" id="url" name="url">

            <label for="phone">Callback Number</label>
            <input type="tel" id="phone" name="phone">

            <label for="age">Estimated Age of the Tape (years)</label>
            <input type="number" id="age" name="age" min="1" max="150">

            <label for="danger">Danger Level</label>
            <input type="range" id="danger" name="danger" min="0" max="10">

            <label for="aura">Aura Color Seen on Tape</label>
            <input type="color" id="aura" name="aura">

            <label for="found-date">Date Found</label>
            <input type="date" id="found-date" name="found-date">

            <label for="found-time">Time of Discovery</label>
            <input type="time" id="found-time" name="found-time">

            <label for="recorded">When the Tape Was Recorded</label>
            <input type="datetime-local" id="recorded" name="recorded">

            <label for="story">Description / Story Behind the Tape</label>
            <textarea id="story" name="story" rows="6"></textarea>

            <label for="file">Attach the Artifact</label>
            <input type="file" id="file" name="file">

            <button type="submit">Submit for Investigation</button>
        </form>
    </div>

    <?php include 'footer.php'; ?> 

    <script>
        

        // Wait for DOM to be fully loaded
        window.addEventListener("DOMContentLoaded", function () {
            const codename = window.prompt("Codename this cursed tape before you proceed:", "Untitled Entity");
            if (codename && codename.trim() !== "") {
                document.getElementById("title").value = codename.trim();
            }

            const form = document.getElementById("lostTapeForm");
            form.addEventListener("submit", function(event) {
                window.alert("Your Lost Tape has been submitted for further investigation. Beware of any callbacks...");
            });
        });
    </script>
    <script src="logo.js"></script>

</body>
</html>
