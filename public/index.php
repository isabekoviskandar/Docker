<?php
session_start();

if (!isset($_SESSION['step'])) {
    $_SESSION['step'] = 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['question'])) {
        $userAnswer = $_POST['answer'] ?? null;

        if ($userAnswer == $_SESSION['question']['result']) {
            $message = '<div class="alert alert-success text-center fw-bold">✅ Correct! Click Next to proceed.</div>';
            $_SESSION['step']++;
            unset($_SESSION['question']);

            if ($_SESSION['step'] > 10) {
                $message = '<div class="alert alert-success text-center fw-bold">🏆 Congratulations! You answered all questions correctly.</div>';
                session_destroy();
            }
        } else {
            $message = '<div class="alert alert-danger text-center fw-bold">❌ Incorrect! Try again.</div>';
        }
    } else {
        $message = '<div class="alert alert-warning text-center fw-bold">⚠️ No question found, please refresh the page.</div>';
    }
}

if ($_SESSION['step'] < 10 && !isset($_SESSION['question'])) {
    $a = rand(0, 9);
    $b = rand(0, 9);
    $operators = ['+', '-', '*'];
    $c = $operators[array_rand($operators)];
    $_SESSION['question'] = [
        'a' => $a,
        'b' => $b,
        'operator' => $c,
        'result' => eval("return $a$c$b;")
    ];
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Math Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #ff9966, #ff5e62);
            font-family: 'Arial', sans-serif;
        }
        .quiz-container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 500px;
            margin: auto;
            text-align: center;
            margin-top: 80px;
        }
        h1, h2 {
            font-weight: bold;
            color: #333;
        }
        .btn-custom {
            background-color: #ff5e62;
            color: white;
            font-weight: bold;
            border-radius: 25px;
            padding: 10px 30px;
        }
        .btn-custom:hover {
            background-color: #e14e56;
        }
    </style>
</head>

<body>
    <div class="quiz-container">
        <h1>Step <?= $_SESSION['step'] ?> / 10</h1>
        <?php if ($_SESSION['step'] <= 10): ?>
            <h2>
                <?= $_SESSION['question']['a'] ?> <?= $_SESSION['question']['operator'] ?> <?= $_SESSION['question']['b'] ?> = ?
            </h2>
            <form method="POST">
                <input type="number" name="answer" class="form-control mt-3" style="width: 80%; margin: auto;" placeholder="Your answer" required>
                <button type="submit" class="btn btn-custom mt-4">Submit</button>
            </form>
        <?php else: ?>
            <h2>🏆 You won! All questions were answered correctly.</h2>
            <form action="" method="post">
                <button type="submit" class="btn btn-custom mt-4">Restart</button>
            </form>
        <?php endif; ?>
        <br>
        <?= $message ?? '' ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
