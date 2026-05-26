<?php
// Nastavení databáze 
define('DB_HOST', 'localhost');
define('DB_NAME', 'if0_41951673_db_school');
define('DB_USER', 'root');      
define('DB_PASS', '');          

// Připojení k databázi
function pripojDB() {
    $spojeni = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$spojeni) {
        die("Chyba připojení k databázi: " . mysqli_connect_error());
    }
    mysqli_set_charset($spojeni, 'utf8mb4');
    return $spojeni;
}

// Spustí session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Funkce pro přesměrování
function presmeruj($url) {
    header("Location: " . $url);
    exit();
}

// Funkce pro ošetření vstupu (ochrana před XSS)
function ocisti($text) {
    return htmlspecialchars(strip_tags(trim($text)));
}

// Zkontroluj jestli je uživatel přihlášen
function jePrihlaseny() {
    return isset($_SESSION['uzivatel_id']);
}

// Zkontroluj roli uživatele
function jeRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

// Ochrana stránek - přesměruje nepřihlášené na login
function vyžadujPrihlaseni() {
    if (!jePrihlaseny()) {
        presmeruj('index.php');
    }
}

// Ochrana stránek pro konkrétní roli
function vyzadujRoli($role) {
    vyžadujPrihlaseni();
    if (!jeRole($role)) {
        presmeruj('index.php');
    }
}
?>
