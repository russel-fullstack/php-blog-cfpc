<?php
declare(strict_types=1);

session_start();
require_once 'database/database.php';
require_once 'flash.php';
require_once 'app/enums/role.php';
require_once 'app/helpers.php';

checkAdmin();
deleteArticle((int)$_GET['id']);
flash_set('success', 'Article supprimé avec succès !');

redirect('admin-list-article.php');