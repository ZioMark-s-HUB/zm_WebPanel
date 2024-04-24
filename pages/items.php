<?php
require '../steamauth/steamauth.php';
if (!isset($_SESSION['steamid'])) {
    header("Location: ../login.php"); // Redirect to Login Page if not logged in
    exit();
}

require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('C:/Users/Administrator/Desktop/'); //directory where your .env is!
$dotenv->load();

$dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'];
try {
    $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT item, label, `limit`, usable, groupId, `desc`, weight, can_remove, id FROM items");

    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VORP | Items</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/e064ddb14c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="../assets/favicon.png">
  </head>
  <body class="bg-gray-900 text-white">
    <nav class="bg-gray-800 p-4">
      <div class="container mx-auto flex justify-between items-center">
        <div class="flex items-center">
          <a href="index.php">
            <img src="../assets/logo.svg" alt="Logo" class="h-8 mr-2">
          </a>
        </div>
        <div class="flex-grow mx-auto">
          <a href="../index.php" class="navitem text-white font-semibold text-lg mx-4">Home</a>
          <a href="characters.php" class="navitem text-white font-semibold text-lg mx-4">Characters</a>
          <a href="#" class="navitem text-slate-500 font-semibold text-lg mx-4">Items</a>
        </div>
        <div class="flex items-center">
        <?php if(!isset($_SESSION['steamid'])) : ?>
    <?php 
        header("Location: login.php"); //Redirect to Login Page if not logged in
        exit();
    ?>
<?php else : ?>
    <?php include ('../steamauth/userInfo.php'); ?>
    <div class="flex items-center">
        <a href="#" class="navitem text-white font-semibold text-lg mx-4">Hello, <?= $steamprofile['personaname'] ?></a>
        <img src="<?= $steamprofile['avatar'] ?>" alt="Avatar" class="w-8 h-8 rounded-full ml-2 mr-5">
        <?php logoutbutton(); ?>
    </div>
<?php endif; ?>

        </div>
    </div>
</nav>
    <div class="container mx-auto p-4">
      <section class="mb-8 header-section p-8 rounded-lg shadow-lg flex flex-col justify-center items-center" style="background-image: url('../assets/background_image_items.png'); height: 100%;">
        <h1 class="text-3xl font-semibold mb-4 text-center">Item List</h1>
        <p class="text-lg text-center">This panel displays information about items from your database.</p>
      </section>
      <table class="table-auto w-full border-collapse border border-slate-500">
        <thead>
          <tr>
            <th class="bg-slate-800 border border-slate-600 px-4 py-2 text-center">ID</th>
            <th class="bg-slate-800 border border-slate-600 px-4 py-2 text-center">Item</th>
            <th class="bg-slate-800 border border-slate-600 px-4 py-2 text-center">Label</th>
            <th class="bg-slate-800 border border-slate-600 px-4 py-2 text-center">Limit</th>
            <th class="bg-slate-800 border border-slate-600 px-4 py-2 text-center">Usable</th>
            <th class="bg-slate-800 border border-slate-600 px-4 py-2 text-center">Edit</th>
          </tr>
        </thead>
        <tbody> <?php if (!empty($items)) : ?> <?php foreach ($items as $item) : ?> <tr>
            <td class="border border-slate-700 px-4 py-2 text-center"> <?= htmlspecialchars($item['id']) ?> </td>
            <td class="border border-slate-700 px-4 py-2 text-center"> <?= htmlspecialchars($item['item']) ?> </td>
            <td class="border border-slate-700 px-4 py-2 text-center"> <?= htmlspecialchars($item['label']) ?> </td>
            <td class="border border-slate-700 px-4 py-2 text-center"> <?= htmlspecialchars($item['limit']) ?> </td>
            <td class="border border-slate-700 px-4 py-2 text-center"> <?= $item['usable'] ? 'Yes' : 'No' ?> </td>
            <td class="border border-slate-700 px-4 py-2 text-center">
              <button onclick="showEditModal(
													<?= htmlspecialchars(json_encode($item)) ?>)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-edit"></i>
              </button>
            </td>
          </tr> <?php endforeach; ?> <?php else : ?> <tr>
            <td colspan="5" class="px-4 py-2 text-center">No items found.</td>
          </tr> <?php endif; ?> </tbody>
      </table>
    </div>
    <div id="editModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
      <div class="bg-gray-900 p-6 rounded-lg shadow-lg max-w-md w-full">
        <div class="text-white">
          <h2 class="text-lg font-semibold mb-4">Edit Item</h2>
          <form id="editForm" class="grid grid-cols-2 gap-4">
            <input type="hidden" id="itemId" name="itemId">
            <div class="col-span-2">
              <label for="label">Label:</label>
              <input type="text" id="label" name="label" class="bg-gray-800 rounded-lg px-4 py-2 w-full text-white">
            </div>
            <div>
              <label for="limit">Limit:</label>
              <input type="number" id="limit" name="limit" class="bg-gray-800 rounded-lg px-4 py-2 w-full text-white">
            </div>
            <div>
              <label for="usable">Usable:</label>
              <select id="usable" name="usable" class="bg-gray-800 rounded-lg px-4 py-2 w-full text-white">
                <option value="1">Yes</option>
                <option value="0">No</option>
              </select>
            </div>
            <div>
              <label for="groupId">Group ID:</label>
              <input type="text" id="groupId" name="groupId" class="bg-gray-800 rounded-lg px-4 py-2 w-full text-white">
            </div>
            <div>
              <label for="desc">Description:</label>
              <textarea id="desc" name="desc" class="bg-gray-800 rounded-lg px-4 py-2 w-full text-white"></textarea>
            </div>
            <div>
              <label for="weight">Weight (x,xx):</label>
              <input type="text" id="weight" name="weight" class="bg-gray-800 rounded-lg px-4 py-2 w-full text-white">
            </div>
            <div class="flex items-center ps-4 border border-gray-200 rounded dark:border-gray-700">
              <input id="can_remove" type="checkbox" name="can_remove" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
              <label for="can_remove" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Can Remove</label>
            </div>
            <div class="col-span-2">
              <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-save"></i> Save </button>
              <button type="button" onclick="closeEditModal()" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-times"></i> Cancel </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script>
      function showEditModal(item) {
        const modal = document.getElementById('editModal');
        modal.classList.remove('hidden');
        document.getElementById('itemId').value = item.item;
        document.getElementById('label').value = item.label;
        document.getElementById('limit').value = item.limit;
        document.getElementById('usable').value = item.usable;
        document.getElementById('groupId').value = item.groupId;
        document.getElementById('desc').value = item.desc;
        document.getElementById('weight').value = item.weight;
        document.getElementById('can_remove').checked = item.can_remove;
      }

      function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
      }
      document.getElementById('editForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/update_item.php', {
          method: 'POST',
          body: formData
        }).then(response => {
          if (response.ok) {
            closeEditModal();
          } else {
            response.json().then(data => {
              console.error('Failed to update item:', data.message);
            });
          }
        }).catch(error => {
          console.error('Error:', error);
        });
      });
    </script>
    <script>
      function sortTable(column) {
        var table, rows, switching, i, x, y, shouldSwitch;
        table = document.getElementById("itemsTable");
        switching = true;
        while (switching) {
          switching = false;
          rows = table.getElementsByTagName("tr");
          for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].querySelector('[data-id="' + column + '"]');
            y = rows[i + 1].querySelector('[data-id="' + column + '"]');
            if (parseInt(x.innerHTML) > parseInt(y.innerHTML)) {
              shouldSwitch = true;
              break;
            }
          }
          if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
          }
        }
      }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
  </body>
</html>