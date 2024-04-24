<?php
   require_once 'vendor/autoload.php';
   
   $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
   $dotenv->load();
   
   $dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'];
   try {
       $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
       $stmt = $pdo->prepare("SELECT identifier, steamname, charidentifier, `group`, discordid, firstname, lastname, gender, age, joblabel, jobgrade, character_desc, money, gold FROM characters");
       $stmt->execute();
       $characters = $stmt->fetchAll(PDO::FETCH_ASSOC);
   } catch (PDOException $e) {
       die("Database error: " . $e->getMessage());
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>VORP | Character Table</title>
      <script src="https://cdn.tailwindcss.com"></script>
      <script src="https://kit.fontawesome.com/e064ddb14c.js" crossorigin="anonymous"></script>
      <style>
         .description {
         max-height: 200px; 
         overflow-y: auto; 
         }
         .header-section {
         position: relative;
         overflow: hidden;
         height: 200px; 
         animation: slideBackground 30s infinite alternate; 
         }
         @keyframes slideBackground {
         0% {
         background-position: 0 0;
         }
         100% {
         background-position: 0 20%;
         }
         }
      </style>
   </head>
   <body class="bg-gray-900 text-white">
      <div class="container mx-auto p-4">
         <section class="mb-8 header-section p-8 rounded-lg shadow-lg flex flex-col justify-center items-center" style="background-image: url('background_image.png'); height: 100%;">
            <h1 class="text-3xl font-semibold mb-4 text-center">Character List</h1>
            <p class="text-lg text-center">This panel reads your characters table live, so what you see is the actual information from your players database</p>
         </section>
         <table class="table-auto w-full bg-gray-800 rounded-lg shadow-lg">
            <thead>
               <tr>
                  <th class="px-4 py-2 text-center">Identifier</th>
                  <th class="px-4 py-2 text-center">Steam Name</th>
                  <th class="px-4 py-2 text-center">Character Identifier</th>
                  <th class="px-4 py-2 text-center">Group</th>
                  <th class="px-4 py-2 text-center">Discord ID</th>
                  <th class="px-4 py-2 text-center">Details</th>
               </tr>
            </thead>
            <tbody>
               <?php if (!empty($characters)) : ?>
               <?php foreach ($characters as $char) : ?>
               <tr>
                  <td class="px-4 py-2 text-center"><?= htmlspecialchars($char['identifier']) ?></td>
                  <td class="px-4 py-2 text-center"><?= htmlspecialchars($char['steamname']) ?></td>
                  <td class="px-4 py-2 text-center"><?= htmlspecialchars($char['charidentifier']) ?></td>
                  <td class="px-4 py-2 text-center"><?= htmlspecialchars($char['group']) ?></td>
                  <td class="px-4 py-2 text-center"><?= htmlspecialchars($char['discordid']) ?></td>
                  <td class="px-4 py-2 text-center">
                     <button onclick="showModal(<?= htmlspecialchars(json_encode($char)) ?>)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                     <i class="fas fa-info-circle"></i>
                     </button>
                  </td>
               </tr>
               <?php endforeach; ?>
               <?php else : ?>
               <tr>
                  <td colspan="6" class="px-4 py-2 text-center">No characters found.</td>
               </tr>
               <?php endif; ?>
            </tbody>
         </table>
      </div>
      <div id="modal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
         <div class="bg-gray-900 p-6 rounded-lg shadow-lg max-w-md w-full">
            <div class="text-white">
               <h2 class="text-lg font-semibold mb-4" id="charName"></h2>
               <div id="charDetails" class="mb-4"></div>
               <button onclick="closeModal()" class="flex items-center justify-center mt-4 text-white text-sm px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-lg">
               <i class="fas fa-times mr-2"></i> Close
               </button>
            </div>
         </div>
      </div>
      <script>
         function showModal(character) {
             const modal = document.getElementById('modal');
             const charName = `${character.firstname} ${character.lastname}`;
             const charDetails = `
                 <div class="grid grid-cols-2 gap-2">
                     <div><i class="fas fa-venus-mars mr-2"></i> Gender:</div>
                     <div>${character.gender}</div>
                     <div><i class="fas fa-birthday-cake mr-2"></i> Age:</div>
                     <div>${character.age}</div>
                     <div><i class="fas fa-briefcase mr-2"></i> Job:</div>
                     <div>${character.joblabel} (Grade ${character.jobgrade})</div>
                     <div><i class="fas fa-file-alt mr-2"></i> Description:</div>
                     <div class="description">${character.character_desc}</div>
                     <div><i class="fas fa-money-bill-wave mr-2"></i> Money:</div>
                     <div>${character.money}</div>
                     <div><i class="fas fa-coins mr-2"></i> Gold:</div>
                     <div>${character.gold}</div>
                 </div>
             `;
             document.getElementById('charName').textContent = charName;
             document.getElementById('charDetails').innerHTML = charDetails;
             modal.classList.remove('hidden');
         }
         
         function closeModal() {
             const modal = document.getElementById('modal');
             modal.classList.add('hidden');
         }
         
      </script>
   </body>
</html>