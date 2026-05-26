<?php
$nazev_stranky = 'Přehled';
require_once '../config.php';
vyzadujRoli('ucitel');

$db = pripojDB();
$ucitel_id = $_SESSION['uzivatel_id'];

// Moje kroužky s přihlášenými
$sql = "SELECT k.*, COUNT(p.id) AS pocet_prihlasenych
        FROM krouzky k
        LEFT JOIN prihlaseni p ON k.id = p.krouzek_id
        WHERE k.ucitel_id = ? AND k.aktivni = 1
        GROUP BY k.id
        ORDER BY k.den_tydne, k.cas_od";
$stmt2 = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt2, 'i', $ucitel_id);
mysqli_stmt_execute($stmt2);
$krouzky = mysqli_fetch_all(mysqli_stmt_get_result($stmt2), MYSQLI_ASSOC);

mysqli_close($db);
?>
<?php include 'hlavicka.php'; ?>

<!-- MÉ KROUŽKY -->
<h2 style="font-size:17px; font-weight:700; margin-bottom:14px;">Moje kroužky</h2>

<?php if (empty($krouzky)): ?>
    <div class="prazdny-stav">
        <div class="ikona"></div>
        <h3>Žádné kroužky</h3>
        <p>Nemáš přiřazeny žádné kroužky. Kontaktuj administrátora.</p>
    </div>
<?php else: ?>
    <div class="tabulka-obal">
        <table>
            <thead>
                <tr>
                    <th>Název kroužku</th>
                    <th>Den a čas</th>
                    <th>Místo</th>
                    <th>Přihlášení</th>
                    <th>Akce</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($krouzky as $k): ?>
                    <tr>
                        <td><strong><?= ocisti($k['nazev']) ?></strong></td>
                        <td><?= ocisti($k['den_tydne']) ?>, <?= substr($k['cas_od'], 0, 5) ?>–<?= substr($k['cas_do'], 0, 5) ?></td>
                        <td><?= ocisti($k['mistnost']) ?></td>
                        <td>
                            <span class="badge badge-info"><?= $k['pocet_prihlasenych'] ?> / <?= $k['max_kapacita'] ?></span>
                        </td>
                        <td>
                            <a href="ucastnici.php" class="btn btn-sekundarni btn-maly">
                                <p>Účastníci</p>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include 'paticka.php'; ?>