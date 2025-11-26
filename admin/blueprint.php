<?php
require_once 'includes/auth.php';
require_once '../config/database.php';

$pageTitle = 'Kelola Blueprint';
$db = new Database();

$success = '';
$error = '';

// DELETE
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($db->query("DELETE FROM blueprint WHERE id = $id")) {
        $success = 'Blueprint berhasil dihapus!';
    } else {
        $error = 'Gagal menghapus data.';
    }
}

// ADD / EDIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = $db->escape($_POST['title']);
    $description = $db->escape($_POST['description']);
    $image = '';

    // Upload gambar opsional
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = '../uploads/blueprint/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image = 'uploads/blueprint/' . $fileName;
        }
    }

    if ($id > 0) {
        // UPDATE
        $sql = "UPDATE blueprint SET title='$title', description='$description'";
        if ($image) {
            $sql .= ", image='$image'";
        }
        $sql .= ", updated_at=CURRENT_TIMESTAMP WHERE id=$id";
    } else {
        // INSERT
        if (!$image) $error = "Gambar harus diupload!";
        if (!$error) {
            $sql = "INSERT INTO blueprint (title, description, image) 
                    VALUES ('$title', '$description', '$image')";
        }
    }

    if (!$error && $db->query($sql)) {
        $success = "Blueprint berhasil disimpan!";
    } elseif (!$error) {
        $error = "Gagal menyimpan data.";
    }
}

// FETCH DATA
$result = $db->query("SELECT * FROM blueprint ORDER BY id DESC");
$data = $db->fetchAll($result);

// GET DATA FOR EDIT
$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $editResult = $db->query("SELECT * FROM blueprint WHERE id = $editId LIMIT 1");
    $editData = $db->fetch($editResult);
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

<!-- Form -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <?php echo $editData ? 'Edit Blueprint' : 'Tambah Blueprint'; ?>
    </h3>

    <form method="POST" action="" enctype="multipart/form-data">
        <?php if ($editData): ?>
        <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
        <?php endif; ?>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Judul *</label>
            <input type="text" name="title" required 
                   value="<?php echo htmlspecialchars($editData['title'] ?? ''); ?>"
                   class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-purple-300">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Deskripsi *</label>
            <textarea name="description" rows="4" required
                      class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-purple-300"><?php echo htmlspecialchars($editData['description'] ?? ''); ?></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">
                Gambar <?php echo $editData ? '(opsional)' : '*'; ?>
            </label>
            <input type="file" name="image" accept="image/*"
                   class="w-full px-4 py-2 border rounded-lg">
        </div>

        <?php if ($editData && $editData['image']): ?>
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Gambar Saat Ini</label>
            <img src="../<?php echo $editData['image']; ?>" class="w-48 rounded-lg">
        </div>
        <?php endif; ?>

        <div class="flex justify-end space-x-2">
            <?php if ($editData): ?>
            <a href="blueprint.php" class="px-4 py-2 bg-gray-300 rounded-lg">Batal</a>
            <?php endif; ?>

            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                Simpan
            </button>
        </div>
    </form>
</div>

<!-- List Blueprint -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if ($data): ?>
        <?php foreach ($data as $row): ?>
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="h-48 overflow-hidden">
                <img src="../<?php echo $row['image']; ?>" class="w-full h-full object-cover">
            </div>
            <div class="p-4">
                <h4 class="font-bold text-lg"><?php echo $row['title']; ?></h4>
                <p class="text-gray-600 text-sm mb-3"><?php echo $row['description']; ?></p>

                <div class="flex space-x-2">
                    <a href="?edit=<?php echo $row['id']; ?>" class="flex-1 text-center py-2 bg-blue-500 text-white rounded-lg">Edit</a>
                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Hapus data ini?')"
                       class="flex-1 text-center py-2 bg-red-500 text-white rounded-lg">
                       Hapus
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
    <div class="col-span-full text-center py-10 bg-white rounded-xl shadow">
        <i class="fas fa-inbox text-4xl mb-2"></i>
        <p class="text-gray-500 text-lg">Belum ada blueprint tersimpan</p>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
