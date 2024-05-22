<?php 
if(isset($_GET['id'])) {
    // Recupera o ID da URL
    $id = $_GET['id'];
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "unipass";

    // Cria conexão
    $connection = new mysqli($servername, $username, $password, $database);

    // Verifica conexão
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $checkIDQuery = "SELECT id FROM passwords WHERE id = $id";
    $result = $connection->query($checkIDQuery);

    if ($result->num_rows > 0 ) {
        $deleteSQL = "DELETE FROM passwords WHERE id = $id";
  
      if ($connection->query($deleteSQL) === TRUE) {
            header('Location: ../../../index.php?message=Senha%20deletada%20com%20sucesso!');
      } else {
          header('Location:../../../index.php?message=Erro%20ao%20deletar%20a%20senha,%20tente%20novamente%20mais%20tarde!');
      }
    } else {
        echo "<script>alert('O ID especificado não existe!');</script>";
        header('Location: ../../../index.php');
        exit; // Encerra o script
    }
    $connection->close();
} else {
    // Se o ID não estiver presente na URL, redirecione sem mensagem
    header('Location:../../../index.php');
}
?>
