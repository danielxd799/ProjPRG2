<?php
// Tento soubor se vkládá do žákovských stránek
require_once '../config.php';
vyzadujRoli('zak');

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
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <div class="text">
                    <a href="krouzky.php" style="color: white; text-decoration: none;">Školní kroužky</a>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="krouzky.php"
                    class="<?= (basename($_SERVER['PHP_SELF']) === 'krouzky.php') ? 'aktivni' : '' ?>">
                    <p>Kroužky</p>
                </a>
                <a href="moje_krouzky.php"
                    class="<?= (basename($_SERVER['PHP_SELF']) === 'moje_krouzky.php') ? 'aktivni' : '' ?>">
                    <p>Moje kroužky</p>
                </a>
                <a href="rozvrh.php" class="<?= (basename($_SERVER['PHP_SELF']) === 'rozvrh.php') ? 'aktivni' : '' ?>">
                    <p>Rozvrh</p>
                </a>
            </nav>
        </aside>

        <div class="hlavni-obsah">
            <!-- HEADER -->
            <header class="header">
                <h1 class="header-nadpis"><?= isset($nazev_stranky) ? $nazev_stranky : 'Kroužky' ?></h1>
                <div class="header-uzivatel">
                    <div class="uzivatel-info">
                        <div class="jmeno"><?= ocisti($_SESSION['cele_jmeno']) ?></div>
                        <div class="role">Žák</div>
                    </div>
                    <div class="uzivatel-avatar">
                        <?= strtoupper(substr($_SESSION['cele_jmeno'], 0, 1)) ?>
                    </div>
                    <a href="../odhlasit.php" class="btn-odhlasit" title="Odhlásit se">
                        <p>Odhlásit</p>
                    </a>
                </div>
            </header>

            <div class="stranka-obsah">