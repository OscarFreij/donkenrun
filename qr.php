<?php

if(!isset($_SESSION['donken']['username']))
{
    header('Location: index.php');
    die();
}

$config = (array) json_decode(file_get_contents('priv/config.json'));

if(!isset($config['swish_number']) || $config['swish_number'] == '')
{
    echo '<div class="alert alert-danger">Inget telefonnumer har angetts för Swish.</div>';
    die();
}

$data = array(
    "format" => "svg",
    "transparent" => true,
    "amount" => array(
        "value" => $_GET['price'],
        "editable" => false
    ),
    "message" => array(
        "value" => "DonkenRun - " . $_GET['name'],
        "editable" => false
    ),"payee" => array(
        "value" => $config['swish_number'],
        "editable" => false
    )
);

$url = 'https://mpc.getswish.net/qrg-swish/api/v1/prefilled';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

header('Content-Type: image/svg-xml');
echo substr(curl_exec($ch), 0, -1);