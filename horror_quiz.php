<?php
include 'header.php';

function calculateScore($userAnswers, $correctAnswers) {
    $score = 0;
    for ($i = 0; $i < count($correctAnswers); $i++) {
        if (isset($userAnswers[$i]) && strtolower(trim($userAnswers[$i])) === strtolower($correctAnswers[$i])) {
            $score++;
        }
    }
    return $score;
}

function getRank($score, $total) {
    $percent = ($score / $total) * 100;
    if ($percent >= 90) return "👑 Eldritch Horror Master";
    if ($percent >= 70) return "🩸 Fearless Fiend";
    if ($percent >= 50) return "👻 Cautious Creeper";
    return "😱 Screaming Novice";
}

$questions = [
    "Who directed the movie *Psycho* (1960)?",
    "What is the name of the killer in the *Friday the 13th* franchise?",
    "In which horror film do we hear the line 'Do you like scary movies?'",
    "Which 2017 horror film features a clown named Pennywise?",
    "What’s the name of the haunted hotel in *The Shining*?",
    "Which horror movie features a puzzle box that summons Cenobites?",
    "What horror film involves a videotape that kills you in 7 days?",
    "What is the name of the possessed doll in *The Conjuring* universe?",
    "Who plays the role of Freddy Krueger in the original *Nightmare on Elm Street*?",
    "Which horror film's antagonist is named Jigsaw?"
];

$options = [
    ["Alfred Hitchcock", "Stanley Kubrick", "John Carpenter", "Wes Craven"],
    ["Jason Voorhees", "Michael Myers", "Freddy Krueger", "Ghostface"],
    ["Scream", "The Ring", "Saw", "The Conjuring"],
    ["It", "The Babadook", "Hereditary", "Us"],
    ["Overlook Hotel", "Bates Motel", "Hill House", "Derry Inn"],
    ["Hellraiser", "The Mist", "The Grudge", "The Void"],
    ["The Ring", "Insidious", "Host", "Paranormal Activity"],
    ["Annabelle", "Chucky", "Megan", "Valak"],
    ["Robert Englund", "Kevin Bacon", "Bruce Campbell", "Tony Todd"],
    ["Saw", "Split", "Sinister", "Final Destination"]
];

$correctAnswers = [
    "Alfred Hitchcock",
    "Jason Voorhees",
    "Scream",
    "It",
    "Overlook Hotel",
    "Hellraiser",
    "The Ring",
    "Annabelle",
    "Robert Englund",
    "Saw"
];

$totalQuestions = count($questions);
$userScore = null;
$rank = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userAnswers = $_POST["answers"] ?? [];
    $userScore = calculateScore($userAnswers, $correctAnswers);
    $rank = getRank($userScore, $totalQuestions);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Horror Trivia Quiz - Spookies</title>
    <link rel="stylesheet" href="MainPage.css">
    <style>
        body {
            background-color: #0a0a0a;
            color: #f8f8f8;
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 0;
        }

        .quiz-container {
            max-width: 800px;
            margin: 40px auto;
            background: linear-gradient(145deg, #1a1a1a, #111);
            border: 2px solid crimson;
            border-radius: 16px;
            padding: 30px 40px;
            box-shadow: 0 0 15px rgba(220, 20, 60, 0.5);
        }

        h2 {
            text-align: center;
            color: crimson;
            font-size: 32px;
            margin-bottom: 30px;
        }

        .question {
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 1px solid #333;
        }

        .question p {
            font-size: 18px;
            margin-bottom: 12px;
        }

        .question label {
            display: block;
            margin-bottom: 8px;
            padding-left: 5px;
            transition: all 0.2s ease;
        }

        .question input[type="radio"] {
            margin-right: 10px;
            accent-color: crimson;
        }

        .question label:hover {
            color: crimson;
        }

        button {
            background-color: crimson;
            color: #fff;
            border: none;
            padding: 12px 24px;
            font-size: 18px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .result {
            text-align: center;
            padding: 25px;
            background-color: #181818;
            border-left: 6px solid crimson;
            margin-top: 30px;
            border-radius: 10px;
        }

        .result h3 {
            font-size: 24px;
            color: limegreen;
            margin-bottom: 10px;
        }

        .result p {
            font-size: 18px;
        }

        a {
            color: crimson;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
        /* Override global styles with more specific quiz-only class names */
        .quiz-container form {
            display: block; /* Cancel global flex layout */
            margin-bottom: 0;
        }

        .quiz-container .question label {
            display: block;
            margin-left: 0;
        }

        .quiz-container input[type="radio"] {
            margin-right: 8px;
        }

        .quiz-container button.quiz-submit {
            background-color: crimson;
            border: none;
            padding: 10px 20px;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 20px auto 0;
            transition: background-color 0.3s;
        }

        .quiz-container button.quiz-submit:hover {
            background-color: darkred;
        }

        .quiz-container a {
            color: crimson;
            text-decoration: none;
            font-weight: bold;
        }

    </style>
</head>
<body>
<div class="quiz-container">
    <h2>🎃 Horror Trivia Quiz</h2>

    <?php if ($userScore === null): ?>
        <form method="post">
            <?php foreach ($questions as $index => $q): ?>
                <div class="question">
                    <p><strong><?= ($index + 1) ?>. <?= $q ?></strong></p>
                    <?php
                        $shuffledOptions = $options[$index];
                        shuffle($shuffledOptions);
                        foreach ($shuffledOptions as $opt): 
                    ?>
                        <label>
                            <input type="radio" name="answers[<?= $index ?>]" value="<?= htmlspecialchars($opt) ?>" required>
                            <?= htmlspecialchars($opt) ?>
                        </label>
                    <?php endforeach; ?>

                </div>
            <?php endforeach; ?>
            <button type="submit" class="quiz-submit">Submit Answers</button>
        </form>
    <?php else: ?>
        <div class="result">
            <h3>You scored <?= $userScore ?>/<?= $totalQuestions ?></h3>
            <p>Rank: <strong><?= $rank ?></strong></p>
        </div>
        <p style="text-align:center;"><a href="horror_quiz.php" class="quiz-retry">🔁 Try Again</a></p>
    <?php endif; ?>
</div>

<script src="logo.js"></script>


<?php include 'footer.php'; ?>
</body>
</html>
