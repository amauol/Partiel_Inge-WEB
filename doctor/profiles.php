<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: /doctor/login.php");
    exit();
}

// Appeler l'API pour récupérer les informations du docteur
$doctorId = $_SESSION['user_id'];
$url = "http://localhost/api/doctor/profiles.php?doctor_id=" . $doctorId;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$doctorInfo = json_decode($response, true);

// Vérifier si l'API a retourné une erreur ou si les données sont manquantes
if (!$doctorInfo || isset($doctorInfo['error'])) {
    die("Erreur lors de la récupération des informations du docteur.");
}

// Fonction utilitaire pour afficher les données ou une valeur par défaut
function safeOutput($value, $default = 'Non spécifié') {
    return htmlspecialchars($value ?? $default);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil du Docteur</title>
    <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Profil du Docteur</h2>
    <table class="table table-bordered">
        <tr><th>Nom</th><td><?php echo safeOutput($doctorInfo['name']); ?></td></tr>
        <tr><th>Email</th><td><?php echo safeOutput($doctorInfo['email']); ?></td></tr>
        <tr><th>Spécialisation</th><td><?php echo safeOutput($doctorInfo['specialist']); ?></td></tr>
        <tr><th>Téléphone</th><td><?php echo safeOutput($doctorInfo['phone']); ?></td></tr>
        <tr><th>Genre</th><td><?php echo safeOutput($doctorInfo['gender'] == 0 ? 'Homme' : ($doctorInfo['gender'] == 1 ? 'Femme' : 'Non spécifié')); ?></td></tr>
        <tr><th>Date de création</th><td><?php echo safeOutput($doctorInfo['created']); ?></td></tr>
    </table>
    <a href="/master.php" class="btn btn-primary">Back to dashboard</a>
</div>
</body>
</html>