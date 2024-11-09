<?php
require_once 'conexao.php';

$message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar se o token é válido
    $sql = "SELECT * FROM nova_senha WHERE token = :token AND expires_at > NOW()";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token', $token);
    
    try {
        $stmt->execute();
        $resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resetRequest) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
                $newPassword = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
                
                // Atualizar a senha no banco de dados
                $sql = "UPDATE usuarios SET senha = :new_password WHERE email = :email";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':new_password', $newPassword);
                $stmt->bindParam(':email', $resetRequest['email']);
                
                if ($stmt->execute()) {
                    $message = "Senha atualizada com sucesso!";
                    // Excluir o token após o uso
                    $sql = "DELETE FROM nova_senha WHERE token = :token";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':token', $token);
                    $stmt->execute();
                } else {
                    $message = "Erro ao atualizar a senha.";
                }
            }
        } else {
            $message = "Token inválido ou expirado.";
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
    <title>Redefinição de Senha</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Redefinir Senha</h2>
    <?php if ($message): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if (isset($resetRequest)): ?>
        <form action="" method="POST">
            <input type="password" name="new_password" placeholder="Nova Senha" required>
            <button type="submit">Redefinir Senha</button>
        </form>
    <?php endif; ?>
  </div>
</body>
</html>
