<?php
declare(strict_types=1);
session_start();
require_once './database/database.php';
require_once 'flash.php';

$pageTitle = 'Notre blog d\'accueil';// Titre de la page d'accueil du blog
ob_start();// créer un tampon de sortie pour stocker le contenu de la page d'accueil du blog

require_once 'resources/views/blog/index_html.php';

$pageContent = ob_get_clean(); // Récupérer le contenu du tampon de sortie et le stocker dans la variable $pageContent
require_once 'resources/views/layouts/blog-layout/blog-layout_html.php'; //Inclure le layout du blog qui affichera le header, le contenu et le footer


?>