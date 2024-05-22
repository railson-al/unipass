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
      </div>
      <input type="submit" value="Adicionar Senha">
    </form>
    
    <?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

      $insertSQL = "INSERT INTO passwords (description, category, password) VALUES ('$newDescription', '$newCategory', '$newPassword')";

      $result = $connection->query($insertSQL);

      if (!$result) {
        die("Invalid query: ". $connection->error);
      }

      header('Location: ../../../index.php');
      exit;
    }
    ?>
  </main>
</body>

</html>
