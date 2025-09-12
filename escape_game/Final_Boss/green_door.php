<?php
session_start();
$doorName = 'Verde';
$sessionKey = 'porta_green_secret';
$tempoTotal = 180; // 3 minutos
$password = "Green";
if (!isset($_SESSION[$sessionKey])) {
    $_SESSION[$sessionKey] = "231";
    
};

if (!isset($_SESSION['porta_green_start'])) {
    $_SESSION['porta_green_start'] = time();
}

$tempoPassado = time() - $_SESSION['porta_green_start'];
$tempoRestante = max(0, $tempoTotal - $tempoPassado);

if ($tempoRestante <= 0) {
    $_SESSION['porta_green_expirada'] = true;
}
if (isset($_SESSION['porta_green_expirada']) && $_SESSION['porta_green_expirada'] === true) {
    echo "<h1>🚪 Esta porta esta trancada!</h1>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
   if ($_POST['action'] === 'check') {
    $d1 = intval($_POST['d1']);
    $d2 = intval($_POST['d2']);
    $d3 = intval($_POST['d3']);
    $try = sprintf('%d%d%d', $d1, $d2, $d3);
    $secret = str_pad(preg_replace('/[^0-9]/','', $_SESSION[$sessionKey]),3,'0',STR_PAD_LEFT);
    $ok = ($try === $secret);
    
    echo json_encode([
        'success' => $ok,
        'try' => $try,
        'password' => $ok ? $password : null
    ]);
    exit;
    }
    if ($_POST['action'] === 'expire') {
       $_SESSION['porta_green_expirada'] = true;
        echo json_encode(['expired'=>true]);
        exit;
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Porta <?php echo $doorName ?></title>
<style>
html, body {
    height: 100%;
    margin: 0;
}
body{
    font-family: 'Courier New', monospace;
    background: #000000ff;
    color: #1eff00ff;
    display: flex;
    justify-content: center;
    align-items: center;
}
.container{
    text-align: center;
}
.digital-timer{
    font-size: 40px;
    font-weight: bold;
    letter-spacing: 4px;
    margin: 20px 0;
    color: #2bff00ff;
    padding: 20px 40px;
    border-radius: 10px;
    display: inline-block;
}
.digital-timer.danger{
    color: #ff3b3b;
    animation: blink 0.8s steps(1) infinite;
}
@keyframes blink{50%{opacity:0}}
.lock{
    display:flex;
    gap:10px;
    justify-content: center;
    align-items:center;
    margin-top:20px;
}
.wheel{
    background:#222;
    padding:10px;
    border-radius:6px;
    text-align:center;
}
.wheel button{
    display:block;
    width:40px;
    margin:6px 0;
}
.digit{
    font-size:28px;
    font-weight:700;
}
.try{
    margin-top:20px;
    padding:10px 20px;
    font-size:18px;
    cursor:pointer;
}
.status{
    margin-top:15px;
    font-size:20px;
}
.lock.shake{
    animation:shake 0.5s ease-in-out;
}
@keyframes shake{
    0%{transform:translateX(0);}
    25%{transform:translateX(-3px);}
    50%{transform:translateX(3px);}
    75%{transform:translateX(-2px);}
    100%{transform:translateX(0);}
}
</style>
</head>
<body>
<div class="container">
<h1>Porta <?php echo $doorName ?></h1>
<p>Tempo por jogada: <strong>3 minutos</strong>.</p>
<div id="timer" class="digital-timer"></div>

<div class="lock" id="lock">
<?php for($i=0;$i<3;$i++): ?>
  <div class="wheel">
    <button class="up" data-wheel="<?php echo $i ?>">↑</button>
    <div class="digit" data-value="0">0</div>
    <button class="down" data-wheel="<?php echo $i ?>">↓</button>
  </div>
<?php endfor; ?>
</div>

<button class="try" id="tryBtn">Unlock</button>
<div class="status" id="status"></div>
</div>

<script>
let tempo = <?php echo $tempoRestante; ?>;
const timerEl = document.getElementById('timer');
const lockEl = document.getElementById('lock');
const statusEl = document.getElementById('status');

function formatTime(s){
    let min = Math.floor(s/60);
    let seg = s%60;
    return `${min<10?'0':''}${min}:${seg<10?'0':''}${seg}`;
}

function updateTimer(){
    timerEl.textContent = formatTime(tempo);
    if(tempo <= 10) timerEl.classList.add('danger');
    if(tempo <= 0){
        clearInterval(timerInterval);
        fetch('',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'action=expire'}).then(()=>{window.close();});
    }
    tempo--;
}
let timerInterval = setInterval(updateTimer,1000);

document.querySelectorAll('.up').forEach(btn=>{
    btn.onclick=()=>{
        let wheel=parseInt(btn.dataset.wheel);
        let digit=document.querySelectorAll('.digit')[wheel];
        let val=(parseInt(digit.dataset.value)+1)%10;
        digit.dataset.value=val;
        digit.textContent=val;
    }
});
document.querySelectorAll('.down').forEach(btn=>{
    btn.onclick=()=>{
        let wheel=parseInt(btn.dataset.wheel);
        let digit=document.querySelectorAll('.digit')[wheel];
        let val=(parseInt(digit.dataset.value)+9)%10;
        digit.dataset.value=val;
        digit.textContent=val;
    }
});

document.getElementById('tryBtn').onclick=()=>{
    let digits=[...document.querySelectorAll('.digit')].map(d=>d.dataset.value);
    let formData = new URLSearchParams();
    formData.append('action','check');
    formData.append('d1',digits[0]);
    formData.append('d2',digits[1]);
    formData.append('d3',digits[2]);
    fetch('',{method:'POST',body:formData}).then(r=>r.json()).then(data=>{
        if(data.success){
    statusEl.textContent = `✅ Porta aberta! Senha: ${data.password}`;
    lockEl.classList.remove('shake');
    clearInterval(timerInterval);
}else{
    statusEl.textContent = `❌ Código incorreto: ${data.try}`;
    lockEl.classList.add('shake');
    setTimeout(()=>lockEl.classList.remove('shake'),500);
}

    });
};
</script>
</body>
</html>
