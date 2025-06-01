<?php include 'header.php'; ?>

<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spookies - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="MainPage.css">
    <style>
    .hero {
        background-size: cover;
        background-position: center;
        transition: background-image 0.5s ease-in-out;
        min-height: 500px; /* Add this */
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        text-align: center;
        padding: 50px 20px;
    }
</style>

</head>
<body>
 

        
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Spookies</h1>
            <p>Your ultimate horror movie guide</p>
            <form id="searchForm" action="search.php" method="get" autocomplete="off" style="position: relative; max-width: 400px; margin: 0 auto;">
            <input type="text" id="searchInput" name="q" placeholder="Search for a spooky title..." style="width: 60%;">
            <input type="number" id="yearInput" name="year" placeholder="Release Year" min="1900" max="2100" style="width: 30%; margin-left: 5px;">
            <button type="submit">Search</button>
        </form>

            <p id="searchOutput"></p>
        </div>
    </section>

   

    <script>
    const form = document.getElementById('searchForm');
    const input = document.getElementById('searchInput');
    const suggestionsBox = document.getElementById('suggestions');

    window.alert("Welcome to Spookies... Enter if you dare!");

    // Handle form submission   
    form.addEventListener('submit', function (event) {
    event.preventDefault();
    const query = input.value.trim();
    const year = document.getElementById('yearInput').value.trim();
    if (query) {
        let url = `search.php?q=${encodeURIComponent(query)}`;
        if (year) {
            url += `&year=${encodeURIComponent(year)}`;
        }
        window.location.href = url;
    }
});

     // Background image logic
        const backgrounds = [
            "images/Main Page Pictures/img1.jpg",
            "images/Main Page Pictures/img2.jpg",
            "images/Main Page Pictures/img3.jpg",
            "images/Main Page Pictures/img4.jpg",
            "images/Main Page Pictures/img5.jpg",
            "images/Main Page Pictures/img6.jpg",
            "images/Main Page Pictures/img7.jpg"
        ];
        const randomBg = backgrounds[Math.floor(Math.random() * backgrounds.length)];
        document.querySelector(".hero").style.backgroundImage = `url('${randomBg}')`;
</script>
    
<script src="logo.js"></script>


<?php include 'footer.php'; ?>


</body>
</html>
