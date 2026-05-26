<?php
require_once 'config.php';

// Pokud je uživatel přihlášen, přesměruj ho
if (jePrihlaseny()) {
    switch ($_SESSION['role']) {
        case 'zak':
            presmeruj('zak/krouzky.php');
            break;
        case 'ucitel':
            presmeruj('ucitel/prehled.php');
            break;
        case 'admin':
            presmeruj('admin/dashboard.php');
            break;
    }
}

$chyba = '';

// Zpracování formuláře přihlášení
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jmeno = ocisti($_POST['uzivatelske_jmeno'] ?? '');
    $heslo = $_POST['heslo'] ?? '';

    if (empty($jmeno) || empty($heslo)) {
        $chyba = 'Vyplň prosím všechna pole.';
    } else {
        $db = pripojDB();
        $sql = "SELECT id, uzivatelske_jmeno, heslo, cele_jmeno, role, tema FROM uzivatele WHERE uzivatelske_jmeno = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, 's', $jmeno);
        mysqli_stmt_execute($stmt);
        $vysledek = mysqli_stmt_get_result($stmt);
        $uzivatel = mysqli_fetch_assoc($vysledek);

        if ($uzivatel && password_verify($heslo, $uzivatel['heslo'])) {
            $_SESSION['uzivatel_id'] = $uzivatel['id'];
            $_SESSION['uzivatelske_jmeno'] = $uzivatel['uzivatelske_jmeno'];
            $_SESSION['cele_jmeno'] = $uzivatel['cele_jmeno'];
            $_SESSION['role'] = $uzivatel['role'];
            $_SESSION['tema'] = $uzivatel['tema'] ?? 'svetly';

            switch ($uzivatel['role']) {
                case 'zak':
                    presmeruj('zak/krouzky.php');
                    break;
                case 'ucitel':
                    presmeruj('ucitel/prehled.php');
                    break;
                case 'admin':
                    presmeruj('admin/dashboard.php');
                    break;
            }
        } else {
            $chyba = 'Špatné uživatelské jméno nebo heslo.';
        }
        mysqli_close($db);
    }
}
?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Školní kroužky – Přihlášení</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body class="login-page">

    <div class="login-obal">
        <div class="login-karta">
            <div class="login-logo">
                <div class="logo-ikona"></div>
                <h1>Školní kroužky</h1>
            </div>

            <?php if ($chyba): ?>
                <div class="chybova-zprava"><?= $chyba ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php" class="login-formular">
                <div class="pole">
                    <label for="uzivatelske_jmeno">Uživatelské jméno</label>
                    <input type="text" id="uzivatelske_jmeno" name="uzivatelske_jmeno"
                        placeholder="Zadej uživatelské jméno"
                        value="<?= isset($_POST['uzivatelske_jmeno']) ? ocisti($_POST['uzivatelske_jmeno']) : '' ?>"
                        required autofocus>
                </div>
                <div class="pole">
                    <label for="heslo">Heslo</label>
                    <input type="password" id="heslo" name="heslo" placeholder="Zadej heslo" required>
                </div>
                <button type="submit" class="btn-prihlasit">Přihlásit se</button>
            </form>


        </div>
    </div>


</body>

</html>