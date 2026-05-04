<?php
declare(strict_types=1);
session_start();
require_once 'database/database.php';


$pageTitle = "page admin";
ob_start();
require_once 'resources/views/admin/admin_html.php';

$pageContent = ob_get_clean(); // Récupérer le contenu du tampon de sortie et le stocker dans la variable $pageContent
require_once 'resources/views/layouts/admin-layout/admin-layout_html.php'; //Inclure le layout du blog qui affichera le header, le contenu et le footer
