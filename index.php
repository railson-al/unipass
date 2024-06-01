<?php
function limitText($text, $limit) {
    if (mb_strlen($text) > $limit) {
        return mb_substr($text, 0, $limit) . '...';
    }
    return $text;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="styles.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Unipass</title>
</head>

<body>
  <header class="header">
    <h1>Unipass</h1>
    <img src="./src/assets/svg/lock.svg" alt="cadeado" />
  </header>

  <main>
    <aside>
      <a class='add_button' href="./src/pages/addPassword/index.php">
        <img class='plus_img' src="./src/assets/svg/plus.svg" alt='adicionar' />
        <p>Adicionar senha</p>
      </a>
    </aside>
    <div class='main-table'>
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
            require './security.php';

            if (isset($_GET['message'])) {
              echo "<script>alert('" . htmlspecialchars($_GET['message']) . "');</script>";

              // Remove o parâmetro 'message' da URL sem recarregar a página
              echo "
              <script>
                  if (window.history.replaceState) {
                      var cleanUrl = window.location.protocol + '//' + window.location.host + window.location.pathname;
                      window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
                  }
              </script>
              ";
            }

            $servername = "localhost";
            $username = "root";
            $password = getenv("DATABASE_PASSWORDS");
            $database = "unipass";

            $connection = new mysqli($servername, $username, $password, $database);

            if ($connection->connect_error) {
              die("Connection failed: " . $connection->connect_error);
            }

            $sql = "SELECT * FROM passwords";
            $result = $connection->query($sql);

            if (!$result) {
              die("Invalid query: " . $connection->error);
            }

            while ($row = $result->fetch_assoc()) {
              // Escapar a senha para uso em JavaScript
              $password = htmlspecialchars($row['password'], ENT_QUOTES, 'UTF-8');
              // Usar addslashes para escapar a senha corretamente em JavaScript
              $decrypted_pass = decryptPassword($password);
              $passwordForJs = addslashes($decrypted_pass);
              
              // Limitar os textos a 20 caracteres
              $description = limitText($row['description'], 30);
              $category = limitText($row['category'], 20);
              $passwordLimited = str_repeat('*', min(strlen($password), 20));
              
              echo "
              <tr>
                <td>{$row['id']}</td>
                <td>{$description}</td>
                <td>{$category}</td>
                <td>
                  <div class='wrapperPassword'>
                    <p data-password='{$passwordForJs}' id='password-{$row['id']}'>
                      {$passwordLimited}
                    </p>
                    <button onclick='copyToClipboard(\"password-{$row['id']}\")'>
                      <img src='./src/assets/svg/copy.svg' alt='copiar senha' />
                    </button>
                  </div>
                </td>
                <td>
                  <div class='wrapperActions'>
                    <a href='./src/pages/editPassword/index.php?id={$row['id']}'>
                      <img src='./src/assets/svg/edit.svg' alt='editar' />
                    </a>
                    <a href='./src/pages/deletePassword/index.php?id={$row['id']}'>
                      <img src='./src/assets/svg/trash.svg' alt='deletar' />
                    </a>
                  </div>
                </td>
              </tr>
              ";
            }
            $connection->close();
          ?>
        </tbody>
      </table>
    </div>
  </main>
  <script>
    function copyToClipboard(passwordId) {
      // Obtém o elemento que contém a senha real
      const passwordElement = document.getElementById(passwordId);
      const password = passwordElement.getAttribute('data-password');

      // Cria um elemento textarea oculto
      const textarea = document.createElement('textarea');
      textarea.value = password;
      document.body.appendChild(textarea);

      // Seleciona o texto do textarea
      textarea.select();
      textarea.setSelectionRange(0, 99999); // Para dispositivos móveis

      // Executa o comando de cópia
      document.execCommand('copy');

      // Remove o textarea do documento
      document.body.removeChild(textarea);

      // Alerta para confirmar a cópia
      alert('Senha copiada');
    }
  </script>
</body>

</html>