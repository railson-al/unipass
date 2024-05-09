<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Unipass</title>
  
  <link rel="stylesheet" href="./styles.css">
</head>

<body>
  <header class="header">
    <h1>Unipass</h1>
    <img src="./src/assets/svg/lock.svg" alt="cadeado" />
  </header>
  <main>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Descrição</th>
          <th>Categoria</th>
          <th>Senha</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $servername = "localhost";
          $username = "root";
          $password = "";
          $database = "unipass";

          $connection = new mysqli($servername, $username, $password, $database);

          if ($connection->connect_error) {
            die("Connection failed: ". $connection->connect_error);
          }

          $sql = "SELECT * FROM passwords";
          $result = $connection->query($sql);

          if (!$result) {
            die("Invalid query: ". $connection->error);
          }

          while($row = $result->fetch_assoc()) {
            echo "
            <tr>
              <td>$row[id]</td>
              <td>$row[description]</td>
              <td>$row[category]</td>
              <td>
                <div class='wrapperPassword'>
                  <p>
                    $row[password]
                  </p>
                  <a href='./newPassword.php'>
                    <img src='./src/assets/svg/copy.svg' alt='ver senha' />
                  </a>
                </div>
              </td>
              <td>
                <div class='wrapperActions'>
                  <a href='./editPassword.php'>
                    <img src='./src/assets/svg/edit.svg' alt='editar' />
                  </a>
                  <button>
                    <img src='./src/assets/svg/trash.svg' alt='deletar' />
                  </button>
                </div>
              </td>
            </tr>
            ";
          }
        ?>
      </tbody>
    </table>
  </main>
</body>

</html>