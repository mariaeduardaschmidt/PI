<?php
include 'conexao.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donor_name = $_POST['donor_name'];
    $donor_email = $_POST['donor_email'];
    $product_type = $_POST['product_type'];
    $quantity = (int) $_POST['quantity'];
    $description = $_POST['description'];

    $sql = "INSERT INTO doacoes (nome_doador, email_doador, tipo_produto, quantidade, descricao_produto) 
            VALUES (:donor_name, :donor_email, :product_type, :quantity, :description)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':donor_name', $donor_name);
    $stmt->bindParam(':donor_email', $donor_email);
    $stmt->bindParam(':product_type', $product_type);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':description', $description);

    try {
        $stmt->execute();
        $message = "Doação registrada com sucesso!";
    } catch (PDOException $e) {
        $message = "Erro ao registrar doação: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Doação</title>
    <link rel="stylesheet" href="style.css">    
</head>
<body>
  <header>
    <div class="logo">
      <a href="index.php"><img src="logo.png" alt="Início"></a>
    </div>
    <nav>
      <ul>
        <li><a href="index1.php">Cadastrar Doação</a></li>
        <li><a href="index2.php">Relatórios</a></li>
        <li><a href="index3.php">Perfil</a></li>
        <li><a href="index4.php">Login</a></li>
      </ul>
    </nav>
  </header>

  <div class="container">
    <h2>Cadastro de Doação</h2>
    
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <input type="text" name="donor_name" placeholder="Nome do Doador" required>
        <input type="email" name="donor_email" placeholder="E-mail do Doador" required>
        <input type="text" name="product_type" placeholder="Tipo de Produto" required>
        <input type="number" name="quantity" placeholder="Quantidade" required min="1">
        <textarea name="description" placeholder="Descrição do Produto" rows="4" required></textarea>
        <button type="submit">Registrar Doação</button>
    </form>
  </div>

  <footer>
    <p>Sistema de Doações FMP.</p>
    <p>Alunos: Issaga Seco Injai, Maria Eduarda Schmidt e Raissa Vieira.</p>
    <p>Implementado em xx/xx/xxxx.</p>
  </footer>

</body>
</html>
