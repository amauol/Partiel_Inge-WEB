<?php
require_once('../config/database.php');
require_once('../objects/doctor.php');

header('Content-Type: application/json');

// Vérifier si l'ID du docteur est fourni
if (!isset($_GET['doctor_id'])) {
    echo json_encode(['error' => 'L\'ID du docteur est requis.']);
    exit();
}

$doctorId = intval($_GET['doctor_id']);

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Récupérer les informations du docteur
$doctor = new Doctor($db);
$doctor->id = $doctorId;

$stmt = $doctor->read_single(); // Appeler la méthode read_single()

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Assigner les valeurs aux propriétés de l'objet Doctor
    $doctor->name = $row['name'];
    $doctor->email = $row['email'];
    $doctor->password = $row['password'];
    $doctor->phone = $row['phone'];
    $doctor->gender = $row['gender'];
    $doctor->specialist = $row['specialist'];
    $doctor->created = $row['created'];

    // Retourner les données en JSON
    echo json_encode([
        'id' => $doctor->id,
        'name' => $doctor->name,
        'email' => $doctor->email,
        'specialist' => $doctor->specialist,
        'phone' => $doctor->phone,
        'gender' => $doctor->gender,
        'created' => $doctor->created
    ]);
} else {
    echo json_encode(['error' => 'Docteur introuvable.']);
}
?>