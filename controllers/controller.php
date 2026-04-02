<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../models/categorie.php';

$categoriedb = new categorieDB();
$categories = $categoriedb->readall();
