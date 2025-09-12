<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}

$pista = file_get_contents("aouehncbvahks/pista_6.txt");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pista 6</title>
    <style>
        body {
            font-family: monospace;
            background-color: #111;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            border: 2px solid #0f0;
            padding: 20px;
            max-width: 600px;
        }
        pre {
            border: 1px solid #0f0;
            padding: 15px;
            background: #000;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <pre><?php echo htmlspecialchars($pista); ?></pre>
    </div>
</body>
</html>
