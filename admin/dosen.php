<?php
require_once 'includes/auth.php';
require_once '../config/database.php';

$pageTitle = 'Kelola Dosen';
$db = new Database();

$success = '';
$error = '';

/* ===========================
   DEFAULT PAGINATION PUBLIKASI (prevent division by zero)
=========================== */
$pubPerPage = 5; // default publikasi per halaman
$pubPage = isset($_GET['pubPage']) ? max(1, (int)$_GET['pubPage']) : 1;

/* ===========================
   AMBIL JABATAN
=========================== */
$jabatanList = $db->fetchAll(
    $db->query("SELECT * FROM jabatan ORDER BY urutan ASC")
);

/* ===========================
   DELETE DOSEN
=========================== */
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    $q = $db->query("SELECT foto FROM dosen_detail WHERE id=$id LIMIT 1");
    if ($db->numRows($q)) {
        $d = $db->fetch($q);

        if ($d['foto'] && file_exists("../" . $d['foto'])) {
            @unlink("../" . $d['foto']);
        }

        $db->query("DELETE FROM dosen_detail WHERE id=$id");
        $success = "Dosen berhasil dihapus.";
    }
}

/* ===========================
   CREATE / UPDATE DOSEN
=========================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_dosen'])) {

    $id = (int) ($_POST['id'] ?? 0);
    $nama = $db->escape($_POST['nama']);
    $jabatan_id = (int) $_POST['jabatan_id'];
    $deskripsi = $db->escape($_POST['deskripsi']);
    $foto = "";

    if (!$nama || !$jabatan_id) {
        $error = "Nama dan Jabatan wajib diisi!";
    }

    /* UPLOAD FOTO */
    if (!$error && isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {

        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($_FILES['foto']['type'], $allowed)) {
            $error = "Format foto harus JPG/PNG/WebP.";
        } else {
            $dir = "../uploads/dosen/";
            if (!is_dir($dir)) mkdir($dir, 0777, true);

            $fileName = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['foto']['name']);
            $target = $dir . $fileName;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                $foto = "uploads/dosen/" . $fileName;
            } else {
                $error = "Gagal mengupload foto.";
            }
        }
    }

    /* INSERT */
    if (!$error && $id == 0) {
        $sql = "INSERT INTO dosen_detail (nama, jabatan_id, deskripsi, foto)
                VALUES ('$nama', '$jabatan_id', '$deskripsi', '$foto')";
        if ($db->query($sql)) {
            $success = "Dosen berhasil ditambahkan!";
        } else {
            $error = "Gagal menambah dosen!";
        }
    }

    /* UPDATE */
    if (!$error && $id > 0) {
        $old = $db->fetch($db->query("SELECT foto FROM dosen_detail WHERE id=$id"));

        $sql = "UPDATE dosen_detail SET 
                nama='$nama',
                jabatan_id='$jabatan_id',
                deskripsi='$deskripsi'";

        if ($foto) {
            if (!empty($old['foto']) && file_exists("../" . $old['foto'])) @unlink("../" . $old['foto']);
            $sql .= ", foto='$foto'";
        }

        $sql .= " WHERE id=$id";
        if ($db->query($sql)) {
            $success = "Dosen berhasil diperbarui!";
        } else {
            $error = "Gagal update dosen!";
        }
    }
}

/* ===========================
   PAGINATION LIST DOSEN
=========================== */
$perPage = 6;
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

$totalRow = $db->fetch($db->query("SELECT COUNT(*) AS t FROM dosen_detail"));
$total = (int) ($totalRow['t'] ?? 0);
$totalPages = $perPage > 0 ? ceil($total / $perPage) : 1;

$data = $db->fetchAll(
    $db->query("
        SELECT * FROM view_dosen
        LIMIT $perPage OFFSET $offset
    ")
);

/* ===========================
   EDIT FORM
=========================== */
$editData = null;
if (isset($_GET['edit'])) {
    $eid = (int) $_GET['edit'];
    $q = $db->query("SELECT * FROM dosen_detail WHERE id=$eid LIMIT 1");
    if ($db->numRows($q)) $editData = $db->fetch($q);
}

/* ===========================
   P U B L I K A S I
=========================== */

$publikasi = [];
$currentDosen = null;
$totalPubPages = 0; // default

/* Jika user klik "Publikasi" */
if (isset($_GET['publikasi'])) {
    $dosenId = (int) $_GET['publikasi'];

    $currentDosen = $db->fetch(
        $db->query("SELECT * FROM dosen_detail WHERE id=$dosenId LIMIT 1")
    );

    // pastikan $pubPerPage valid
    $pubPerPage = max(1, (int)$pubPerPage);
    $pubPage = isset($_GET['pubPage']) ? max(1, (int)$_GET['pubPage']) : 1;
    $pubOffset = ($pubPage - 1) * $pubPerPage;

    // Hitung total publikasi untuk dosen ini
    $totalPubRow = $db->fetch($db->query("SELECT COUNT(*) AS t FROM dosen_publikasi WHERE dosen_id=$dosenId"));
    $totalPub = (int) ($totalPubRow['t'] ?? 0);

    $totalPubPages = $pubPerPage > 0 ? ceil($totalPub / $pubPerPage) : 1;
    if ($totalPubPages < 1) $totalPubPages = 1;

    // Ambil publikasi sesuai halaman
    $publikasi = $db->fetchAll(
        $db->query("
            SELECT * FROM dosen_publikasi
            WHERE dosen_id=$dosenId
            ORDER BY tahun DESC, id DESC
            LIMIT $pubPerPage OFFSET $pubOffset
        ")
    );
}

/* Tambah / edit publikasi */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_publikasi'])) {

    $pid = (int) ($_POST['pid'] ?? 0);
    $dosen_id = (int) $_POST['dosen_id'];
    $tahun = (int) $_POST['tahun'];
    $judul = $db->escape($_POST['judul']);

    if (!$dosen_id || !$tahun || !$judul) {
        $error = "Lengkapi tahun & judul publikasi.";
    } else {
        if ($pid == 0) {
            $db->query("
                INSERT INTO dosen_publikasi (dosen_id, tahun, judul)
                VALUES ('$dosen_id', '$tahun', '$judul')
            ");
            $success = "Publikasi ditambahkan!";
        } else {
            // optional: update existing publikasi (jika diimplementasikan)
            $db->query("
                UPDATE dosen_publikasi
                SET tahun = '$tahun', judul = '$judul'
                WHERE id = $pid
            ");
            $success = "Publikasi diperbarui!";
        }
    }

    header("Location: dosen.php?publikasi=" . $dosen_id . "&pubPage=" . ($pubPage ?? 1));
    exit;
}

/* Hapus publikasi */
if (isset($_GET['hapusPublikasi'])) {
    $pid = (int) $_GET['hapusPublikasi'];

    $pub = $db->fetch($db->query("SELECT * FROM dosen_publikasi WHERE id=$pid"));
    if ($pub) {
        $db->query("DELETE FROM dosen_publikasi WHERE id=$pid");
        $success = "Publikasi dihapus!";
        header("Location: dosen.php?publikasi=" . $pub['dosen_id'] . "&pubPage=" . ($pubPage ?? 1));
        exit;
    }
}

include 'includes/header.php';
?>

<!-- ALERTS -->
<?php if ($success): ?>
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4"><?= $error ?></div>
<?php endif; ?>


<!-- ===========================
     FORM DOSEN
=========================== -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">

    <h3 class="text-xl font-bold mb-4"><?= $editData ? "Edit Dosen" : "Tambah Dosen" ?></h3>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="save_dosen" value="1">
        <?php if ($editData): ?>
        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        <?php endif; ?>

        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label>Nama *</label>
                <input type="text" name="nama" required class="w-full px-4 py-2 border rounded-lg"
                    value="<?= htmlspecialchars($editData['nama'] ?? '') ?>">
            </div>
        </div>

        <div class="mb-4">
            <label>Jabatan *</label>
            <select name="jabatan_id" required class="w-full px-4 py-2 border rounded-lg">
                <option value="">Pilih Jabatan</option>
                <?php foreach ($jabatanList as $j): ?>
                <option value="<?= $j['id'] ?>" <?= isset($editData) && $editData['jabatan_id']==$j['id'] ? 'selected':'' ?>>
                    <?= $j['nama_jabatan'] ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 border rounded-lg"><?= htmlspecialchars($editData['deskripsi'] ?? '') ?></textarea>
        </div>

        <div class="mb-4">
            <label>Foto</label>
            <input type="file" name="foto" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
        </div>

        <?php if ($editData && $editData['foto']): ?>
        <img src="../<?= $editData['foto'] ?>" class="w-32 h-32 rounded-lg object-cover mb-3">
        <?php endif; ?>

        <button class="px-6 py-2 bg-blue-600 text-white rounded-lg">
            <?= $editData ? "Update" : "Simpan" ?>
        </button>
    </form>

</div>



<!-- ===========================
     LIST DOSEN
=========================== -->
<div class="bg-white rounded-xl shadow-md">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3">Foto</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Jabatan</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                <?php foreach ($data as $row): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <?php if ($row['foto']): ?>
                        <img src="../<?= $row['foto'] ?>" class="w-16 h-16 object-cover rounded">
                        <?php else: ?>
                        <div class="w-16 h-16 bg-gray-300 rounded"></div>
                        <?php endif; ?>
                    </td>

                    <td class="px-4 py-3">
                        <b><?= htmlspecialchars($row['nama']) ?></b><br>
                    </td>

                    <td class="px-4 py-3"><?= htmlspecialchars($row['nama_jabatan']) ?></td>

                    <td class="px-4 py-3 text-center space-x-1">
                        <a href="?edit=<?= $row['id'] ?>" class="px-3 py-1 bg-blue-500 text-white rounded">Edit</a>

                        <a href="?publikasi=<?= $row['id'] ?>"
                           class="px-3 py-1 bg-purple-500 text-white rounded">
                           Publikasi
                        </a>

                        <a href="?delete=<?= $row['id'] ?>" 
                           onclick="return confirm('Hapus dosen?')"
                           class="px-3 py-1 bg-red-500 text-white rounded">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="p-4 flex justify-center space-x-2">
        <?php for ($i=1; $i<=$totalPages; $i++): ?>
        <a href="?page=<?= $i ?>" class="px-3 py-1 rounded-lg <?= $i==$page?'bg-blue-600 text-white':'bg-gray-200' ?>">
            <?= $i ?>
        </a>
        <?php endfor; ?>
    </div>
</div>



<!-- ===========================
     P U B L I K A S I  D O S E N
=========================== -->
<?php if ($currentDosen): ?>
<div class="bg-white rounded-xl shadow-md p-6 mt-6">

    <h2 class="text-xl font-bold mb-4">Publikasi — <?= htmlspecialchars($currentDosen['nama']) ?></h2>

    <form method="POST" enctype="multipart/form-data" class="mb-6">
        <input type="hidden" name="save_publikasi" value="1">
        <input type="hidden" name="dosen_id" value="<?= $currentDosen['id'] ?>">
        <input type="hidden" name="pid" value="0">

        <div class="grid md:grid-cols-3 gap-4 mb-4">
            <div>
                <label>Tahun</label>
                <input type="number" name="tahun" required class="w-full px-4 py-2 border rounded-lg">
            </div>

            <div class="md:col-span-2">
                <label>Judul Publikasi</label>
                <input type="text" name="judul" required class="w-full px-4 py-2 border rounded-lg">
            </div>
        </div>

        <button class="px-6 py-2 bg-purple-600 text-white rounded-lg">Simpan Publikasi</button>
    </form>

    <table class="w-full">
        <thead class="bg-purple-600 text-white">
            <tr>
                <th class="px-4 py-2">Tahun</th>
                <th class="px-4 py-2">Judul</th>
                <th class="px-4 py-2 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            <?php foreach ($publikasi as $p): ?>
            <tr>
                <td class="px-4 py-2"><?= $p['tahun'] ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($p['judul']) ?></td>
                <td class="px-4 py-2 text-center">
                    <a href="?hapusPublikasi=<?= $p['id'] ?>"
                       class="px-3 py-1 bg-red-500 text-white rounded"
                       onclick="return confirm('Hapus publikasi?')">
                       Hapus
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- PAGINATION PUBLIKASI -->
    <div class="p-4 flex justify-center space-x-2">
    <?php for ($i=1; $i<=$totalPubPages; $i++): ?>
        <a href="?publikasi=<?= $currentDosen['id'] ?>&pubPage=<?= $i ?>"
           class="px-3 py-1 rounded-lg <?= ($i == $pubPage ? 'bg-purple-600 text-white' : 'bg-gray-200') ?>">
           <?= $i ?>
        </a>
    <?php endfor; ?>
    </div>

    <a href="dosen.php" class="px-4 py-2 bg-gray-300 rounded-lg mt-4 inline-block">Kembali</a>
</div>
<?php endif; ?>


<?php include 'includes/footer.php'; ?>
