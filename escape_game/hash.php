<?php

$senha = "black";
$salt  = "doors";

echo hash("sha256", $salt . $senha) ;


?>
