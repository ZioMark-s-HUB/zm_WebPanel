<?php
require '../steamauth/steamauth.php';
if (!isset($_SESSION['steamid'])) {
    header("Location: ../login.php"); // Redirect to Login Page if not logged in
    exit();
}

require_once '../vendor/autoload.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No API Key</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white flex items-center justify-center h-screen">
    <div class="max-w-md w-full bg-red-500 p-8 rounded-lg shadow-lg">
        <p class="font-bold text-center mb-4">SteamAuth:</p>
        <p class="text-center mb-4">Please supply an API-Key!</p>
        <p class="text-center mb-4">Find this in <span class="font-bold">steamauth/SteamConfig.php</span>, Find the '<span class="font-bold">\<?php echo "\$steamauth['apikey']"; ?></span>' Array.</p>
        <div class="flex justify-center">
            <?php loginbutton();?>
        </div>
    </div>
</body>
</html>
