<?php
$data = $_GET["data"];

$conversor = explode("?", $data);

$data = $conversor[0]; // Data da requisição
$endereco = $conversor[1]; // Caminho até o local do arquivo e nome do arquivo

// Copiando o arquivo e disponibilizando o mesmo para download
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream;");
header("Content-Length:".filesize($endereco));
header("Content-disposition: attachment; filename=".$endereco);
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: 0");
readfile($endereco);
flush();