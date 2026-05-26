<?php
$nazev_stranky = 'Přihlášení žáků';
require_once '../config.php';
vyzadujRoli('admin');

$db = pripojDB();

$sql = "SELECT u.cele_jmeno, u.uzivatelske_jmeno, u.trida, u.email,
               k.nazev AS krouzek_nazev, k.den_tydne, k.cas_od, k.cas_do, k.mistnost,
               uk.cele_jmeno AS ucitel_jmeno, p.datum_prihlaseni
        FROM prihlaseni p
        JOIN uzivatele u ON p.zak_id = u.id
        JOIN krouzky k ON p.krouzek_id = k.id
        LEFT JOIN uzivatele uk ON k.ucitel_id = uk.id
        WHERE k.aktivni = 1
        ORDER BY k.nazev, u.cele_jmeno";

$prihlaseni = mysqli_fetch_all(mysqli_query($db, $sql), MYSQLI_ASSOC);

mysqli_close($db);
?>
<?php include 'hlavicka.php'; ?>

<?php if (empty($prihlaseni)): ?>
    <div class="prazdny-stav">
        <div class="ikona"></div>
        <h3>Žádná přihlášení</h3>
    </div>
<?php else: ?>
    <div class="tabulka-obal">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Žák</th>
                    <th>Třída</th>
                    <th>Kroužek</th>
                    <th>Den / čas</th>
                    <th>Místo</th>
                    <th>Vedoucí</th>
                    <th>Přihlášen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prihlaseni as $i => $r): ?>
                    <tr>
                        <td style="color:var(--text-sekundarni);"><?= $i + 1 ?></td>
                        <td>
                            <strong><?= ocisti($r['cele_jmeno']) ?></strong><br>
                            <small
                                style="color:var(--text-sekundarni);font-family:monospace;"><?= ocisti($r['uzivatelske_jmeno']) ?></small>
                        </td>
                        <td><?= ocisti($r['trida'] ?? '—') ?></td>
                        <td><span class="badge badge-info"><?= ocisti($r['krouzek_nazev']) ?></span></td>
                        <td style="font-size:13px;">
                            <?= ocisti($r['den_tydne']) ?><br><?= substr($r['cas_od'], 0, 5) ?>–<?= substr($r['cas_do'], 0, 5) ?>
                        </td>
                        <td><?= ocisti($r['mistnost']) ?></td>
                        <td style="font-size:13px;"><?= ocisti($r['ucitel_jmeno'] ?? '—') ?></td>
                        <td style="font-size:12px;color:var(--text-sekundarni);">
                            <?= date('d.m.Y', strtotime($r['datum_prihlaseni'])) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include 'paticka.php'; ?>