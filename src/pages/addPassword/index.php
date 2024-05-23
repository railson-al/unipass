<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="./styles.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Unipass - Adicionar senha</title>  
</head>

<body>

  <header class="header">
    <h1>Unipass</h1>
    <img src="../../assets/svg/lock.svg" alt="cadeado" />
  </header>
  <main>
    <header><h2>Adicionar nova senha</h2></header>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class='inputWrapper'>
        <label for="newDescription">Descrição</label>
        <input type="text" name="newDescription" id="newDescription">
      </div>
      <div class='inputWrapper'>
        <label for="newCategory">Categoria</label>
        <input type="text" name="newCategory" id="newCategory">
      </div>
      <div class='inputWrapper'>
        <label for="newPassword">Senha</label>
        <input type="password" name="newPassword" id="newPassword" autocomplete="off">
        <button type="button" onclick="generateStrongPassword()" class="button generate-button">Gerar Senha Forte</button>
      </div>
      <input type="submit" value="Adicionar Senha">
    </form>
    <button onclick="window.location.href = '../../../index.php';" class="button cancel-button">Cancelar</button>
    
    <?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      require '../../../security.php';

      $newDescription = $_POST['newDescription'];
      $newCategory = $_POST['newCategory'];
      $newPassword = $_POST['newPassword'];

      $servername = "localhost";
      $username = "root";
      $password = "";
      $database = "unipass";

      $connection = new mysqli($servername, $username, $password, $database);

      if ($connection->connect_error) {
        die("Connection failed: ". $connection->connect_error);
      }

      #Criptografia SSL da senha
      $hash_password = encryptPassword($newPassword);

      $insertSQL = "INSERT INTO passwords (description, category, password) VALUES ('$newDescription', '$newCategory', '$hash_password')";

      $result = $connection->query($insertSQL);

      if (!$result) {
        die("Invalid query: ". $connection->error);
      }

      header('Location: ../../../index.php');
      exit;
    }
    ?>
  </main>

  <script>
        function generateStrongPassword() {
            var length = 16; // comprimento da senha
            var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=";
            var password = "";
            for (var i = 0, n = charset.length; i < length; ++i) {
                password += charset.charAt(Math.floor(Math.random() * n));
            }
            document.getElementById('newPassword').value = password;
        }
    </script>
</body>

</html>
