<?php
require_once 'includes/auth.php';
require_once '../config/database.php';

$pageTitle = "Kelola Kontak";
$db = new Database();

$success = '';
$error = '';

/* ===========================
   AMBIL DATA KONTAK
=========================== */
$row = $db->fetch($db->query("SELECT * FROM site_kontak LIMIT 1"));

if (!$row) {
    // Jika tabel kosong, buat 1 baris default
    $db->query("INSERT INTO site_kontak (alamat, telepon, email, jam_operasional) 
                VALUES ('', '', '', '')");
    $row = $db->fetch($db->query("SELECT * FROM site_kontak LIMIT 1"));
}

/* ===========================
   UPDATE KONTAK
=========================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $alamat = $db->escape($_POST['alamat']);
    $telepon = $db->escape($_POST['telepon']);
    $email = $db->escape($_POST['email']);
    $jam_operasional = $db->escape($_POST['jam_operasional']);

    $sql = "
        UPDATE site_kontak SET 
            alamat = '$alamat',
            telepon = '$telepon',
            email = '$email',
            jam_operasional = '$jam_operasional',
            updated_at = CURRENT_TIMESTAMP
        WHERE id = {$row['id']}
    ";

    if ($db->query($sql)) {
        $success = "Kontak berhasil diperbarui!";
        $row = $db->fetch($db->query("SELECT * FROM site_kontak LIMIT 1"));
    } else {
        $error = "Gagal memperbarui kontak!";
    }
}

include 'includes/header.php';
?>

<!-- ALERT NOTIF -->
<?php if ($success): ?>
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4"><?= $error ?></div>
<?php endif; ?>


<!-- FORM EDIT KONTAK -->
<div class="bg-white rounded-xl shadow-md p-6">
    <h2 class="text-xl font-bold mb-4">Pengaturan Kontak Website</h2>

    <form method="POST" class="space-y-4">

        <div>
            <label class="font-semibold">Alamat</label>
            <textarea name="alamat" rows="3"
                class="w-full border px-4 py-2 rounded-lg"><?= htmlspecialchars($row['alamat']) ?></textarea>
        </div>

        <div>
            <label class="font-semibold">Telepon</label>
            <input type="text" name="telepon"
                class="w-full border px-4 py-2 rounded-lg"
                value="<?= htmlspecialchars($row['telepon']) ?>">
        </div>

        <div>
            <label class="font-semibold">Email</label>
            <input type="email" name="email"
                class="w-full border px-4 py-2 rounded-lg"
                value="<?= htmlspecialchars($row['email']) ?>">
        </div>

        <div>
            <label class="font-semibold">Jam Operasional</label>
            <textarea name="jam_operasional" rows="3"
                class="w-full border px-4 py-2 rounded-lg"><?= htmlspecialchars($row['jam_operasional']) ?></textarea>
        </div>

        <button class="px-6 py-2 bg-blue-600 text-white rounded-lg">
            Simpan Perubahan
        </button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
