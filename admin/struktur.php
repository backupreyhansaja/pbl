<?php
require_once 'includes/auth.php';
require_once '../config/database.php';

$pageTitle = 'Struktur Organisasi';
$db = new Database();

$success = '';
$error = '';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($db->query("DELETE FROM struktur_organisasi WHERE id = $id")) {
        $success = 'Data berhasil dihapus!';
    }
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $jabatan = $db->escape($_POST['jabatan']);
    $nama = $db->escape($_POST['nama']);
    $urutan = (int)$_POST['urutan'];
    $foto = '';
    
    // Handle file upload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $uploadDir = '../uploads/struktur/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = time() . '_' . basename($_FILES['foto']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
            $foto = 'uploads/struktur/' . $fileName;
        }
    }
    
    if ($id > 0) {
        // Update
        $sql = "UPDATE struktur_organisasi SET jabatan = '$jabatan', nama = '$nama', urutan = $urutan";
        if ($foto) {
            $sql .= ", foto = '$foto'";
        }
        $sql .= ", updated_at = CURRENT_TIMESTAMP WHERE id = $id";
    } else {
        // Insert
        $sql = "INSERT INTO struktur_organisasi (jabatan, nama, foto, urutan) VALUES ('$jabatan', '$nama', '$foto', $urutan)";
    }
    
    if ($db->query($sql)) {
        $success = 'Data berhasil disimpan!';
    } else {
        $error = 'Gagal menyimpan data!';
    }
}

// Fetch all data
$result = $db->query("SELECT * FROM struktur_organisasi ORDER BY urutan ASC");
$data = $db->fetchAll($result);

// Get data for edit
$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $editResult = $db->query("SELECT * FROM struktur_organisasi WHERE id = $editId LIMIT 1");
    $editData = $db->fetch($editResult);
}

include 'includes/header.php';
?>

<?php if ($success): ?>
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <p class="text-green-700"><?php echo $success; ?></p>
        </div>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
            <p class="text-red-700"><?php echo $error; ?></p>
        </div>
    </div>
<?php endif; ?>

<!-- Form -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <?php echo $editData ? 'Edit' : 'Tambah'; ?> Struktur Organisasi
    </h3>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <?php if ($editData): ?>
            <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
        <?php endif; ?>
        
        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Jabatan *</label>
                <input type="text" name="jabatan" required 
                       value="<?php echo htmlspecialchars($editData['jabatan'] ?? ''); ?>"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"
                       placeholder="Contoh: Ketua Laboratorium">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nama *</label>
                <input type="text" name="nama" required 
                       value="<?php echo htmlspecialchars($editData['nama'] ?? ''); ?>"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"
                       placeholder="Nama lengkap">
            </div>
        </div>
        
        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Urutan *</label>
                <input type="number" name="urutan" required 
                       value="<?php echo htmlspecialchars($editData['urutan'] ?? '0'); ?>"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"
                       placeholder="0">
                <p class="text-sm text-gray-600 mt-1">Urutan tampilan (angka kecil akan tampil lebih dulu)</p>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Foto</label>
                <input type="file" name="foto" accept="image/*" 
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-purple-500 outline-none">
                <?php if ($editData && $editData['foto']): ?>
                    <p class="text-sm text-gray-600 mt-1">Foto saat ini: <?php echo basename($editData['foto']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="flex justify-end space-x-2">
            <?php if ($editData): ?>
                <a href="struktur.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </a>
            <?php endif; ?>
            <button type="submit" class="px-6 py-2 text-white rounded-lg hover:shadow-lg transition" style="background-color : blue">
                <i class="fas fa-save mr-2"></i><?php echo $editData ? 'Update' : 'Simpan'; ?>
            </button>
        </div>
    </form>
</div>

<!-- Data Table -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="text-white"  style="background-color : blue">
                <tr>
                    <th class="px-6 py-3 text-left">Urutan</th>
                    <th class="px-6 py-3 text-left">Foto</th>
                    <th class="px-6 py-3 text-left">Jabatan</th>
                    <th class="px-6 py-3 text-left">Nama</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($data && count($data) > 0): ?>
                    <?php foreach ($data as $row): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo $row['urutan']; ?></td>
                            <td class="px-6 py-4">
                                <?php if ($row['foto']): ?>
                                    <img src="../<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto" class="w-12 h-12 rounded-full object-cover">
                                <?php else: ?>
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 font-semibold"><?php echo htmlspecialchars($row['jabatan']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td class="px-6 py-4 text-center">
                                <a href="?edit=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800 mr-3">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirmDelete()" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Belum ada data</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
