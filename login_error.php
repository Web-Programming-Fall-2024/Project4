<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In Error</title>
    <link rel="stylesheet" href="css/style3.css">
</head>

<body>
    <div class="error-container">
        <h2>Log In Error</h2>
        <p><?= isset($_SESSION['error']) ? htmlspecialchars($_SESSION['error']) : "An unknown error occurred."; ?></p>
        <a href="./session_destroy.php" class="btn">Return to Main Page</a>
    </div>
</body>

</html>
