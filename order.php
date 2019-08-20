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
    if(!isset($_GET['id']) || $_GET['id'] == '')
    {
        header('Location: admin.php');
        die();
    }

    $sql = '
    SELECT 
        product.name, 
        product.price,
        order_product.quantity,
        (order_product.quantity * product.price) AS price_sum
    FROM donken_orders
    JOIN donken_order_products AS order_product ON order_product.donken_orders_id = donken_orders.id
    JOIN donken_products AS product ON product.id = order_product.donken_products_id
    WHERE donken_orders.id = ?
    ';
    $model['products'] = DB::prepare($sql)->execute([$_GET['id']])->fetchAll();

    $sql = '
    SELECT 
        *
    FROM donken_orders
    WHERE donken_orders.id = ?
    ';
    $model['order'] = DB::prepare($sql)->execute([$_GET['id']])->fetch();
    $model['total_price'] = array_sum(array_column($model['order'], 'price_sum'));

    echo $twig->render('order.twig', $model);
    die();
}

if($_POST['action'] == 'update-paid')
{

    $sql = '
    UPDATE donken_orders
    SET paid = ?
    WHERE id = ?
    ';
    DB::prepare($sql)->execute([$_POST['paid'], $_POST['id']]);
    header('Location: order.php?id=' . $_POST['id']);
    die();
}

if($_POST['action'] == 'update-delivered')
{

    $sql = '
    UPDATE donken_orders
    SET delivered = ?
    WHERE id = ?
    ';
    DB::prepare($sql)->execute([$_POST['delivered'], $_POST['id']]);
    header('Location: order.php?id=' . $_POST['id']);
    die();
}

$sql = '
INSERT INTO donken_orders
(customer, paid, comment)
VALUES (?, ?, ?)
';
DB::prepare($sql)->execute([$_POST['name'], isset($_POST['paid']), $_POST['comment']]);
$orderId = DB::lastInsertId();

foreach($_POST['products'] as $productId => $quantity)
{
    if($quantity > 0)
    {
        $sql = '
        INSERT INTO donken_order_products
        (donken_orders_id, donken_products_id, quantity)
        VALUES (?, ?, ?)
        ';
        DB::prepare($sql)->execute([$orderId, $productId, $quantity]);
    }
}

header('Location: order.php?id=' . $orderId);