<?php
$nazev_stranky = 'Dashboard';
require_once '../config.php';
vyzadujRoli('admin');

$db = pripojDB();

// Přehled kroužků s počty
$sql = "SELECT k.*, u.cele_jmeno AS ucitel_jmeno, COUNT(p.id) AS pocet
        FROM krouzky k
        LEFT JOIN uzivatele u ON k.ucitel_id = u.id
        LEFT JOIN prihlaseni p ON k.id = p.krouzek_id
        WHERE k.aktivni = 1
        GROUP BY k.id ORDER BY k.den_tydne, k.cas_od";
$krouzky = mysqli_fetch_all(mysqli_query($db, $sql), MYSQLI_ASSOC);

mysqli_close($db);
?>
<?php include 'hlavicka.php'; ?>


<!-- PŘEHLED KROUŽKŮ -->
<h2 style="font-size:17px;font-weight:700;margin-bottom:14px;">Přehled všech kroužků</h2>
<div class="tabulka-obal">
    <table>
        <thead>
            <tr>
                <th>Název</th>
                <th>Den a čas</th>
                <th>Místo</th>
                <th>Vedoucí</th>
                <th>Přihlášení</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($krouzky as $k):
                $procent = $k['max_kapacita'] > 0 ? round($k['pocet'] / $k['max_kapacita'] * 100) : 0;
                ?>
                <tr>
                    <td><strong><?= ocisti($k['nazev']) ?></strong></td>
                    <td><?= ocisti($k['den_tydne']) ?>, <?= substr($k['cas_od'], 0, 5) ?>–<?= substr($k['cas_do'], 0, 5) ?></td>
                    <td><?= ocisti($k['mistnost']) ?></td>
                    <td><?= ocisti($k['ucitel_jmeno'] ?? '—') ?></td>
                    <td>
                        <span
                            class="badge <?= $procent >= 100 ? 'badge-chyba' : ($procent >= 80 ? 'badge-varovani' : 'badge-info') ?>">
                            <?= $k['pocet'] ?> / <?= $k['max_kapacita'] ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'paticka.php'; ?>