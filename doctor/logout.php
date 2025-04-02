<?php
// Faire appel à l'API de déconnexion pour détruire la session côté serveur
$url = "http://localhost/api/doctor/logout.php";
$response = file_get_contents($url);

// Vérifier si la réponse contient un succès
$responseData = json_decode($response, true);
var_dump($responseData); // Vérifie ce que l'API retourne

if (isset($responseData['status']) && $responseData['status'] === 'success') {
    // Supprimer tous les cookies restants
    foreach ($_COOKIE as $key => $value) {
        setcookie($key, '', time() - 3600, '/');
    }

    // Rediriger l'utilisateur vers la page de connexion après déconnexion
    header("Location: /doctor/login.php");
    exit;
} else {
    echo "Erreur lors de la déconnexion.";
}
?>
