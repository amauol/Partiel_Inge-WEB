<?php
// Activer l'affichage des erreurs pour le debug (en développement uniquement)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Commencer la session
session_start();

// Détruire toutes les variables de session
$_SESSION = [];

// Si les cookies de session sont utilisés, les supprimer
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params["path"], $params["domain"], $params["secure"], $params["httponly"]
    );
}

// Détruire la session
session_destroy();
session_write_close(); // S'assurer que tout est bien écrit

// Définir le type de réponse JSON
header("Content-Type: application/json");

// Retourner une réponse JSON confirmant la déconnexion
echo json_encode(['status' => 'success']);
exit;
?>
