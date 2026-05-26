<?php
$nazev_stranky = 'Rozvrh';
require_once '../config.php';
vyzadujRoli('zak');

$db = pripojDB();
$zak_id = $_SESSION['uzivatel_id'];

// Načti VŠECHNY aktivní kroužky pro rozvrh
$sql_vsechny = "SELECT k.*, u.cele_jmeno AS ucitel_jmeno
                FROM krouzky k
                INNER JOIN prihlaseni p ON k.id = p.krouzek_id
                LEFT JOIN uzivatele u ON k.ucitel_id = u.id
                WHERE k.aktivni = 1
                AND p.zak_id = ?
                ORDER BY k.cas_od";
$stmt = mysqli_prepare($db, $sql_vsechny);
mysqli_stmt_bind_param($stmt, 'i', $zak_id);
mysqli_stmt_execute($stmt);
$vsechny = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

mysqli_close($db);

// Organizuj kroužky podle dne
$dny = ['Pondělí', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek'];
$krouzky_dny = [];
foreach ($dny as $den) {
    $krouzky_dny[$den] = [];
}
foreach ($vsechny as $k) {
    if (isset($krouzky_dny[$k['den_tydne']])) {
        $krouzky_dny[$k['den_tydne']][] = $k;
    }
}

// Časy vyučovacích hodin
$hodiny = [
    '1.' => ['08:00', '08:45'],
    '2.' => ['08:55', '09:40'],
    '3.' => ['10:00', '10:45'],
    '4.' => ['10:55', '11:40'],
    '5.' => ['11:50', '12:35'],
    '6.' => ['12:40', '13:30'],
    '7.' => ['13:40', '14:25'],
    '8.' => ['14:35', '15:20'],
    '9.' => ['15:30', '16:15']
];


// Funkce - najde kroužek pro daný den a hodinu
function najdiKrouzek($krouzky, $cas_od, $cas_do)
{
    foreach ($krouzky as $k) {
        $k_od = substr($k['cas_od'], 0, 5);
        $k_do = substr($k['cas_do'], 0, 5);
        // Porovnej jestli se kroužek překrývá s danou hodinou
        if ($k_od < $cas_do && $k_do > $cas_od) {
            return $k;
        }
    }
    return null;
}
?>
<?php include 'hlavicka.php'; ?>

<!-- TABULKOVÝ ROZVRH -->
<div class="karta" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
        <table class="rozvrh-tabulka">
            <thead>
                <tr>
                    <th style="background:#1e293b;">Hodina</th>
                    <?php foreach ($dny as $den): ?>
                        <th><?= $den ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hodiny as $cislo => $casy): ?>
                    <tr>
                        <td class="cas-sloupec">
                            <strong><?= $cislo ?></strong><br>
                            <small><?= $casy[0] ?></small><br>
                            <small><?= $casy[1] ?></small>
                        </td>
                        <?php foreach ($dny as $den):
                            $krouzek = najdiKrouzek($krouzky_dny[$den], $casy[0], $casy[1]);
                            ?>
                            <td>
                                <?php if ($krouzek): ?>
                                    <div class="rozvrh-bunka muj-krouzek">
                                        <div class="bunka-nazev">
                                            <?= ocisti($krouzek['nazev']) ?>
                                        </div>
                                        <div class="bunka-mistnost"><?= ocisti($krouzek['mistnost']) ?></div>
                                        <div style="font-size:11px; color:var(--text-sekundarni); margin-top:2px;">
                                            <?= substr($krouzek['cas_od'], 0, 5) ?>–<?= substr($krouzek['cas_do'], 0, 5) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>




<?php include 'paticka.php'; ?>