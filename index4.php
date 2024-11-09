<?php
require_once 'conexao.php';

$message = '';
$form_state = 'login';

// Registro de um novo usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
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
        $form_state = 'login';
    } catch (PDOException $e) {
        $message = "Erro ao cadastrar usuário: " . $e->getMessage();
    }
}

// Login do usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);

    try {
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $message = "Login realizado com sucesso!";
        } else {
            $message = "Credenciais inválidas!";
        }
    } catch (PDOException $e) {
        $message = "Erro ao realizar login: " . $e->getMessage();
    }
}

// Recuperação de senha
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot_password'])) {
    $email = $_POST['email'];

    // Verifica se o e-mail existe no banco de dados
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    
    try {
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Gera o token de recuperação
            $token = bin2hex(random_bytes(50));

            // Armazena o token e a data de expiração no banco de dados
            $sql = "INSERT INTO nova_senha (email, token, expires_at) 
                    VALUES (:email, :token, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            // Gera o link de redefinição de senha
            $resetLink = "http://localhost/pi2/redef_senha.php?token=" . $token;

            // Envia o e-mail com o link de redefinição
            $subject = "Redefinição de Senha";
            $message = "Clique no link abaixo para redefinir sua senha: \n" . $resetLink;
            $headers = "From: no-reply@seusite.com";

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

// Redefinir a senha após o clique no link
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $message = '';

    // Verifica se o token existe e se não expirou
    $sql = "SELECT * FROM nova_senha WHERE token = :token AND expires_at > NOW()";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token', $token);
    
    try {
        $stmt->execute();
        $resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resetRequest) {
            // Formulário para nova senha
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
                $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                // Atualiza a senha do usuário
                $sql = "UPDATE usuarios SET password = :password WHERE email = :email";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':password', $newPassword);
                $stmt->bindParam(':email', $resetRequest['email']);
                $stmt->execute();

                // Remove o token da tabela de resets
                $sql = "DELETE FROM nova_senha WHERE token = :token";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':token', $token);
                $stmt->execute();

                $message = "Senha redefinida com sucesso! Você pode fazer login agora.";
            }
        } else {
            $message = "Token inválido ou expirado.";
        }
    } catch (PDOException $e) {
        $message = "Erro ao processar a solicitação: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login e Cadastro</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function scrollToRegister() {
            const registerForm = document.getElementById("register-form");
            if (registerForm.style.display === "none") {
                registerForm.style.display = "block";
                document.getElementById("login-form").style.display = "none";
            } else {
                registerForm.style.display = "none";
                document.getElementById("login-form").style.display = "block";
            }
        }

        function showForgotPasswordForm() {
            document.getElementById("login-form").style.display = "none";
            document.getElementById("forgot-password-form").style.display = "block";
        }
    </script>
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
    <!-- Seção de Login -->
    <div id="login-form" style="display: <?php echo $form_state == 'login' ? 'block' : 'none'; ?>;">
        <h2>Login</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit" name="login">Entrar</button>
        </form>

        <a href="esq_senha.php" class="link">Esqueceu sua senha?</a>

        <a href="javascript:void(0);" onclick="scrollToRegister()" class="link">Não tenho conta</a>
    </div>

    <!-- Formulário de "Esqueci Minha Senha" -->
    <div id="forgot-password-form" style="display: none;">
        <h2>Recuperar Senha</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="email" name="email" placeholder="E-mail" required>
            <button type="submit" name="forgot_password">Enviar Link para Redefinição</button>
        </form>
    </div>

    <!-- Seção de Cadastro -->
    <div id="register-form" style="display: <?php echo $form_state == 'register' ? 'block' : 'none'; ?>;">
        <h2>Cadastrar</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="text" name="full_name" placeholder="Nome Completo" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="text" name="registration_number" placeholder="Número de Registro" required>
            <input type="date" name="birth_date" placeholder="Data de Nascimento" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit" name="register">Cadastrar</button>
        </form>

        <a href="javascript:void(0);" onclick="scrollToRegister()" class="link">Já tenho uma conta</a>
    </div>
  </div>
</body>
</html>
