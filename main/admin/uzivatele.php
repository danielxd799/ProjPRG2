<?php
$nazev_stranky = 'Uživatelé';
require_once '../config.php';
vyzadujRoli('admin');

$db = pripojDB();
$zprava = '';
$typ_zpravy = '';

// Přidání nového uživatele
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jmeno = ocisti($_POST['uzivatelske_jmeno'] ?? '');
    $cele = ocisti($_POST['cele_jmeno'] ?? '');
    $email = ocisti($_POST['email'] ?? '');
    $role = ocisti($_POST['role'] ?? 'zak');
    $trida = ocisti($_POST['trida'] ?? '');
    $heslo = $_POST['heslo'] ?? '';

    if ($jmeno && $cele && $heslo) {
        $hash = password_hash($heslo, PASSWORD_DEFAULT);
        $sql = "INSERT INTO uzivatele (uzivatelske_jmeno,heslo,cele_jmeno,email,role,trida) VALUES (?,?,?,?,?,?)";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssss', $jmeno, $hash, $cele, $email, $role, $trida);
        if (mysqli_stmt_execute($stmt)) {
            $zprava = 'Uživatel byl přidán.';
            $typ_zpravy = 'uspech';
        } else {
            $zprava = 'Chyba: uživatelské jméno již existuje.';
            $typ_zpravy = 'chyba';
        }
    } else {
        $zprava = 'Vyplň jméno, celé jméno a heslo.';
        $typ_zpravy = 'chyba';
    }
}

// Smazání uživatele
if (isset($_GET['smazat']) && is_numeric($_GET['smazat'])) {
    $sid = intval($_GET['smazat']);
    if ($sid != $_SESSION['uzivatel_id']) {
        $stmt = mysqli_prepare($db, "DELETE FROM uzivatele WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'i', $sid);
        mysqli_stmt_execute($stmt);
        $zprava = 'Uživatel byl smazán.';
        $typ_zpravy = 'uspech';
    }
}

// Všichni uživatelé
$uzivatele = mysqli_fetch_all(
    mysqli_query($db, "SELECT * FROM uzivatele ORDER BY role, cele_jmeno"),
    MYSQLI_ASSOC
);

mysqli_close($db);

?>
<?php include 'hlavicka.php'; ?>

<?php if ($zprava): ?>
    <div class="zprava zprava-<?= $typ_zpravy ?>"><?= $typ_zpravy === 'uspech' ? '' : '' ?><?= $zprava ?></div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start;">

    <!-- PŘIDAT UŽIVATELE -->
    <div class="karta">
        <h2 style="font-size:15px;font-weight:700;margin-bottom:14px;">Přidat uživatele</h2>
        <form method="POST">
            <div class="formular-skupina">
                <label>Uživatelské jméno</label>
                <input type="text" name="uzivatelske_jmeno" required placeholder="uzivatel123">
            </div>
            <div class="formular-skupina">
                <label>Celé jméno</label>
                <input type="text" name="cele_jmeno" required placeholder="Jan Novák">
            </div>
            <div class="formular-skupina">
                <label>E-mail</label>
                <input type="email" name="email" placeholder="jan@skola.cz">
            </div>
            <div class="formular-skupina">
                <label>Heslo</label>
                <input type="password" name="heslo" required placeholder="Heslo">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div class="formular-skupina">
                    <label>Role *</label>
                    <select name="role">
                        <option value="zak">Žák</option>
                        <option value="ucitel">Učitel</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="formular-skupina">
                    <label>Třída</label>
                    <input type="text" name="trida" placeholder="3.A">
                </div>
            </div>
            <button type="submit" class="btn btn-primarni btn-cely">Přidat</button>
        </form>
    </div>

    <!-- SEZNAM UŽIVATELŮ -->
    <div>
        <div class="tabulka-obal">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jméno</th>
                        <th>Login</th>
                        <th>Role</th>
                        <th>Třída</th>
                        <th>Smazání</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($uzivatele as $i => $u): ?>
                        <tr>
                            <td style="color:var(--text-sekundarni);"><?= $i + 1 ?></td>
                            <td><strong><?= ocisti($u['cele_jmeno']) ?></strong><br>
                                <small style="color:var(--text-sekundarni);"><?= ocisti($u['email'] ?? '') ?></small>
                            </td>
                            <td style="font-family:monospace;font-size:13px;"><?= ocisti($u['uzivatelske_jmeno']) ?></td>
                            <td>
                                <?php
                                $role_labels = [
                                    'zak' => ['badge-uspech', 'Žák'],
                                    'ucitel' => ['badge-info', 'Učitel'],
                                    'admin' => ['badge-varovani', 'Admin']
                                ];

                                $rl = $role_labels[$u['role']] ?? ['badge-info', $u['role']];
                                ?>
                                <span class="badge <?= htmlspecialchars($rl[0]) ?>">
                                    <?= htmlspecialchars($rl[1]) ?>
                                </span>
                            </td>
                            <td><?= ocisti($u['trida'] ?? '—') ?></td>
                            <td>
                                <?php if ($u['id'] != $_SESSION['uzivatel_id']): ?>
                                    <a href="uzivatele.php?smazat=<?= $u['id'] ?>" class="btn btn-del btn-maly"
                                        onclick="return confirm('Smazat uživatele <?= addslashes(ocisti($u['cele_jmeno'])) ?>?')">Smazat</a>
                                <?php else: ?>
                                    <span style="font-size:12px;color:var(--text-sekundarni);">Ty</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'paticka.php'; ?>