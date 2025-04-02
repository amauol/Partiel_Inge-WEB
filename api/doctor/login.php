<?php
require_once('../config/database.php');
require_once('../objects/doctor.php');
session_start();

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["success" => false, "message" => "Méthode non autorisée."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true) ?: $_POST;

$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Email et mot de passe requis."]);
    exit;
}

$database = new Database();
$db = $database->getConnection();

$doctor = new Doctor($db);
$doctor->email = $email;

$result = $doctor->login($email, $password);

if ($result["success"]) {
    echo json_encode([
        "success" => true,
        "user" => [
            "id" => $result["user"]["id"],
            "name" => $result["user"]["name"]
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect."]);
}