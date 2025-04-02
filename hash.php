<?php
// Inclure la configuration de la base de données
include_once './api/config/database.php';

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Récupérer tous les docteurs dont les mots de passe ne sont pas encore hachés
$query = "SELECT id, password FROM doctors";
$stmt = $db->prepare($query);
$stmt->execute();

// Mettre à jour chaque mot de passe
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $row['id'];
    $password = $row['password'];

    // Si le mot de passe est encore encodé en base64, on le décode d'abord
    $decoded_password = base64_decode($password);

    // Hacher le mot de passe avec PASSWORD_DEFAULT (bcrypt)
    $hashed_password = password_hash($decoded_password, PASSWORD_DEFAULT);

    // Mettre à jour le mot de passe dans la base de données
    $update_query = "UPDATE doctors SET password = :password WHERE id = :id";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->bindParam(':password', $hashed_password);
    $update_stmt->bindParam(':id', $id);

    if ($update_stmt->execute()) {
        echo "Mot de passe pour le docteur ID $id mis à jour avec succès.\n";
    } else {
        echo "Erreur lors de la mise à jour du mot de passe pour le docteur ID $id.\n";
    }
}

echo "Mise à jour terminée.";
?>