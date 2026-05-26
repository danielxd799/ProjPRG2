<?php
$nazev_stranky = 'Rozvrh';
require_once '../config.php';
vyzadujRoli('ucitel');

$db = pripojDB();
$ucitel_id = $_SESSION['uzivatel_id'];

$sql = "SELECT k.*, COUNT(p.id) AS pocet_prihlasenych
        FROM krouzky k
        LEFT JOIN prihlaseni p ON k.id = p.krouzek_id
        WHERE k.ucitel_id = ? AND k.aktivni = 1
        GROUP BY k.id ORDER BY k.cas_od";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, 'i', $ucitel_id);
mysqli_stmt_execute($stmt);
$krouzky = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
mysqli_close($db);

$dny = ['Pondělí', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek'];
$krouzky_dny = array_fill_keys($dny, []);
foreach ($krouzky as $k) {
    if (isset($krouzky_dny[$k['den_tydne']])) {
        $krouzky_dny[$k['den_tydne']][] = $k;
    }
}

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

function najdiKrouzek($krouzky, $od, $do)
{
    foreach ($krouzky as $k) {
        if (substr($k['cas_od'], 0, 5) < $do && substr($k['cas_do'], 0, 5) > $od)
            return $k;
    }
    return null;
}
?>
<?php include 'hlavicka.php'; ?>

<div class="karta" style="padding:0; overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="rozvrh-tabulka">
            <thead>
                <tr>
                    <th style="background:#1e293b;">Hodina</th>
                    <?php foreach ($dny as $den): ?>
                        <th><?= $den ?></th><?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hodiny as $c => $casy): ?>
                    <tr>
                        <td class="cas-sloupec"><strong><?= $c ?></strong><br><small><?= $casy[0] ?>–<?= $casy[1] ?></small>
                        </td>
                        <?php foreach ($dny as $den):
                            $k = najdiKrouzek($krouzky_dny[$den], $casy[0], $casy[1]);
                            ?>
                            <td>
                                <?php if ($k): ?>
                                    <div class="rozvrh-bunka">
                                        <div class="bunka-nazev"><?= ocisti($k['nazev']) ?></div>
                                        <div class="bunka-mistnost"><?= ocisti($k['mistnost']) ?></div>
                                        <div style="font-size:11px;color:var(--text-sekundarni);">
                                            <?= $k['pocet_prihlasenych'] ?>/<?= $k['max_kapacita'] ?>
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