<?php
$nazev_stranky = 'Moje kroužky';
require_once '../config.php';
vyzadujRoli('ucitel');

$db = pripojDB();
$ucitel_id = $_SESSION['uzivatel_id'];

$sql = "SELECT k.*, COUNT(p.id) AS pocet_prihlasenych
        FROM krouzky k
        LEFT JOIN prihlaseni p ON k.id = p.krouzek_id
        WHERE k.ucitel_id = ? AND k.aktivni = 1
        GROUP BY k.id
        ORDER BY k.den_tydne, k.cas_od";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, 'i', $ucitel_id);
mysqli_stmt_execute($stmt);
$krouzky = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

mysqli_close($db);
?>
<?php include 'hlavicka.php'; ?>

<?php if (empty($krouzky)): ?>
    <div class="prazdny-stav">
        <div class="ikona"></div>
        <h3>Žádné kroužky</h3>
        <p>Nejsi přiřazen k žádnému kroužku. Kontaktuj administrátora.</p>
    </div>
<?php else: ?>
    <div class="mrizka-krouzku">
        <?php foreach ($krouzky as $k):
            $procent = ($k['max_kapacita'] > 0) ? round(($k['pocet_prihlasenych'] / $k['max_kapacita']) * 100) : 0;
            ?>
            <div class="karta-krouzku">
                <div class="krouzek-nazev"><?= ocisti($k['nazev']) ?></div>
                <p class="krouzek-popis"><?= ocisti($k['popis']) ?></p>

                <div class="krouzek-info">
                    <div class="info-radek">
                        <span class="info-label">Den:</span>
                        <span class="info-hodnota"><?= ocisti($k['den_tydne']) ?></span>
                    </div>
                    <div class="info-radek">
                        <span class="info-label">Čas:</span>
                        <span class="info-hodnota"><?= substr($k['cas_od'], 0, 5) ?> – <?= substr($k['cas_do'], 0, 5) ?></span>
                    </div>
                    <div class="info-radek">
                        <span class="info-label">Místnost:</span>
                        <span class="info-hodnota"><?= ocisti($k['mistnost']) ?></span>
                    </div>
                </div>

                <div class="kapacita-obal">
                    <div class="kapacita-text">
                        <span>Obsazenost</span>
                        <span><strong><?= $k['pocet_prihlasenych'] ?></strong> / <?= $k['max_kapacita'] ?></span>
                    </div>
                    <div class="kapacita-bar">
                        <div class="kapacita-vyplneni <?= ($procent >= 100) ? 'plny' : (($procent >= 80) ? 'skoro-plny' : '') ?>"
                            style="width:<?= min($procent, 100) ?>%"></div>
                    </div>
                </div>

                <a href="ucastnici.php?krouzek=<?= $k['id'] ?>" class="btn btn-sekundarni btn-cely btn-maly"
                    style="text-align:center;">
                    <p>Zobrazit účastníky</p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'paticka.php'; ?>