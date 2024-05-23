<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="./styles.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Unipass - Editar senha</title>  
</head>

<body>

  <header class="header">
    <h1>Unipass</h1>
    <img src="../../assets/svg/lock.svg" alt="cadeado" />
  </header>
  <main>
    <?php 
    if(isset($_GET['id'])) {

        require '../../../security.php';

        // Recupera o ID da URL
        $id = $_GET['id'];
        
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "unipass";

        $connection = new mysqli($servername, $username, $password, $database);

        // Verifica conexão
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        // Verifica se o formulário foi submetido
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Recupera os novos dados do formulário
            $newDescription = $_POST['newDescription'];
            $newCategory = $_POST['newCategory'];
            $newPassword = $_POST['newPassword'];

            // Atualiza os dados no banco de dados
            $hash_password = encryptPassword($newPassword);
            $updateQuery = "UPDATE passwords SET description='$newDescription', category='$newCategory', password='$hash_password' WHERE id=$id";
            if ($connection->query($updateQuery) === TRUE) {
                // Redireciona para a página inicial
                header('Location: ../../../index.php?message=Senha%20atualizada%20com%20sucesso!');
                exit;
            } else {
                echo "Erro ao atualizar a senha: " . $connection->error;
            }
        }

        // Consulta SQL para obter os detalhes da senha pelo ID
        $checkIDQuery = "SELECT * FROM passwords WHERE id = $id";
        $result = $connection->query($checkIDQuery);

        if ($result->num_rows > 0) {
            // Loop pelos resultados
            while ($row = $result->fetch_assoc()) {

                $decrypted_pass = decryptPassword($row['password'])
                // Preenche os campos do formulário com os detalhes da senha
                ?>
                <header><h2>Editar senha</h2></header>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $id; ?>">
                    <div class='inputWrapper'>
                        <label for='newDescription'>Descrição</label>
                        <input type='text' name='newDescription' id='newDescription' value="<?php echo $row['description']; ?>">
                    </div>
                    <div class='inputWrapper'>
                        <label for='newCategory'>Categoria</label>
                        <input type='text' name='newCategory' id='newCategory' value="<?php echo $row['category']; ?>">
                    </div>
                    <div class='inputWrapper'>
                        <label for='newPassword'>Senha</label>
                        <input type='password' name='newPassword' id='newPassword' autocomplete='off' value="<?php echo $decrypted_pass; ?>">
                        <button type="button" onclick="generateStrongPassword()" class="button generate-button">Gerar Senha Forte</button>
                    </div>
                    <input type='submit' value='Salvar Alterações'>
                </form>
                <button onclick="window.location.href = '../../../index.php';" class="button cancel-button">Cancelar</button>
                <?php
            }
        } else {
            echo "ID não encontrado.";
        }

        $connection->close();
    } else {
        // Redirecionar se o ID não estiver presente na URL
        header('Location:../../../index.php');
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
