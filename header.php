<?php session_start(); ?>
<header>
    <div class="logo-container">
        <a href="index.php">
            <img src="" alt="Spookies Logo" class="logo">
        </a>
    </div>

    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="top_rated.php">Top Rated</a></li>
            <li><a href="Lost_Tape_Submission.php">Submit a Tape</a></li>
            <li><a href="darkdiary.php">Dark Diaries</a></li> 
            <li><a href="horror_quiz.php">Quiz</a></li> 
            <li><a href="upcoming_movies.php">Upcoming</a></li>
            <li><a href="browse.php">Browse</a></li>

            <?php if (isset($_SESSION['user_name'])): ?>
                <li class="dropdown">
                    <a href="#" class="dropbtn">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></a>
                    <ul class="dropdown-content">
                        <li><a href="my_ratings.php">🎃 My Ratings</a></li>
                        <li><a href="watchlist.php">🕸️ Watchlist</a></li>
                        <li><a href="logout.php">🚪 Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="signin.php">Sign in</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Search bar -->
    <form action="search.php" method="GET" class="header-search-form">
        <input type="text" name="q" placeholder="Search movies..." required>
        <button type="submit">🔍</button>
    </form>
</header>
