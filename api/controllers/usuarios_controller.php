<?php
require_once '../conexion_bd_usuarios.php';

$data = json_decode(file_get_contents("php://input"), true);
$query = $conn->prepare("INSERT INTO usuarios (username, password, role) VALUES (?, ?, ?)");
$result = $query->execute([
    $data['username'], password_hash($data['password'], PASSWORD_DEFAULT), $data['role']
]);
echo json_encode(["success" => $result]);
?>
