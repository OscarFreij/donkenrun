<?php

session_start();
error_reporting(0);

require_once 'priv/pdo.php';
require_once 'priv/twig.php';

if(isset($_SESSION['donken']['username']) || $_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header('Location: index.php');
	die();
}

$connLDAP = ldap_connect("ldaps://ad.ssis.nu") or die('Kunde inte koppla till LDAP.');
$bind = ldap_bind($connLDAP, $_POST['username'] . "@ad.ssis.nu", $_POST['password']);

if(!$bind)
{
    header('Location: index.php?error=Fel lösenord eller användarnamn.');
    die();
}

$_SESSION['donken']['username'] = $_POST['username'];

header('Location: index.php');
