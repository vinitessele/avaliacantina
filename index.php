<?php
require_once 'config.php';

if ($questions_to_show === 1) {
    header("Location: primeiro_set_perguntas.php");
    exit;
} elseif ($questions_to_show === 2) {
    header("Location: segundo_set_perguntas.php");
    exit;
} else {
    echo "Configuração inválida em config.php";
}