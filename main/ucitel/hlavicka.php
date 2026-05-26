<?php
require_once '../config.php';
vyzadujRoli('ucitel');

?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($nazev_stranky) ? $nazev_stranky . ' – ' : '' ?>Školní kroužky</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="rozlozeni">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <span class="ikona"></span>
                <div class="text">
                    <a href="prehled.php" style="text-decoration: none; color: white;">Školní kroužek</a>
                </div>
            </div>
            <nav class="sidebar-nav">
                <a href="prehled.php"
                    class="<?= (basename($_SERVER['PHP_SELF']) === 'prehled.php') ? 'aktivni' : '' ?>">
                    <p>Přehled</p>
                </a>
                <a href="moje_krouzky.php"
                    class="<?= (basename($_SERVER['PHP_SELF']) === 'moje_krouzky.php') ? 'aktivni' : '' ?>">
                    <p>Moje kroužky</p>
                </a>
                <a href="ucastnici.php"
                    class="<?= (basename($_SERVER['PHP_SELF']) === 'ucastnici.php') ? 'aktivni' : '' ?>">
                    <p>Účastníci</p>
                </a>
                <a href="rozvrh.php" class="<?= (basename($_SERVER['PHP_SELF']) === 'rozvrh.php') ? 'aktivni' : '' ?>">
                    <p>Rozvrh</p>
                </a>
            </nav>
        </aside>

        <div class="hlavni-obsah">
            <header class="header">
                <h1 class="header-nadpis"><?= isset($nazev_stranky) ? $nazev_stranky : 'Přehled' ?></h1>
                <div class="header-uzivatel">
                    <div class="uzivatel-info">
                        <div class="jmeno"><?= ocisti($_SESSION['cele_jmeno']) ?></div>
                        <div class="role">Učitel</div>
                    </div>
                    <div class="uzivatel-avatar" style="background:#7c3aed;">
                        <?= strtoupper(substr($_SESSION['cele_jmeno'], 0, 1)) ?>
                    </div>
                    <a href="../odhlasit.php" class="btn-odhlasit">Odhlásit</a>
                </div>
            </header>
            <div class="stranka-obsah">