<?php
declare(strict_types=1);
session_start();
require_once 'database/database.php';
require_once 'app/helpers.php';



$pageTitle = "page admin";
render('admin/admin', [
    'pageTitle' => $pageTitle
], 'admin-layout');