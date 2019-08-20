<?php

session_start();

require_once 'priv/twig.php';
require_once 'priv/pdo.php';

if(!isset($_SESSION['donken']['username']))
{
    header('Location: index.php');
    die();
}

if($_SERVER['REQUEST_METHOD'] == 'GET')
{
    $sql = '
    SELECT 
        *,
        SUM(donken_order_products.quantity) AS num_orders
    FROM donken_products
    LEFT JOIN donken_order_products ON donken_products.id = donken_order_products.donken_orders_id
    GROUP BY donken_order_products.donken_orders_id
    ';
    $model['products'] = DB::prepare($sql)->execute()->fetchAll();

    $sql = '
    SELECT 
        donken_orders.id,
        donken_orders.customer,
        donken_orders.paid,
        donken_orders.delivered,
        SUM(donken_order_products.quantity * donken_products.price) AS price
    FROM donken_orders
    LEFT JOIN donken_order_products ON donken_orders.id = donken_order_products.donken_orders_id
    LEFT JOIN donken_products ON donken_products.id = donken_order_products.donken_products_id
    GROUP BY donken_order_products.donken_orders_id
    ';
    $model['orders'] = DB::prepare($sql)->execute()->fetchAll();
    $model['config'] = json_decode(file_get_contents('priv/config.json'));

    echo $twig->render('admin.twig', $model);
    die();
}

if($_POST['action'] == 'delete')
{
    $sql = 'DELETE FROM donken_products WHERE id = ?';
    DB::prepare($sql)->execute([$_POST['id']]);
    header('Location: admin.php');
    die();
}


if($_POST['action'] == 'create')
{
    $sql = 'INSERT INTO donken_products (name, price) VALUES (?, ?)';
    DB::prepare($sql)->execute([$_POST['name'], $_POST['price']]);
    header('Location: admin.php');
    die();
}


if($_POST['action'] == 'config')
{
    file_put_contents('priv/config.json', json_encode(['swish_number' => $_POST['swish_number']]));
    header('Location: admin.php');
    die();
}

