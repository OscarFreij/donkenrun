<?php
require_once 'vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('priv/templates');
$twig = new \Twig\Environment($loader);
$twig->addGlobal('session', $_SESSION);