<?php
// exportar.php
require_once "config.php"; // usa a conexão PDO já definida

// Define o nome do arquivo de saída
$filename = "avaliacoes_" . date('Y-m-d_H-i-s') . ".csv";

// Configura cabeçalhos para download
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=$filename");

// Abre saída para escrita
$output = fopen("php://output", "w");

// Cabeçalhos do CSV
fputcsv($output, [
    'ID',
    'Cantina',
    'Higiene',
    'Preços',
    'Atendimento',
    'Comentários',
    'Data da Avaliação',
    'IP Usuário'
], ';'); // usa ponto e vírgula como separador

// Busca dados do banco
$sql = "SELECT id, cantina, higiene, precos, atendimento, comentarios, data_avaliacao, ip_usuario 
        FROM avaliacoes
        ORDER BY data_avaliacao DESC";

$stmt = $pdo->query($sql);

// Escreve linha a linha no CSV
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row, ';');
}

fclose($output);
exit;
