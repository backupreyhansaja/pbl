<?php
require_once 'includes/auth.php';
require_once '../config/database.php';

$pageTitle = 'Struktur Organisasi';
$db = new Database();

$success = '';
$error = '';

/* ================================
   DELETE DATA
================================ */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($db->query("DELETE FROM struktur_organisasi WHERE id = $id")) {
        $success = 'Data berhasil dihapus!';
    } else {
        $error = 'Gagal menghapus data!';
    }
}

/* ================================
   ADD / EDIT DATA
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    $jabatan = $db->escape($_POST['jabatan']);
    $nama = $db->escape($_POST['nama']);
    $foto = '';

    // Upload foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $uploadDir = '../uploads/struktur/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . '_' . basename($_FILES['foto']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
            $foto = 'uploads/struktur/' . $fileName;
        }
    }

    if ($id > 0) {
        // UPDATE
        $sql = "UPDATE struktur_organisasi 
                SET jabatan='$jabatan', nama='$nama'";
        if ($foto) {
            $sql .= ", foto='$foto'";
        }
        $sql .= ", updated_at=CURRENT_TIMESTAMP WHERE id=$id";
    } else {
        // INSERT
        $sql = "INSERT INTO struktur_organisasi (jabatan, nama, foto)
                VALUES ('$jabatan', '$nama', '$foto')";
    }

    if ($db->query($sql)) {
        $success = 'Data berhasil disimpan!';
    } else {
        $error = 'Gagal menyimpan data!';
    }
}

/* ================================
   GET DATA
================================ */
/*
Ranking jabatan (besar ke kecil):
1. Ketua Laboratorium
2. Wakil Ketua
3. Sekretaris
4. Bendahara
5. Koordinator
6. Anggota
*/
$result = $db->query("
    SELECT * FROM struktur_organisasi
    ORDER BY 
        CASE 
            WHEN jabatan = 'Ketua Laboratorium' THEN 1
            WHEN jabatan = 'Wakil Ketua' THEN 2
            WHEN jabatan = 'Sekretaris' THEN 3
            WHEN jabatan = 'Bendahara' THEN 4
            WHEN jabatan = 'Koordinator' THEN 5
            WHEN jabatan = 'Anggota' THEN 6
            ELSE 99
        END,
        nama ASC
");
$data = $db->fetchAll($result);

// Edit mode
$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $editRes = $db->query("SELECT * FROM struktur_organisasi WHERE id = $editId LIMIT 1");
    $editData = $db->fetch($editRes);
}

include 'includes/header.php';
?>

<?php if ($success): ?>
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
    <p class="text-green-700"><?php echo $success; ?></p>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
    <p class="text-red-700"><?php echo $error; ?></p>
</div>
<?php endif; ?>

<!-- FORM TAMBAH / EDIT -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <h3 class="text-lg font-bold mb-4">
        <?php echo $editData ? 'Edit Struktur' : 'Tambah Struktur Organisasi'; ?>
    </h3>

    <form method="POST" enctype="multipart/form-data">
        <?php if ($editData): ?>
            <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
        <?php endif; ?>

        <div class="grid md:grid-cols-2 gap-4 mb-4">

            <div>
                <label class="block font-semibold mb-2">Jabatan *</label>
                <select name="jabatan" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-300">
                    <option value="">-- Pilih Jabatan --</option>

                    <?php 
                    $listJabatan = [
                        "Ketua Laboratorium",
                        "Wakil Ketua",
                        "Sekretaris",
                        "Bendahara",
                        "Koordinator",
                        "Anggota"
                    ];

                    foreach ($listJabatan as $j): ?>
                        <option value="<?php echo $j; ?>"
                            <?php echo (isset($editData['jabatan']) && $editData['jabatan'] === $j) ? 'selected' : ''; ?>>
                            <?php echo $j; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block font-semibold mb-2">Nama *</label>
                <input type="text" name="nama" required
                       value="<?php echo htmlspecialchars($editData['nama'] ?? ''); ?>"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-300">
            </div>

        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-2">Foto</label>
            <input type="file" name="foto" accept="image/*"
                   class="w-full px-4 py-2 border rounded-lg">

            <?php if ($editData && $editData['foto']): ?>
                <p class="mt-2 text-sm text-gray-600">Foto saat ini:</p>
                <img src="../<?php echo $editData['foto']; ?>" class="w-24 h-24 rounded-lg mt-1 object-cover">
            <?php endif; ?>
        </div>

        <div class="flex justify-end">
            <?php if ($editData): ?>
                <a href="struktur.php" class="px-4 py-2 bg-gray-300 rounded-lg mr-2">Batal</a>
            <?php endif; ?>

            <button class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                Simpan
            </button>
        </div>
    </form>
</div>

<!-- TABEL -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="text-white" style="background-color: blue;">
            <tr>
                <th class="px-6 py-3 text-left">Foto</th>
                <th class="px-6 py-3 text-left">Jabatan</th>
                <th class="px-6 py-3 text-left">Nama</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php if ($data): ?>
            <?php foreach ($data as $row): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <?php if ($row['foto']): ?>
                            <img src="../<?php echo $row['foto']; ?>" class="w-12 h-12 rounded-full object-cover">
                        <?php else: ?>
                            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td class="px-6 py-4 font-semibold"><?php echo $row['jabatan']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['nama']; ?></td>

                    <td class="px-6 py-4 text-center">
                        <a href="?edit=<?php echo $row['id']; ?>" class="text-blue-600 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="?delete=<?php echo $row['id']; ?>" 
                           class="text-red-600" 
                           onclick="return confirm('Hapus data ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i><br>
                    Belum ada data
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
