<?php
$nazev_stranky = 'Účastníci';

require_once '../config.php';

vyzadujRoli('ucitel');

$db = pripojDB();
$ucitel_id = $_SESSION['uzivatel_id'];

$sql = "
    SELECT 
        u.id,
        u.cele_jmeno,
        u.trida,
        u.uzivatelske_jmeno,
        k.nazev AS krouzek_nazev,
        p.datum_prihlaseni
    FROM prihlaseni p
    INNER JOIN uzivatele u 
        ON p.zak_id = u.id
    INNER JOIN krouzky k 
        ON p.krouzek_id = k.id
    WHERE k.ucitel_id = ?
    ORDER BY k.nazev ASC, u.cele_jmeno ASC
";

$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $ucitel_id);
mysqli_stmt_execute($stmt);

$vysledek = mysqli_stmt_get_result($stmt);

$ucastnici = [];

while ($radek = mysqli_fetch_assoc($vysledek)) {
    $ucastnici[] = $radek;
}

mysqli_stmt_close($stmt);
mysqli_close($db);
?>

<?php include 'hlavicka.php'; ?>

<!-- TABULKA ÚČASTNÍKŮ -->
<?php if (empty($ucastnici)): ?>
    <div class="prazdny-stav">
        <h3>Žádní účastníci</h3>
        <p>V tomto kroužku zatím nikdo není přihlášen.</p>
    </div>
<?php else: ?>

    <div style="margin-bottom:10px; font-size:13px; color:var(--text-sekundarni);">
        Celkem: <strong><?= count($ucastnici) ?></strong> žáků
    </div>

    <div class="tabulka-obal">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jméno</th>
                    <th>Třída</th>
                    <th>Uživatelské jméno</th>
                    <th>Kroužek</th>
                    <th>Přihlášen od</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($ucastnici as $i => $u): ?>
                    <tr>
                        <td style="color:var(--text-sekundarni);">
                            <?= $i + 1 ?>
                        </td>

                        <td>
                            <strong><?= ocisti($u['cele_jmeno']) ?></strong>
                        </td>

                        <td>
                            <?= ocisti($u['trida'] ?? '–') ?>
                        </td>

                        <td style="font-family:monospace; font-size:13px;">
                            <?= ocisti($u['uzivatelske_jmeno']) ?>
                        </td>

                        <td>
                            <span class="badge badge-info">
                                <?= ocisti($u['krouzek_nazev']) ?>
                            </span>
                        </td>

                        <td style="font-size:13px;">
                            <?= date('d.m.Y', strtotime($u['datum_prihlaseni'])) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>

<?php include 'paticka.php'; ?>