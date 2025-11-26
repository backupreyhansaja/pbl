<?php
require_once 'includes/auth.php';
require_once '../config/database.php';

$pageTitle = 'Kelola Scope';
$db = new Database();

$success = '';
$error = '';

/* =============================
   DELETE DATA
============================= */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($db->query("DELETE FROM scope WHERE id = $id")) {
        $success = 'Data Scope berhasil dihapus!';
    } else {
        $error = 'Gagal menghapus data!';
    }
}

/* =============================
   INSERT / UPDATE DATA
============================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $judul = $db->escape($_POST['judul']);
    $deskripsi = $db->escape($_POST['deskripsi']);
    $gambar = '';

    // Upload Gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $uploadDir = '../uploads/scope/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES['gambar']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetPath)) {
            $gambar = 'uploads/scope/' . $fileName;
        }
    }

    if ($id > 0) {
        // UPDATE
        $sql = "UPDATE scope 
                SET judul = '$judul', deskripsi = '$deskripsi'";

        if ($gambar) {
            $sql .= ", gambar = '$gambar'";
        }

        $sql .= ", updated_at = CURRENT_TIMESTAMP WHERE id = $id";

    } else {
        // INSERT
        if (!$gambar) {
            $error = 'Gambar harus diupload!';
        } else {
            $sql = "INSERT INTO scope (judul, deskripsi, gambar) 
                    VALUES ('$judul', '$deskripsi', '$gambar')";
        }
    }

    if (!$error && $db->query($sql)) {
        $success = 'Data Scope berhasil disimpan!';
    } elseif (!$error) {
        $error = 'Gagal menyimpan data!';
    }
}

/* =============================
   FETCH DATA
============================= */
$result = $db->query("SELECT * FROM scope ORDER BY created_at DESC");
$data = $db->fetchAll($result);

// get data untuk form edit
$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $res = $db->query("SELECT * FROM scope WHERE id = $editId LIMIT 1");
    $editData = $db->fetch($res);
}

include 'includes/header.php';
?>

<!-- Alert Berhasil -->
<?php if ($success): ?>
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
    <div class="flex items-center">
        <i class="fas fa-check-circle text-green-500 mr-3"></i>
        <p class="text-green-700"><?php echo $success; ?></p>
    </div>
</div>
<?php endif; ?>

<!-- Alert Error -->
<?php if ($error): ?>
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
        <p class="text-red-700"><?php echo $error; ?></p>
    </div>
</div>
<?php endif; ?>

<!-- FORM TAMBAH / EDIT -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <?php echo $editData ? 'Edit Scope' : 'Tambah Scope'; ?>
    </h3>

    <form method="POST" enctype="multipart/form-data">
        <?php if ($editData): ?>
            <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
        <?php endif; ?>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Judul *</label>
            <input type="text" name="judul" required
                   value="<?php echo htmlspecialchars($editData['judul'] ?? ''); ?>"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-300 outline-none">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Deskripsi *</label>
            <textarea name="deskripsi" rows="3"
                      class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-300 outline-none"><?php echo htmlspecialchars($editData['deskripsi'] ?? ''); ?></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">
                Gambar <?php echo $editData ? '(opsional)' : '*'; ?>
            </label>
            <input type="file" name="gambar" accept="image/*" 
                   <?php echo $editData ? '' : 'required'; ?>
                   class="w-full px-4 py-2 rounded-lg border border-gray-300">
        </div>

        <?php if ($editData && $editData['gambar']): ?>
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Gambar Saat Ini</label>
            <img src="../<?php echo $editData['gambar']; ?>" class="w-48 h-48 object-cover rounded-lg">
        </div>
        <?php endif; ?>

        <div class="flex justify-end space-x-2">
            <?php if ($editData): ?>
                <a href="scope.php" class="px-4 py-2 bg-gray-300 rounded-lg">Batal</a>
            <?php endif; ?>

            <button type="submit" class="px-6 py-2 text-white rounded-lg hover:shadow-md transition" style="background-color: blue">
                <i class="fas fa-save mr-2"></i><?php echo $editData ? 'Update' : 'Simpan'; ?>
            </button>
        </div>
    </form>
</div>

<!-- GRID DATA -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
<?php if ($data && count($data) > 0): ?>
    <?php foreach ($data as $row): ?>
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition overflow-hidden">
            <div class="h-48">
                <img src="../<?php echo $row['gambar']; ?>" class="w-full h-full object-cover">
            </div>

            <div class="p-4">
                <h4 class="font-bold text-lg mb-2"><?php echo $row['judul']; ?></h4>
                <p class="text-sm text-gray-600 mb-3"><?php echo $row['deskripsi']; ?></p>
                <div class="flex space-x-2">
                    <a href="?edit=<?php echo $row['id']; ?>" class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg text-center hover:bg-blue-600">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>

                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?');"
                       class="flex-1 px-4 py-2 bg-red-500 text-white rounded-lg text-center hover:bg-red-600">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

<?php else: ?>
    <div class="col-span-full text-center py-10 bg-white rounded-xl">
        <i class="fas fa-inbox text-4xl mb-2"></i>
        <p class="text-gray-500 text-lg">Belum ada data scope</p>
    </div>
<?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
