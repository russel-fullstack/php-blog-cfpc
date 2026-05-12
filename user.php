<?php
declare(strict_types=1);
session_start();
require_once 'database/database.php';


$pageTitle = "page user";
ob_start();
require_once 'resources/views/users/user_html.php';

$pageContent = ob_get_clean(); // Récupérer le contenu du tampon de sortie et le stocker dans la variable $pageContent
require_once 'resources/views/layouts/user-layout/user-layout_html.php'; //Inclure le layout du blog qui affichera le header, le contenu et le footer
