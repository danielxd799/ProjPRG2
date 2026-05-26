<?php
require_once '../config.php';
vyzadujRoli('admin');

?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($nazev_stranky) ? $nazev_stranky . ' – ' : '' ?>Admin – Školní kroužky</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="<?= $tema_trida ?>">

    <div class="rozlozeni">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <div class="text">
                    <h2>Školní kroužky</h2>
                </div>
            </div>
            <nav class="sidebar-nav">
                <a href="dashboard.php"
                    class="<?= (basename($_SERVER['PHP_SELF']) === 'dashboard.php') ? 'aktivni' : '' ?>">
                    <p>Dashboard</p>
                </a>
                <a href="krouzky.php"
                    class="<?= (basename($_SERVER['PHP_SELF']) === 'krouzky.php') ? 'aktivni' : '' ?>">
                    <p>Kroužky</p>
                </a>
                <a href="uzivatele.php"
                    class="<?= (basename($_SERVER['PHP_SELF']) === 'uzivatele.php') ? 'aktivni' : '' ?>">
                    <p>Uživatelé</p>
                </a>
                <a href="prihlaseni.php"
                    class="<?= (basename($_SERVER['PHP_SELF']) === 'prihlaseni.php') ? 'aktivni' : '' ?>">
                    <p>Přihlášení</p>
                </a>
            </nav>
        </aside>

        <div class="hlavni-obsah">
            <header class="header">
                <h1 class="header-nadpis"><?= isset($nazev_stranky) ? $nazev_stranky : 'Dashboard' ?></h1>
                <div class="header-uzivatel">
                    <div class="uzivatel-info">
                        <div class="jmeno"><?= ocisti($_SESSION['cele_jmeno']) ?></div>
                        <div class="role">Administrátor</div>
                    </div>
                    <div class="uzivatel-avatar" style="background:#dc2626;">A</div>
                    <a href="../odhlasit.php" class="btn-odhlasit">Odhlásit</a>
                </div>
            </header>
            <div class="stranka-obsah">