<?php
// CRUD BERITA – DENGAN QUILL EDITOR – FIXED (PostgreSQL + Stored Procedures)
require_once 'includes/auth.php';
require_once '../config/database.php';

$pageTitle = 'Kelola Berita';
$db = new Database();

$success = '';
$error = '';

/* -------------------------------
   DELETE MULTI
---------------------------------*/
if (isset($_POST['multi_delete']) && !empty($_POST['ids'])) {
    $ids = implode(',', array_map('intval', $_POST['ids']));

    // Ambil gambar yang akan dihapus (opsional hapus file)
    $rows = $db->fetchAll($db->query("SELECT gambar FROM berita WHERE id IN ($ids)"));
    if ($rows) {
        foreach ($rows as $r) {
            if (!empty($r['gambar'])) {
                $path = __DIR__ . '/../' . $r['gambar'];
                if (file_exists($path)) @unlink($path);
            }
        }
    }

    if ($db->query("DELETE FROM berita WHERE id IN ($ids)")) {
        $success = "Berita terpilih berhasil dihapus!";
    } else {
        $error = "Gagal menghapus beberapa berita.";
    }
}

/* -------------------------------
   DELETE SINGLE
---------------------------------*/
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    $check = $db->query("SELECT gambar FROM berita WHERE id = $id LIMIT 1");
    $row = $db->fetch($check);
    if ($row) {
        // hapus file gambar jika ada
        if (!empty($row['gambar'])) {
            $path = __DIR__ . '/../' . $row['gambar'];
            if (file_exists($path)) @unlink($path);
        }

        if ($db->query("DELETE FROM berita WHERE id = $id")) {
            $success = 'Berita berhasil dihapus!';
        } else {
            $error = 'Gagal menghapus berita!';
        }
    } else {
        $error = 'Berita tidak ditemukan!';
    }
}

/* -------------------------------
   ADD / EDIT (gunakan stored procedures)
---------------------------------*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'news_form') {

    $id = (int) ($_POST['id'] ?? 0);
    // sanitize via wrapper escape (as used sebelumnya)
    $judul = $db->escape($_POST['judul'] ?? '');
    $isi = $db->escape($_POST['isi'] ?? ''); // QUILL HTML
    $deskripsi = $db->escape($_POST['deskripsi'] ?? '');
    $kategori = $db->escape($_POST['kategori'] ?? '');
    $tanggal = $db->escape($_POST['tanggal'] ?? '');
    $gambar = '';

    if (!$judul || !$kategori || !$tanggal) {
        $error = "Judul, kategori dan tanggal wajib diisi!";
    }

    /* UPLOAD FILE */
    if (!$error && isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $finfoType = mime_content_type($_FILES['gambar']['tmp_name'] ?? '');
        if (!in_array($finfoType, $allowed)) {
            $error = 'File harus berupa gambar (jpg, png, webp)';
        } else {
            $uploadDir = __DIR__ . '/../uploads/berita/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($_FILES['gambar']['name']));
            $targetPath = $uploadDir . $safeName;

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetPath)) {
                $gambar = 'uploads/berita/' . $safeName;
            } else {
                $error = 'Gagal upload gambar';
            }
        }
    }

    /* INSERT / UPDATE via stored procedure (PostgreSQL) */
    if (!$error) {
        if ($id > 0) {
            // pastikan record ada
            $check = $db->query("SELECT id FROM berita WHERE id = $id LIMIT 1");
            $exists = $db->fetch($check);
            if (!$exists) {
                $error = 'Data berita tidak ditemukan!';
            } else {
                // Jika gambar kosong, kirim NULL sehingga SP akan mempertahankan gambar lama
                $gambar_sql = ($gambar !== '') ? "'" . $db->escape($gambar) . "'" : "NULL";

                $sql = "CALL update_berita(
                    $id,
                    '" . $judul . "',
                    '" . $isi . "',
                    '" . $deskripsi . "',
                    $gambar_sql,
                    '" . $kategori . "',
                    '" . $tanggal . "'
                )";

                if ($db->query($sql)) {
                    $success = 'Berita berhasil diupdate!';
                } else {
                    $error = 'Gagal mengupdate berita!';
                }
            }
        } else {
            // INSERT
            $admin_id = (int) $_SESSION['admin_id'];

            $sql = "CALL insert_berita(
                '" . $judul . "',
                '" . $isi . "',
                '" . $deskripsi . "',
                '" . $db->escape($gambar) . "',
                '" . $kategori . "',
                '" . $tanggal . "',
                $admin_id
            )";

            if ($db->query($sql)) {
                $success = 'Berita berhasil ditambahkan!';
            } else {
                $error = 'Gagal menambahkan berita!';
            }
        }
    }
}

/* -------------------------------
   PAGINATION
---------------------------------*/
$perPage = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $perPage;

$countResult = $db->query("SELECT COUNT(*) AS total FROM berita");
$countRow = $db->fetch($countResult);
$totalRows = (int) ($countRow['total'] ?? 0);
$totalPages = ($totalRows > 0) ? ceil($totalRows / $perPage) : 1;

$result = $db->query("SELECT * FROM berita ORDER BY tanggal DESC, created_at DESC LIMIT $perPage OFFSET $offset");
$data = $db->fetchAll($result);

/* -------------------------------
   GET DATA EDIT
---------------------------------*/
$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $editResult = $db->query("SELECT * FROM berita WHERE id = $editId LIMIT 1");
    $editData = $db->fetch($editResult) ?: null;
}

include 'includes/header.php';
?>

<!-- ALERTS -->
<?php if ($success): ?>
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
        <p class="text-green-700"><?= htmlspecialchars($success) ?></p>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
        <p class="text-red-700"><?= htmlspecialchars($error) ?></p>
    </div>
<?php endif; ?>

<!-- FORM CRUD BERITA DENGAN QUILL -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4"><?= $editData ? 'Edit' : 'Tambah' ?> Berita</h3>

    <form id="newsForm" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="form_type" value="news_form">
        <?php if ($editData): ?><input type="hidden" name="id" value="<?= (int)$editData['id'] ?>"><?php endif; ?>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Judul *</label>
            <input type="text" name="judul" required value="<?= htmlspecialchars($editData['judul'] ?? '') ?>"
                class="w-full px-4 py-2 rounded-lg border" />
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Deskripsi Singkat</label>
            <textarea name="deskripsi" rows="2"
                class="w-full px-4 py-2 rounded-lg border"><?= htmlspecialchars($editData['deskripsi'] ?? '') ?></textarea>
        </div>

        <!-- QUILL EDITOR -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Isi Berita *</label>
            <div id="quillEditor" style="height:250px; background:white;" class="rounded border"></div>
            <textarea name="isi" id="isi" style="display:none;"><?= htmlspecialchars($editData['isi'] ?? '') ?></textarea>
        </div>

        <div class="grid md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Kategori *</label>
                <select name="kategori" class="w-full px-4 py-2 rounded-lg border" required>
                    <option value="">Pilih Kategori</option>
                    <?php
                    $kategoriList = ['Pengumuman', 'Event', 'Pelatihan', 'Kegiatan', 'Penelitian', 'Prestasi'];
                    foreach ($kategoriList as $k) {
                        $sel = (($editData['kategori'] ?? '') == $k) ? 'selected' : '';
                        echo "<option value=\"" . htmlspecialchars($k) . "\" $sel>" . htmlspecialchars($k) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Tanggal *</label>
                <input type="date" name="tanggal" required
                    value="<?= htmlspecialchars($editData['tanggal'] ?? date('Y-m-d')) ?>"
                    class="w-full px-4 py-2 rounded-lg border" />
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Gambar <?= $editData ? '(Opsional)' : '' ?></label>
                <input type="file" name="gambar" accept="image/*" class="w-full px-4 py-2 rounded-lg border" />
            </div>
        </div>

        <?php if ($editData && !empty($editData['gambar'])): ?>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Gambar Saat Ini</label>
                <img src="../<?= htmlspecialchars($editData['gambar']) ?>" class="w-64 h-48 object-cover rounded-lg" />
            </div>
        <?php endif; ?>

        <div class="flex justify-end space-x-2">
            <?php if ($editData): ?>
                <a href="berita.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg">Batal</a>
            <?php endif; ?>
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg">
                <i class="fas fa-save mr-2"></i><?= $editData ? 'Update' : 'Simpan' ?>
            </button>
        </div>
    </form>
</div>

<!-- LIST DATA BERITA -->
<div class="bg-white rounded-xl shadow-md overflow-hidden mt-6">
    <form id="listForm" method="POST">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-600 to-blue-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Gambar</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Judul</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Kategori</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold">Aksi</th>
                        <th class="px-6 py-3 text-center">
                            <input type="checkbox" id="checkAll">
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    <?php if ($data): ?>
                        <?php foreach ($data as $row): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <?php if (!empty($row['gambar'])): ?>
                                        <img src="../<?= htmlspecialchars($row['gambar']) ?>" class="w-20 h-16 object-cover rounded">
                                    <?php else: ?>
                                        <div class="w-20 h-16 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-newspaper text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-800"><?= htmlspecialchars($row['judul']) ?></p>
                                    <p class="text-sm text-gray-500">
                                        <?= htmlspecialchars(mb_substr($row['deskripsi'] ?? '', 0, 60)) ?>
                                        <?= (mb_strlen($row['deskripsi'] ?? '') > 60) ? '...' : '' ?>
                                    </p>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">
                                        <?= htmlspecialchars($row['kategori']) ?>
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= date('d M Y', strtotime($row['tanggal'])) ?>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center space-x-2">
                                        <a href="?edit=<?= (int)$row['id'] ?>" class="px-3 py-1 bg-blue-500 text-white rounded-lg text-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="?delete=<?= (int)$row['id'] ?>" onclick="return confirm('Hapus berita ini?')" class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="ids[]" value="<?= (int)$row['id'] ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-newspaper text-gray-300 text-6xl mb-4"></i><br>
                                Belum ada berita
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t flex justify-between">
            <button type="submit" name="multi_delete" class="px-4 py-2 bg-red-500 text-white rounded-lg" onclick="return confirm('Hapus semua yang dipilih?')">
                Hapus Terpilih
            </button>

            <!-- Pagination -->
            <div class="flex space-x-2">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="px-3 py-1 rounded-lg <?= $i == $page ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    </form>
</div>

<!-- SCRIPT QUILL -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    // Init Quill (form specific)
    var quill = new Quill('#quillEditor', {
        theme: 'snow'
    });

    // Preload isi untuk EDIT (safe injection via PHP json_encode)
    <?php if ($editData): ?>
    quill.root.innerHTML = <?= json_encode($editData['isi']) ?>;
    <?php endif; ?>

    // On Submit → Kirim HTML ke textarea hanya untuk newsForm
    document.getElementById('newsForm').addEventListener('submit', function (e) {
        document.getElementById('isi').value = quill.root.innerHTML;
    });

    // Check / Uncheck all for list form
    document.getElementById("checkAll").addEventListener("change", function () {
        let checkboxes = document.querySelectorAll("#listForm input[name='ids[]']");
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>

<?php include 'includes/footer.php'; ?>
