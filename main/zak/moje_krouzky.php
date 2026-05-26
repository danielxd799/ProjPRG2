<?php
$nazev_stranky = 'Moje kroužky';
require_once '../config.php';
vyzadujRoli('zak');

$db = pripojDB();
$zak_id = $_SESSION['uzivatel_id'];

// Načti kroužky do kterých je žák přihlášen
$sql = "SELECT k.*, u.cele_jmeno AS ucitel_jmeno, p.datum_prihlaseni
        FROM prihlaseni p
        JOIN krouzky k ON p.krouzek_id = k.id
        LEFT JOIN uzivatele u ON k.ucitel_id = u.id
        WHERE p.zak_id = ? AND k.aktivni = 1
        ORDER BY k.den_tydne, k.cas_od";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, 'i', $zak_id);
mysqli_stmt_execute($stmt);
$moje = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

mysqli_close($db);

// Pořadí dní
$dny_poradi = ['Pondělí' => 1, 'Úterý' => 2, 'Středa' => 3, 'Čtvrtek' => 4, 'Pátek' => 5];
?>
<?php include 'hlavicka.php'; ?>

<?php if (empty($moje)): ?>
    <div class="prazdny-stav">
        <div class="ikona"></div>
        <h3>Zatím žádné kroužky</h3>
        <p>Ještě nejsi přihlášen do žádného kroužku.</p>
        <a href="krouzky.php" class="btn btn-primarni" style="margin-top:16px; display:inline-flex;">
            Prohlédnout nabídku kroužků
        </a>
    </div>
<?php else: ?>
    <div class="mrizka-krouzku">
        <?php foreach ($moje as $k): ?>
            <div class="karta-krouzku prihlaseny">
                <div>
                    <div class="krouzek-nazev"><?= ocisti($k['nazev']) ?></div>
                    <span class="badge badge-uspech" style="margin-top:4px;">✓ Přihlášen</span>
                </div>

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

                <div class="krouzek-ucitel">
                    <?= ocisti($k['ucitel_jmeno'] ?? 'Neurčeno') ?>
                </div>

                <div style="font-size:11px; color:var(--text-sekundarni); margin-top:4px;">
                    Přihlášen: <?= date('d.m.Y', strtotime($k['datum_prihlaseni'])) ?>
                </div>

                <!-- Odhlásit se -->
                <form method="POST" action="krouzky.php">
                    <input type="hidden" name="krouzek_id" value="<?= $k['id'] ?>">
                    <input type="hidden" name="akce" value="odhlasit">
                    <button type="submit" class="btn btn-nebezpeci btn-cely btn-maly"
                        onclick="return confirm('Opravdu se chceš odhlásit z kroužku <?= addslashes(ocisti($k['nazev'])) ?>?')">
                        ✕ Odhlásit se
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'paticka.php'; ?>