<?php
require_once 'conexao.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $registration_number = $_POST['registration_number'];
    $birth_date = $_POST['birth_date'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (full_name, email, registration_number, birth_date, password)
            VALUES (:full_name, :email, :registration_number, :birth_date, :password)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':registration_number', $registration_number);
    $stmt->bindParam(':birth_date', $birth_date);
    $stmt->bindParam(':password', $password);

    try {
        $stmt->execute();
        $message = "Usuário cadastrado com sucesso!";
    } catch (PDOException $e) {
        $message = "Erro ao cadastrar usuário: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <div class="logo">
      <a href="index.php"><img src="logo.png" alt="Início"></a>
    </div>
    <nav>
      <ul>
        <li><a href="index1.php">Doações</a></li>
        <li><a href="index2.php">Relatórios</a></li>
        <li><a href="index3.php">Sugestões</a></li>
        <li><a href="index4.php">Login</a></li>
        <li><a href="index5.php">Cadastrar Usuário</a></li>
      </ul>
    </nav>
  </header>

  <br><br>

  <div class="container">
      <h2>Cadastrar</h2>

      <?php if ($message): ?>
        <p class="message"><?php echo $message; ?></p>
      <?php endif; ?>

      <form action="index5.php" method="POST">
          <input type="text" name="full_name" placeholder="Nome Completo" required>
          <input type="email" name="email" placeholder="E-mail" required>
          <input type="text" name="registration_number" placeholder="Matrícula (se aluno)" optional>
          <input type="date" name="birth_date" placeholder="Data de Nascimento" required>
          <input type="password" name="password" placeholder="Senha" required>
          <button type="submit">Registrar Usuário</button>
      </form>

      <a href="inicio" class="link">Esqueceu sua senha?</a>
  </div>

  <footer>
    <p>Alunos: Issaga Seco Injai, Maria Eduarda Schmidt e Raissa Vieira.</p>
    <p>Sistema de Doações FMP.</p>
    <p>Implementado em xx/xx/xxxx.</p>
  </footer>

</body>
</html>
