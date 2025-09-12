<?php
session_start();

// Usuário e senha correta
$USER_CORRETO = "admin";
$SENHA_CORRETA = "admin";

// Inicializa mensagem
$msg = "";

// Processa o login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? "";
    $senha = $_POST['senha'] ?? "";

    if ($usuario !== $USER_CORRETO) {
        $msg = "Usuário não encontrado.";
    } elseif ($senha !== $SENHA_CORRETA) {
        // Mensagem de erro sutil
        $msg = "Senha incorreta.";
    } else {
        // Login válido → cria sessão
        $_SESSION['logado'] = true;
        header("Location: pista_6.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login Sistema Hacker</title>
    <style>
        body { font-family: monospace; background-color: #111; color: #0f0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { border: 2px solid #0f0; padding: 20px; }
        input { background-color: #000; color: #0f0; border: 1px solid #0f0; padding: 5px; margin: 5px 0; }
        input[type="submit"] { cursor: pointer; }
        .msg { margin-top: 10px; color: #f00; }
    </style>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <form method="post">
        Usuário: <br>
        <input type="text" name="usuario" required><br>
        Senha: <br>
        <input type="password" name="senha" required><br>
        <input type="submit" value="Entrar">
    </form>
    <?php if ($msg) echo "<div class='msg'>$msg</div>"; ?>
</div>
</body>
</html>
