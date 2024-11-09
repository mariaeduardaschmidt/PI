<?php
require_once 'conexao.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot_password'])) {
    $email = $_POST['email'];

    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    
    try {
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $token = bin2hex(random_bytes(50));

            $sql = "INSERT INTO nova_senha (email, token, expires_at) 
                    VALUES (:email, :token, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            $resetLink = "http://localhost/pi2/redef_senha.php?token=" . $token;

            // Definindo o assunto e corpo do e-mail
            $subject = "Redefinição de Senha";
            $message = "Clique no link abaixo para redefinir sua senha: \n" . $resetLink;
            $headers = "From: no-reply@seusite.com";  // Enviar com um endereço válido

            // Enviando o e-mail
            if (mail($email, $subject, $message, $headers)) {
                $message = "Um link de redefinição de senha foi enviado para o seu e-mail.";
            } else {
                $message = "Erro ao enviar o e-mail.";
            }
        } else {
            $message = "E-mail não encontrado.";
        }
    } catch (PDOException $e) {
        $message = "Erro ao processar solicitação: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha</title>
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
<body>
  <div class="container">
    <h2>Recuperação de Senha</h2>
    <?php if ($message): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <input type="email" name="email" placeholder="E-mail" required>
        <button type="submit" name="forgot_password">Enviar Link para Redefinição</button>
    </form>
  </div>
</body>
</html>
