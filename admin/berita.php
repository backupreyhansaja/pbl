<?php
require_once 'includes/auth.php';
require_once '../config/database.php';

$pageTitle = 'Kelola Berita';
$db = new Database();

$success = '';
$error = '';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($db->query("DELETE FROM Berita WHERE id = $id")) {
        $success = 'Berita berhasil dihapus!';
    }
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $judul = $db->escape($_POST['judul']);
    $isi = $db->escape($_POST['isi']);
    $deskripsi = $db->escape($_POST['deskripsi']);
    $kategori = $db->escape($_POST['kategori']);
    $tanggal = $db->escape($_POST['tanggal']);
    $gambar = '';
    
    // Handle file upload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $uploadDir = '../uploads/berita/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = time() . '_' . basename($_FILES['gambar']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetPath)) {
            $gambar = 'uploads/berita/' . $fileName;
        }
    }
    
    if ($id > 0) {
        // Update
        $sql = "UPDATE Berita SET judul = '$judul', isi = '$isi', deskripsi = '$deskripsi', kategori = '$kategori', tanggal = '$tanggal'";
        if ($gambar) {
            $sql .= ", gambar = '$gambar'";
        }
        $sql .= ", updated_at = CURRENT_TIMESTAMP WHERE id = $id";
        
        if ($db->query($sql)) {
            $success = 'Berita berhasil diupdate!';
        } else {
            $error = 'Gagal mengupdate berita!';
        }
    } else {
        // Insert
        $sql = "INSERT INTO Berita (judul, isi, deskripsi, gambar, kategori, tanggal) 
                VALUES ('$judul', '$isi', '$deskripsi', '$gambar', '$kategori', '$tanggal')";
        
        if ($db->query($sql)) {
            $success = 'Berita berhasil ditambahkan!';
        } else {
            $error = 'Gagal menambahkan berita!';
        }
    }
}

// Fetch all data
$result = $db->query("SELECT * FROM Berita ORDER BY tanggal DESC, created_at DESC");
$data = $db->fetchAll($result);

// Get data for edit
$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $editResult = $db->query("SELECT * FROM Berita WHERE id = $editId LIMIT 1");
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
        <?php echo $editData ? 'Edit' : 'Tambah'; ?> Berita
    </h3>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <?php if ($editData): ?>
            <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
        <?php endif; ?>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Judul Berita *</label>
            <input type="text" name="judul" required 
                   value="<?php echo htmlspecialchars($editData['judul'] ?? ''); ?>"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"
                   placeholder="Contoh: Pembukaan Laboratorium Baru">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Deskripsi Singkat</label>
            <textarea name="deskripsi" rows="2" 
                      class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"
                      placeholder="Ringkasan berita (ditampilkan di kartu berita)..."><?php echo htmlspecialchars($editData['deskripsi'] ?? ''); ?></textarea>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Isi Berita Lengkap *</label>
            <textarea name="isi" rows="6" required
                      class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"
                      placeholder="Tulis isi berita lengkap di sini..."><?php echo htmlspecialchars($editData['isi'] ?? ''); ?></textarea>
        </div>
        
        <div class="grid md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Kategori *</label>
                <select name="kategori" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none">
                    <option value="">Pilih Kategori</option>
                    <option value="Pengumuman" <?php echo (($editData['kategori'] ?? '') == 'Pengumuman') ? 'selected' : ''; ?>>Pengumuman</option>
                    <option value="Event" <?php echo (($editData['kategori'] ?? '') == 'Event') ? 'selected' : ''; ?>>Event</option>
                    <option value="Pelatihan" <?php echo (($editData['kategori'] ?? '') == 'Pelatihan') ? 'selected' : ''; ?>>Pelatihan</option>
                    <option value="Kegiatan" <?php echo (($editData['kategori'] ?? '') == 'Kegiatan') ? 'selected' : ''; ?>>Kegiatan</option>
                    <option value="Penelitian" <?php echo (($editData['kategori'] ?? '') == 'Penelitian') ? 'selected' : ''; ?>>Penelitian</option>
                    <option value="Prestasi" <?php echo (($editData['kategori'] ?? '') == 'Prestasi') ? 'selected' : ''; ?>>Prestasi</option>
                </select>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Tanggal Berita *</label>
                <input type="date" name="tanggal" required
                       value="<?php echo htmlspecialchars($editData['tanggal'] ?? date('Y-m-d')); ?>"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none">
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Gambar <?php echo $editData ? '(Opsional)' : ''; ?></label>
                <input type="file" name="gambar" accept="image/*"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-purple-500 outline-none">
            </div>
        </div>
        
        <?php if ($editData && $editData['gambar']): ?>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Gambar Saat Ini</label>
                <img src="../<?php echo htmlspecialchars($editData['gambar']); ?>" alt="Preview" class="w-64 h-48 object-cover rounded-lg">
            </div>
        <?php endif; ?>
        
        <div class="flex justify-end space-x-2">
            <?php if ($editData): ?>
                <a href="berita.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </a>
            <?php endif; ?>
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:shadow-lg transition">
                <i class="fas fa-save mr-2"></i><?php echo $editData ? 'Update' : 'Simpan'; ?>
            </button>
        </div>
    </form>
</div>

<!-- Berita List -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-purple-600 to-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Gambar</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Judul</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Kategori</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if ($data && count($data) > 0): ?>
                    <?php foreach ($data as $row): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <?php if ($row['gambar']): ?>
                                    <img src="../<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>" class="w-20 h-16 object-cover rounded">
                                <?php else: ?>
                                    <div class="w-20 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-newspaper text-gray-400"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($row['judul']); ?></p>
                                <p class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars(substr($row['deskripsi'] ?? '', 0, 60)); ?><?php echo strlen($row['deskripsi'] ?? '') > 60 ? '...' : ''; ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">
                                    <?php echo htmlspecialchars($row['kategori']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?php echo date('d M Y', strtotime($row['tanggal'])); ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center space-x-2">
                                    <a href="?edit=<?php echo $row['id']; ?>" class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirmDelete()" class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <i class="fas fa-newspaper text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-500 text-lg">Belum ada berita</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
