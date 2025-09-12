<?php

function normalize($s) {
    return mb_strtolower(trim($s));
}

// Função SHA-256 
function sha256Hex($str) {
    return hash("sha256", $str);
}

$SECRET = base64_decode("aDREWl0tczVkY2ZyZD1tcmGhsY2Vj%00"); 

$LEVELS = [
    [
      
        'id' => 1,
        'text' => [
            "<h2> Task 1</h2>",
            "<h2> Dada a Hash a seguir decodifique a senha:</h2>",
            "<h2> 01100111 01101001 01101111 01110110 01100001 01101110 01100001</h2>"
        ],
        'salt' => 'k9z!A3',
        'hash' => '59e10147e33cc7c704caa91972a32cc26d8b62e8b053d9d5b797d73e9d3cc085'
    ],
    [
        'id' => 2,
        'text' => [
            "Task 2",
            "<!--Utilize esse site para descriptar https://www.convertstring.com/pt_PT/EncodeDecode/HexDecode# -->",
            "<h2> Descripitografe a charada e descubra a senha:",
            "<h3>69 6E 73 74 61 67 72 61 6D 2E 63 6F 6D 2F 65 6E 67 63 6F 6D 70 73 6F 66 74 6C 69 6E 73 2F</h3>",
            "<h4>Dica: Esta na base Hexadecimal</h4>",
        ],
        'salt' => 'Qp_72v',
        'hash' => '7c268e814c07b3f4a7794f93ebd5d5eeae58653cbcb6fd00ca105aa3a4c77951'
    ],
    
      [  'id' => 3,
        'text' => [
        "<h2>Task 3</h2>",
        "Passworld:",
        "<h1>たのまる</h1>",
        "<h3>To talk to him you have to know at least two languages</h3>"
        ],
        'salt' => 'mZ$1-0',
        'hash' => '6c3e2e9d2e33becb47e2dbc67f07c8a6bb8748d8157228af29216fe8e3099578'
      ],
  
        ['id' => 4,
        'text' => [
            "<h2>Task 4",
         ' 
         <!--Detalhes que muitas vezes as pessoas desconsideram-->
         <img src="6861636B6572.png"style=" width: 750px; height: 500px;">
         <!--            ↑       -->',
        "Analise a imagem e me retorne a senha:"
        ],
        'salt' => '77Xaa',
        'hash' => 'e43fef40d8d90cbd6ef6606d20ba94ead9543ae600e3c2a12d13c3ca502e41fb'
    ],

       [ 'id' => 5,
        'text' => [
           "<h2>Task 5</h2>",
        "<h1>🎮</h1>",
        "<!--CNPJ-->
        <h1>26316272000199</h1>"
        ],
        'salt' => 'p0L!ce',
        'hash' => 'fac1cfd39b69cf09719838b7e418ff1320d9ba6b28fef6b559fa7af22523246f',
    
    ],

       [ 'id' => 6,
        'text' => [
            "<h2>Task 6</h2>",
            "<h2>{user} and {password}</h2>",
        "<h1> <a  style='color: red; text-decoration: none; ' href='login.php' target='_blank' >⚠ Clique aqui ⚠</a> </h1>"
        ], 
        'salt' => 'UniLins',
        'hash' => 'a9a30f3c19fe8330ef475db0989be42e18c148362d450cde8622ca49e0393d36'
      ],

    [ 
    'id' => 7,
    'text' => [
        "<h2>Task 7</h2>",
        "<!--Utilize -->",
        "<h2>Você recebeu uma mensagem anônima dizendo que a próxima pista está em um roteador secreto dentro da rede. Para encontrá-lo, você precisará descobrir o IP correto <br></h2>",
        "<h3><a href='ip' target='_blank' style='color: red; text-decoration: none;' > DICA: Utilize o diretorio (IP) para identificar qual e o correto!</a></h3>",
        "<h3><p>
        192.168.0.64 <br>
        192.168.0.30 <br>
        192.168.0.55 <br>
        192.168.0.70 <br>
        192.168.0.98 <br>
        192.168.0.256 <br>
        </p></h3>",
    ],
    'salt' => 'ipNu3er',
    'hash' => '4619a043e5717317458d3b15b61ef5d2154f94bd681a015040d76282d3ac1aa9'
],
    [ 'id' => 8,
        'text' => [
            "<h2>Task 8</h2>",
        "<!--Se atente aos numeros (Sendo eles ate mesmo impensaveis!)-->",
        "<h1>-21.6672866 -49.7314226</h1>"
        ],
        'salt' => 'UniLins',
        'hash' => 'ad21d19d801140d80daf056ad2ec52917fc80681d5c37e71843e9503da1ae996' 
    ],
    [ 'id' => 9,
        'text' => [
            "<h1>Task 9</h1>",
        "
        <!--Hexa decimal do Vermelho: #FF0000--->
        <!--Hexa decimal do Azul: #0000FF--->
        <!--Escolha com cuidado--->",
        '<img src="escolha.png" style=" width: 750px; height: 500px;">'
        ],
        'salt' => 'maTrix',
        'hash' => ['6abaf7f2fde81f8de686b4d089ef77523cbd1af5f040c04b7ffe1d95117b2d9f',
        '78fbbe14a492fb8cadfc12e5386d54dd50bc157b6d2ac9e884a07a2c422a2204']
    ],
    [ 
    'id' => 10,
    'salt' => 'doors',
    'hash' => ''
]
];
// Função para gerar ticket de progresso 
function makeTicket($prevTicket, $normalizedAnswer) {
    global $SECRET;
    return sha256Hex($SECRET . $normalizedAnswer . $prevTicket);
}

// Função para pegar o ticket anterior necessário
function requiredPrevTicket($idx) {
    global $SECRET;
    if ($idx === 0) return sha256Hex($SECRET . "#start");
    return $_SESSION["t" . ($idx - 1)] ?? null;
}

function checkAnswer($userInput, $currentIdx) {
    global $LEVELS;
    
    $user = normalize($userInput);

    if (!$user) {
        return "❌ Entrada inválida.";
    }

    // Pega o nível atual
    $level = $LEVELS[$currentIdx];
    $computedHash = sha256Hex($level['salt'] . $user);

    // Suporta múltiplos hashes (array ou string única)
    $validHashes = is_array($level['hash']) ? $level['hash'] : [$level['hash']];

    // Verifica se a resposta está correta
    if (in_array($computedHash, $validHashes)) {
        // Salva o ticket e avança para o próximo nível
        $prevTicket = requiredPrevTicket($currentIdx);
        $ticket = makeTicket($prevTicket, $user);
        $_SESSION["t" . $currentIdx] = $ticket;

        return "✅ Correto!";
    } else {
        return "❌ Senha incorreta.";
    }
}



// Lógica de controle de nível
session_start();
$current = $_SESSION['current_level'] ?? 0; // Obtém o nível atual do usuário

// Recebe a resposta do usuário via POST (assumindo que o formulário está sendo enviado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userAnswer = $_POST['answer'] ?? '';
    $result = checkAnswer($userAnswer, $current);

    // Atualiza o nível do usuário se a resposta estiver correta
    if (strpos($result, "✅ Correto") !== false) {
        $_SESSION['current_level'] = ++$current;
    }
}

function renderLevel($currentIdx) {
    global $LEVELS;

    // Se já terminou todos os níveis
    if ($currentIdx >= count($LEVELS)) {
        echo '<div style="
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 80vh;
            color: #28a745;
            font-size: 2.5em;
            font-family: \'Roboto Mono\', monospace;
            text-align: center;
        ">
            <h1>🎉 Parabéns! 🎉</h1>
            <p>Você completou todos os níveis do Escape Room 🚀</p>
        </div>';
        exit(); 
    }

    // Pega o nível atual
    $level = $LEVELS[$currentIdx];


// Task 10: gera links das portas expiradas
    if ($level['id'] === 10) {
        // Verifica se todas expiraram
        if (
            !empty($_SESSION['porta_black_expirada']) &&
            !empty($_SESSION['porta_green_expirada']) &&
            !empty($_SESSION['porta_brown_expirada']) &&
            !empty($_SESSION['porta_pink_expirada'])
        ) {
            echo "<h1>❌ Você perdeu todas as portas.<br>Fim do jogo.</h1>";
            exit;
        }

        echo "<h2>Task 10</h2>";
        echo "<div style='display:flex;gap:20px;justify-content:center;margin-top:20px'>";
        if (empty($_SESSION['porta_black_expirada'])) {
            echo '<a href="Final_Boss/black_door.php" target="_blank"><img src="Final_Boss/img_doors/black_door.png" alt="Porta Preta"></a>';
        }
        if (empty($_SESSION['porta_green_expirada'])) {
            echo '<a href="Final_Boss/green_door.php" target="_blank"><img src="Final_Boss/img_doors/green_door.png" alt="Porta Verde"></a>';
        }
        if (empty($_SESSION['porta_brown_expirada'])) {
            echo '<a href="Final_Boss/brown_door.php" target="_blank"><img src="Final_Boss/img_doors/brown_door.png" alt="Porta Marrom"></a>';
        }
        if (empty($_SESSION['porta_pink_expirada'])) {
            echo '<a href="Final_Boss/pink_door.php" target="_blank"><img src="Final_Boss/img_doors/pink_door.png" alt="Porta Rosa"></a>';
        }
        echo "</div>";
        return;
    }



    // Exibe o puzzle do nível atual
    echo "<h2>Nível {$level['id']} de " . count($LEVELS) . "</h2>";
    foreach ($level['text'] as $line) {
        echo "<p><code>{$line}</code></p>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js.js" defer></script>
    <link rel="stylesheet" href="sistema.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <title>Escape Room</title>
</head>
<body>
    <div class="wrap">
        <form method="POST">
            <h1>🔐 Escape Room Hacker</h1>
            <div class="sub">Resolva cada puzzle. Digite a senha no terminal para liberar o próximo nível.</div>

            <?php renderLevel($current); ?>

            <div class="terminal" id="term">
                <div class="lamp"></div>
                <div id="screen" aria-live="polite" class="muted"></div>

                <div class="line">
                    <span class="prompt">&gt;</span>
                    
                    <input name="answer" id="answer" type="text" placeholder="_" autocomplete="off" />
                    <button type="submit">Enviar</button>
                </div>

                <div class="panel" id="feedback">
                    <?php
                        if (isset($result)) {
                            echo "<p>{$result}</p>";
                        }
                    ?>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
