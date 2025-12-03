<?php
require_once 'includes/auth.php';
require_once '../config/database.php';

$db = new Database();
$pageTitle = "Kelola Partner";

$success = '';
$error = '';

/* ============================
   DELETE
============================ */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $q = $db->fetch($db->query("SELECT image FROM partner WHERE id=$id"));
    if ($q && $q['image'] && file_exists("../".$q['image'])) {
        @unlink("../".$q['image']);
    }
    $db->query("DELETE FROM partner WHERE id=$id");
    $success = "Partner berhasil dihapus!";
}

/* ============================
   CREATE / UPDATE
============================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['id'] ?? 0);
    $title = $db->escape($_POST['title']);
    $category = $db->escape($_POST['category']);
    $link = $db->escape($_POST['link']);
    $image = "";

    if (!$title) $error = "Judul wajib diisi!";
    if (!$category) $error = "Kategori wajib dipilih!";

    /* Upload image */
    if (!$error && isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['image/jpeg','image/png','image/webp'];
        if (!in_array($_FILES['image']['type'],$allowed)) {
            $error = "Format gambar harus JPG/PNG/WebP.";
        } else {
            $dir = "../uploads/partner/";
            if (!is_dir($dir)) mkdir($dir,0777,true);

            $fileName = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/','_',$_FILES['image']['name']);
            $target = $dir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'],$target)) {
                $image = "uploads/partner/" . $fileName;
            } else $error = "Gagal mengupload gambar.";
        }
    }

    if (!$error && $id == 0) {
        $db->query("
            INSERT INTO partner (title, category, link, image)
            VALUES ('$title','$category','$link','$image')
        ");
        $success = "Partner berhasil ditambahkan!";
    }

    if (!$error && $id > 0) {
        $old = $db->fetch($db->query("SELECT image FROM partner WHERE id=$id"));
        $sql = "UPDATE partner SET title='$title', category='$category', link='$link'";
        if ($image) {
            if ($old['image'] && file_exists("../".$old['image'])) @unlink("../".$old['image']);
            $sql .= ", image='$image'";
        }
        $sql .= " WHERE id=$id";
        $db->query($sql);
        $success = "Partner berhasil diperbarui!";
    }
}

/* ============================
   EDIT
============================ */
$edit = null;
if (isset($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $edit = $db->fetch($db->query("SELECT * FROM partner WHERE id=$eid LIMIT 1"));
}

/* ============================
   GET ALL PARTNERS
============================ */
$data = $db->fetchAll($db->query("SELECT * FROM partner ORDER BY id DESC"));

include 'includes/header.php';
?>

<!-- ALERT -->
<?php if ($success): ?>
<div class="bg-green-100 text-green-700 p-3 mb-4 rounded"><?= $success ?></div>
<?php endif; ?>
<?php if ($error): ?>
<div class="bg-red-100 text-red-700 p-3 mb-4 rounded"><?= $error ?></div>
<?php endif; ?>

<!-- FORM -->
<div class="bg-white p-6 rounded-xl shadow mb-6">
    <h3 class="text-xl font-bold mb-4"><?= $edit ? "Edit Partner" : "Tambah Partner" ?></h3>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $edit['id'] ?? 0 ?>">

        <div class="mb-4">
            <label>Judul *</label>
            <input type="text" name="title" required class="w-full px-4 py-2 border rounded-lg"
                   value="<?= htmlspecialchars($edit['title'] ?? '') ?>">
        </div>

        <div class="mb-4">
            <label>Kategori *</label>
            <select name="category" required class="w-full px-4 py-2 border rounded-lg">
                <option value="">-- Pilih Kategori --</option>
                <?php 
                $cats = ['Industri','Edukasi','Pemerintahan','Internasional'];
                foreach($cats as $cat): ?>
                    <option value="<?= $cat ?>" <?= ($edit['category'] ?? '')==$cat?'selected':'' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label>Link Partner</label>
            <input type="text" name="link" class="w-full px-4 py-2 border rounded-lg"
                   value="<?= htmlspecialchars($edit['link'] ?? '') ?>">
        </div>

        <div class="mb-4">
            <label>Logo Partner</label>
            <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
        </div>

        <?php if ($edit && $edit['image']): ?>
        <img src="../<?= $edit['image'] ?>" class="w-40 h-40 object-cover rounded mb-3">
        <?php endif; ?>

        <button class="px-6 py-2 bg-blue-600 text-white rounded-lg"><?= $edit ? "Update" : "Simpan" ?></button>
    </form>
</div>

<!-- LIST -->
<div class="bg-white rounded-xl shadow p-6">
    <table class="w-full">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="px-4 py-2">Logo</th>
                <th class="px-4 py-2">Judul</th>
                <th class="px-4 py-2">Kategori</th>
                <th class="px-4 py-2">Link</th>
                <th class="px-4 py-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            <?php foreach ($data as $row): ?>
            <tr>
                <td class="px-4 py-2">
                    <?php if ($row['image']): ?>
                        <img src="../<?= $row['image'] ?>" class="w-20 h-20 object-cover rounded">
                    <?php else: ?>
                        <div class="w-20 h-20 bg-gray-200"></div>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['title']) ?></td>
                <td class="px-4 py-2"><?= $row['category'] ?></td>
                <td class="px-4 py-2 text-blue-600"><?= htmlspecialchars($row['link']) ?></td>
                <td class="px-4 py-2 text-center space-x-2">
                    <a href="?edit=<?= $row['id'] ?>" class="px-3 py-1 bg-purple-500 text-white rounded">Edit</a>
                    <a onclick="return confirm('Hapus partner?')" href="?delete=<?= $row['id'] ?>" class="px-3 py-1 bg-red-500 text-white rounded">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
