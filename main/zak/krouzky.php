<?php
$nazev_stranky = 'Kroužky';
require_once '../config.php';
vyzadujRoli('zak');

$db = pripojDB();
$zprava = '';
$typ_zpravy = '';

// Přihlášení / odhlášení z kroužku
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $krouzek_id = intval($_POST['krouzek_id'] ?? 0);
    $akce = $_POST['akce'] ?? '';
    $zak_id = $_SESSION['uzivatel_id'];

    if ($akce === 'prihlasit' && $krouzek_id > 0) {
        // Zkontroluj kapacitu
        $sql_kap = "SELECT k.max_kapacita, COUNT(p.id) as pocet FROM krouzky k LEFT JOIN prihlaseni p ON k.id = p.krouzek_id WHERE k.id = ?";
        $stmt = mysqli_prepare($db, $sql_kap);
        mysqli_stmt_bind_param($stmt, 'i', $krouzek_id);
        mysqli_stmt_execute($stmt);
        $kapacita = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if ($kapacita['pocet'] >= $kapacita['max_kapacita']) {
            $zprava = 'Kroužek je již plný.';
            $typ_zpravy = 'chyba';
        } else {
            $sql_ins = "INSERT IGNORE INTO prihlaseni (zak_id, krouzek_id) VALUES (?, ?)";
            $stmt2 = mysqli_prepare($db, $sql_ins);
            mysqli_stmt_bind_param($stmt2, 'ii', $zak_id, $krouzek_id);
            if (mysqli_stmt_execute($stmt2) && mysqli_stmt_affected_rows($stmt2) > 0) {
                $zprava = 'Úspěšně jsi se přihlásil do kroužku!';
                $typ_zpravy = 'uspech';
            } else {
                $zprava = 'Do tohoto kroužku jsi již přihlášen.';
                $typ_zpravy = 'info';
            }
        }
    } elseif ($akce === 'odhlasit' && $krouzek_id > 0) {
        $sql_del = "DELETE FROM prihlaseni WHERE zak_id = ? AND krouzek_id = ?";
        $stmt = mysqli_prepare($db, $sql_del);
        mysqli_stmt_bind_param($stmt, 'ii', $zak_id, $krouzek_id);
        mysqli_stmt_execute($stmt);
        $zprava = 'Byl jsi odhlášen z kroužku.';
        $typ_zpravy = 'info';
    }
}

// Načti všechny kroužky s počtem přihlášených a info jestli je přihlášen aktuální žák
$zak_id = $_SESSION['uzivatel_id'];
$sql = "SELECT k.*, 
        u.cele_jmeno AS ucitel_jmeno,
        COUNT(DISTINCT p.id) AS pocet_prihlasenych,
        MAX(CASE WHEN p.zak_id = ? THEN 1 ELSE 0 END) AS jsem_prihlaseny
        FROM krouzky k
        LEFT JOIN uzivatele u ON k.ucitel_id = u.id
        LEFT JOIN prihlaseni p ON k.id = p.krouzek_id
        WHERE k.aktivni = 1
        GROUP BY k.id
        ORDER BY k.den_tydne, k.cas_od";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, 'i', $zak_id);
mysqli_stmt_execute($stmt);
$krouzky = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

mysqli_close($db);

// Pořadí dní pro hezké zobrazení
$dny_poradi = ['Pondělí' => 1, 'Úterý' => 2, 'Středa' => 3, 'Čtvrtek' => 4, 'Pátek' => 5];
?>
<?php include 'hlavicka.php'; ?>

<?php if ($zprava): ?>
    <div class="zprava zprava-<?= $typ_zpravy ?>">
        <?= ($typ_zpravy === 'uspech') ? '' : (($typ_zpravy === 'chyba') ? '' : '') ?>
        <?= ocisti($zprava) ?>
    </div>
<?php endif; ?>

<?php if (empty($krouzky)): ?>
    <div class="prazdny-stav">
        <div class="ikona"></div>
        <h3>Žádné kroužky</h3>
        <p>Momentálně nejsou k dispozici žádné kroužky.</p>
    </div>
<?php else: ?>
    <div class="mrizka-krouzku">
        <?php foreach ($krouzky as $k):
            $pocet = $k['pocet_prihlasenych'];
            $max = $k['max_kapacita'];
            $procent = ($max > 0) ? round(($pocet / $max) * 100) : 0;
            $bar_trida = ($procent >= 100) ? 'plny' : (($procent >= 80) ? 'skoro-plny' : '');
            $je_prihlaseny = $k['jsem_prihlaseny'];
            $karta_trida = $je_prihlaseny ? 'prihlaseny' : (($pocet >= $max) ? 'plny' : '');
            ?>
            <div class="karta-krouzku <?= $karta_trida ?>">
                <div>
                    <div class="krouzek-nazev"><?= ocisti($k['nazev']) ?></div>
                    <?php if ($je_prihlaseny): ?>
                        <span class="badge badge-uspech" style="margin-top:4px;">✓ Přihlášen</span>
                    <?php elseif ($pocet >= $max): ?>
                        <span class="badge badge-chyba" style="margin-top:4px;">Plný</span>
                    <?php endif; ?>
                </div>

                <p class="krouzek-popis"><?= ocisti($k['popis']) ?></p>

                <div class="krouzek-info">
                    <div class="info-radek">
                        <span class="info-label">Den:</span>
                        <span class="info-hodnota"><?= ocisti($k['den_tydne']) ?></span>
                    </div>

                    <div class="info-radek">
                        <span class="info-label">Čas:</span>
                        <span class="info-hodnota">
                            <?= substr($k['cas_od'], 0, 5) ?> – <?= substr($k['cas_do'], 0, 5) ?>
                        </span>
                    </div>

                    <div class="info-radek">
                        <span class="info-label">Místnost:</span>
                        <span class="info-hodnota"><?= ocisti($k['mistnost']) ?></span>
                    </div>
                </div>

                <div class="kapacita-obal">
                    <div class="kapacita-text">
                        <span>Obsazenost</span>
                        <span><strong><?= $pocet ?></strong> / <?= $max ?> žáků</span>
                    </div>
                    <div class="kapacita-bar">
                        <div class="kapacita-vyplneni <?= $bar_trida ?>" style="width: <?= min($procent, 100) ?>%"></div>
                    </div>
                </div>

                <div class="krouzek-ucitel">
                    <?= ocisti($k['ucitel_jmeno'] ?? 'Neurčeno') ?>
                </div>

                <!-- Tlačítko přihlásit / odhlásit -->
                <form method="POST" action="krouzky.php">
                    <input type="hidden" name="krouzek_id" value="<?= $k['id'] ?>">
                    <?php if ($je_prihlaseny): ?>
                        <input type="hidden" name="akce" value="odhlasit">
                        <button type="submit" class="btn btn-nebezpeci btn-cely btn-maly">
                            Odhlásit se z kroužku
                        </button>
                    <?php elseif ($pocet < $max): ?>
                        <input type="hidden" name="akce" value="prihlasit">
                        <button type="submit" class="btn btn-primarni btn-cely btn-maly">
                            Přihlásit se
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-sekundarni btn-cely btn-maly" disabled>
                            Kroužek je plný
                        </button>
                    <?php endif; ?>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'paticka.php'; ?>