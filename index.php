<?php
require_once 'vendor/autoload.php';
require 'steamauth/steamauth.php';

$dotenv = Dotenv\Dotenv::createImmutable('C:/Users/Administrator/Desktop/'); //directory where your .env is!
$dotenv->load();

$dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'];
try {
    $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT COUNT(*) as character_count FROM characters");
    $stmt->execute();
    $characterResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $characterCount = $characterResult['character_count'];

    $stmt = $pdo->prepare("SELECT COUNT(*) as item_count FROM items");
    $stmt->execute();
    $itemResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $itemCount = $itemResult['item_count'];
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VORP Web Panel | ZioMark's HUB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/e064ddb14c.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="assets/favicon.png">
    <style>
      .header-section {
        position: relative;
        overflow: hidden;
        height: 20em !important;
        animation: slideBackground 30s infinite alternate;
      }

      @keyframes slideBackground {
        0% {
          background-position: 0 15%;
        }

        100% {
          background-position: 0 30%;
        }
      }

      .navitem:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body class="bg-gray-900 text-white">
  <nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <div class="flex items-center">
            <a href="#">
                <img src="assets/logo.svg" alt="Logo" class="h-8 mr-2">
            </a>
        </div>
        <div class="flex-grow mx-auto">
            <a href="#" class="navitem text-slate-500 font-semibold text-lg mx-4">Home</a>
            <a href="pages/characters.php" class="navitem text-white font-semibold text-lg mx-4">Characters</a>
            <a href="pages/items.php" class="navitem text-white font-semibold text-lg mx-4">Items</a>
        </div>
        <div class="flex items-center">
        <?php if(!isset($_SESSION['steamid'])) : ?>
    <?php 
        header("Location: login.php"); //Redirect to Login Page if not logged in
        exit();
    ?>
<?php else : ?>
    <?php include ('steamauth/userInfo.php'); ?>
    <div class="flex items-center">
        <a href="#" class="navitem text-white font-semibold text-lg mx-4">Hello, <?= $steamprofile['personaname'] ?></a>
        <img src="<?= $steamprofile['avatar'] ?>" alt="Avatar" class="w-8 h-8 rounded-full ml-2 mr-5">
        <?php logoutbutton(); ?>
    </div>
<?php endif; ?>

        </div>
    </div>
</nav>


    <section class="mb-8 header-section p-8 rounded-lg shadow-lg flex flex-col justify-center items-center" style="background-image: url('assets/background_home.png'); height: 100%;">
      <h1 class="text-3xl font-semibold mb-4 text-center">Welcome to VORP Web Panel v2</h1>
      <p class="text-lg text-center">Manage your RedM server db with ease</p>
    </section>
    <section class="relative overflow-hidden">
      <div class="absolute inset-0 z-0 bg-cover bg-center bg-fixed"></div>
      <div class="container mx-auto p-8 relative z-10">
        <h2 class="text-3xl font-semibold text-center mb-8">Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div class="bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-semibold mb-4">Characters Count</h3>
            <p class="text-3xl font-bold"> <?= $characterCount ?> </p>
          </div>
          <div class="bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-semibold mb-4">Items Count</h3>
            <p class="text-3xl font-bold"> <?= $itemCount ?> </p>
          </div>
        </div>
      </div>
    </section>

    <?php if(!isset($_SESSION['steamid'])) : ?>
<?php else : ?>

<?php include ('steamauth/userInfo.php'); ?>

<div class="container mx-auto p-8">
    <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
        <h3 class="text-xl font-semibold mb-4">Your Informations</h3>
        <p class="text-lg">
        <span class="font-semibold"></span> <img src="<?= $steamprofile['avatarfull'] ?>" alt="Avatar" class="w-16 h-16 rounded-full">
            <span class="font-semibold">Steam ID:</span> <?= $steamprofile['steamid'] ?><br>
            <span class="font-semibold">Personaname:</span> <?= $steamprofile['personaname'] ?><br>
            <span class="font-semibold">Profile URL:</span> <a href="<?= $steamprofile['profileurl'] ?>" class="text-blue-500"><?= $steamprofile['profileurl'] ?></a><br>
        </p>
    </div>
</div>

<?php endif; ?>



    <footer class="bg-gray-800 p-4 mt-8">
      <div class="container mx-auto text-center text-gray-400">
        <p>&copy; 2024 | ZioMark's HUB Development</p>
      </div>
    </footer>
  </body>
</html>