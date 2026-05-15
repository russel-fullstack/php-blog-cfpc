<?php

declare(strict_types=1);

// Définir les constantes pour la connexion à la base de données
define('DB_SERVERNAME', '127.0.0.1');
define('DB_USERNAME', 'valet');
define('DB_PASSWORD', 'valet');
define('DB_DATABASE', 'blog_php_2026');

/**
 * Retourne une instance unique de connexion à la base de données (Singleton)
 */
function getPdo(): PDO
{
    // Variable statique pour stocker l'instance PDO
    static $pdo = null;

    // Si l'instance n'existe pas encore, on la crée
    if ($pdo === null) {
        try {
            $pdo = new PDO('mysql:host=' . DB_SERVERNAME . ';dbname=' . DB_DATABASE . ';charset=utf8', DB_USERNAME, DB_PASSWORD);

            // Configurer le mode d'erreur pour lancer des exceptions
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // On configure le mode de récupération par défaut en tableau associatif
            // Cela nous évite de devoir le préciser dans chaque fetch()
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // En cas d'erreur, on arrête le script avec un message clair
            die("<div style='color:red;'>La connexion à la base de données a échoué :</div> " . $e->getMessage());
        }
    }

    // On retourne l'instance (existante ou nouvellement créée)
    return $pdo;
}

// Déclaration globale temporaire pour assurer la rétrocompatibilité
$pdo = getPdo();
