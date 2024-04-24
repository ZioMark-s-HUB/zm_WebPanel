<?php
require 'steamauth/steamauth.php';

if (isset($_SESSION['steamid'])) {
    header("Location: index.php"); // Redirect to Login Page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/e064ddb14c.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="assets/favicon.png">
</head>
<body class="bg-gray-900 text-white flex items-center justify-center h-screen" style="background-image: url('assets/background_image.png'); background-size: cover; background-position: center;">
<div class="max-w-md w-full bg-gray-800 p-8 rounded-lg shadow-lg">
    <div class="flex justify-center mb-4">
        <img src="assets/logo.svg" alt="Logo" class="h-20">
    </div>
    <div>
        <h2 class="text-3xl font-semibold mb-10 text-center">Login with Steam</h2>
        <?php if(!isset($_SESSION['steamid'])) : ?>
            <div class="text-center"><?php loginbutton(); // Display login button ?></div>
        <?php else : ?>
            <p class="text-red-500 text-center">You are already logged in.</p>
        <?php endif; ?>
    </div>
</div>
</body>


</html>

