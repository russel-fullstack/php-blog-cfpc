<?php

declare(strict_types=1);
session_start();
require_once 'database/database.php';
require_once 'app/helpers.php';


$pageTitle = "page user";

render('users/user', [
    'pageTitle' => $pageTitle
], 'user-layout');
