<?php
$nazev_stranky = 'Správa kroužků';
require_once '../config.php';
vyzadujRoli('admin');

$db = pripojDB();
$zprava = '';
$typ_zpravy = '';

// ---- SMAZÁNÍ KROUŽKU ----
if (isset($_GET['smazat']) && is_numeric($_GET['smazat'])) {
    $id = intval($_GET['smazat']);
    $stmt = mysqli_prepare($db, "UPDATE krouzky SET aktivni=0 WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $zprava = 'Kroužek byl smazán.';
    $typ_zpravy = 'uspech';
}

// ---- ULOŽENÍ FORMULÁŘE (přidat / upravit) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nazev = ocisti($_POST['nazev'] ?? '');
    $popis = ocisti($_POST['popis'] ?? '');
    $ucitel_id = intval($_POST['ucitel_id'] ?? 0) ?: null;
    $max_kap = intval($_POST['max_kapacita'] ?? 20);
    $den = ocisti($_POST['den_tydne'] ?? '');
    $cas_od = ocisti($_POST['cas_od'] ?? '');
    $cas_do = ocisti($_POST['cas_do'] ?? '');
    $mistnost = ocisti($_POST['mistnost'] ?? '');
    $edit_id = intval($_POST['edit_id'] ?? 0);

    if ($nazev && $den && $cas_od && $cas_do && $mistnost) {

        if ($edit_id > 0) {

            $sql = "UPDATE krouzky SET nazev=?,popis=?,ucitel_id=?,max_kapacita=?,den_tydne=?,cas_od=?,cas_do=?,mistnost=? WHERE id=?";
            $stmt = mysqli_prepare($db, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                'ssiissssi',
                $nazev,
                $popis,
                $ucitel_id,
                $max_kap,
                $den,
                $cas_od,
                $cas_do,
                $mistnost,
                $edit_id
            );

            mysqli_stmt_execute($stmt);
            $zprava = 'Kroužek byl upraven.';

        } else {

            $sql = "INSERT INTO krouzky (nazev,popis,ucitel_id,max_kapacita,den_tydne,cas_od,cas_do,mistnost)
                    VALUES (?,?,?,?,?,?,?,?)";

            $stmt = mysqli_prepare($db, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                'ssiissss',
                $nazev,
                $popis,
                $ucitel_id,
                $max_kap,
                $den,
                $cas_od,
                $cas_do,
                $mistnost
            );

            mysqli_stmt_execute($stmt);
            $zprava = 'Kroužek byl přidán.';
        }

        $typ_zpravy = 'uspech';
    }
}

// ---- NAČTI KROUŽEK PRO ÚPRAVU ----
$edit_krouzek = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $eid = intval($_GET['edit']);
    $stmt = mysqli_prepare($db, "SELECT * FROM krouzky WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'i', $eid);
    mysqli_stmt_execute($stmt);
    $edit_krouzek = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

// ---- NAČTI VŠECHNY KROUŽKY ----
$sql = "SELECT k.*, u.cele_jmeno AS ucitel_jmeno, COUNT(p.id) AS pocet
        FROM krouzky k
        LEFT JOIN uzivatele u ON k.ucitel_id = u.id
        LEFT JOIN prihlaseni p ON k.id = p.krouzek_id
        WHERE k.aktivni = 1
        GROUP BY k.id ORDER BY k.den_tydne, k.cas_od";
$krouzky = mysqli_fetch_all(mysqli_query($db, $sql), MYSQLI_ASSOC);

// ---- NAČTI UČITELE PRO SELECT ----
$ucitele = mysqli_fetch_all(mysqli_query($db, "SELECT id, cele_jmeno FROM uzivatele WHERE role='ucitel' ORDER BY cele_jmeno"), MYSQLI_ASSOC);

$dny = ['Pondělí', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek'];

mysqli_close($db);
?>
<?php include 'hlavicka.php'; ?>


<?php if ($zprava): ?>
    <div class="zprava zprava-<?= $typ_zpravy ?>">
        <?= $typ_zpravy === 'uspech' ? '' : '' ?>     <?= $zprava ?>
    </div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 1.6fr;gap:20px;align-items:start;">

    <!-- FORMULÁŘ PŘIDAT / UPRAVIT -->
    <div class="karta">
        <h2 style="font-size:16px;font-weight:700;margin-bottom:16px;">
            <?= $edit_krouzek ? 'Upravit kroužek' : 'Přidat nový kroužek' ?>
        </h2>

        <form method="POST" action="krouzky.php">
            <input type="hidden" name="edit_id" value="<?= $edit_krouzek ? $edit_krouzek['id'] : 0 ?>">

            <div class="formular-skupina">
                <label>Název kroužku</label>
                <input type="text" name="nazev" required maxlength="100"
                    value="<?= ocisti($edit_krouzek['nazev'] ?? '') ?>" placeholder="Název kroužku">
            </div>

            <div class="formular-skupina">
                <label>Popis</label>
                <textarea name="popis" rows="3"
                    placeholder="Popis kroužku..."><?= ocisti($edit_krouzek['popis'] ?? '') ?></textarea>
            </div>

            <div class="formular-skupina">
                <label>Vedoucí učitel</label>
                <select name="ucitel_id">
                    <option value="0">— Nepřiřazen —</option>
                    <?php foreach ($ucitele as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= (($edit_krouzek['ucitel_id'] ?? 0) == $u['id']) ? 'selected' : '' ?>>
                            <?= ocisti($u['cele_jmeno']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div class="formular-skupina">
                    <label>Max. kapacita</label>
                    <input type="number" name="max_kapacita" min="1" max="100" required
                        value="<?= $edit_krouzek['max_kapacita'] ?? 20 ?>">
                </div>
                <div class="formular-skupina">
                    <label>Místnost</label>
                    <input type="text" name="mistnost" required maxlength="20"
                        value="<?= ocisti($edit_krouzek['mistnost'] ?? '') ?>" placeholder="PC1, GYM...">
                </div>
            </div>

            <div class="formular-skupina">
                <label>Den v týdnu</label>
                <select name="den_tydne" required>
                    <?php foreach ($dny as $d): ?>
                        <option value="<?= $d ?>" <?= (($edit_krouzek['den_tydne'] ?? '') === $d) ? 'selected' : '' ?>>
                            <?= $d ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div class="formular-skupina">
                    <label>Čas od</label>
                    <input type="time" name="cas_od" required value="<?= $edit_krouzek['cas_od'] ?? '14:00' ?>">
                </div>
                <div class="formular-skupina">
                    <label>Čas do</label>
                    <input type="time" name="cas_do" required value="<?= $edit_krouzek['cas_do'] ?? '15:30' ?>">
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:6px;">
                <button type="submit" class="btn btn-primarni">
                    <?= $edit_krouzek ? 'Uložit změny' : 'Přidat kroužek' ?>
                </button>
                <?php if ($edit_krouzek): ?>
                    <a href="krouzky.php" class="btn btn-sekundarni">Zrušit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- SEZNAM KROUŽKŮ -->
    <div>
        <h2 style="font-size:16px;font-weight:700;margin-bottom:14px;">Všechny kroužky (<?= count($krouzky) ?>)</h2>
        <?php if (empty($krouzky)): ?>
            <div class="prazdny-stav">
                <div class="ikona"></div>
                <h3>Žádné kroužky</h3>
            </div>
        <?php else: ?>
            <div style="display:flex;flex-direction:column;gap:10px;">
                <?php foreach ($krouzky as $k):
                    // OPRAVA: název kroužku připravíme do PHP proměnné, aby se nedostal do JS přes HTML atribut
                    $nazev_js = addslashes(ocisti($k['nazev']));
                    ?>
                    <div class="karta" style="padding:14px 16px;">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;">
                            <div style="flex:1;">
                                <div style="font-weight:700;font-size:15px;margin-bottom:4px;"><?= ocisti($k['nazev']) ?></div>
                                <div style="font-size:12px;color:var(--text-sekundarni);margin-bottom:6px;">
                                    <?= ocisti($k['den_tydne']) ?>
                                    <?= substr($k['cas_od'], 0, 5) ?>–<?= substr($k['cas_do'], 0, 5) ?>
                                    &bull; <?= ocisti($k['mistnost']) ?>
                                    &bull; <?= ocisti($k['ucitel_jmeno'] ?? '—') ?>
                                </div>
                                <span class="badge badge-info"><?= $k['pocet'] ?>/<?= $k['max_kapacita'] ?></span>
                            </div>
                            <div style="display:flex;gap:6px;flex-shrink:0;">
                                <a href="krouzky.php?edit=<?= $k['id'] ?>" class="btn btn-primarni btn-maly"
                                    title="Upravit">Upravit</a>
                                <a href="krouzky.php?smazat=<?= $k['id'] ?>" class="btn btn-del btn-maly" title="Smazat"
                                    onclick="return confirm('Opravdu smazat kroužek <?= $nazev_js ?>?')">Smazat</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php include 'paticka.php'; ?>