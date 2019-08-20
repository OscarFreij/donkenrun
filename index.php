<?php

session_start();

require_once 'priv/twig.php';
require_once 'priv/pdo.php';

if(!isset($_SESSION['donken']['username']))
{
    $model = [];
    if(isset($_GET['error']))
    {
        $model['error'] = $_GET['error'];
    }
    echo $twig->render('login.twig', $model);
    die();
}

$sql = '
SELECT * FROM donken_products
';
$model['products'] = DB::prepare($sql)->execute()->fetchAll();

echo $twig->render('index.twig', $model);