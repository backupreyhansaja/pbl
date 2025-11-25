<?php
require_once 'config/database.php';
$db = new Database();

// Validasi ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ambil berita lengkap
$sql = "
    SELECT b.*, a.full_name AS uploader
    FROM Berita b
    LEFT JOIN admin_users a ON b.uploaded_by = a.id
    WHERE b.id = $id
    LIMIT 1
";
$res = $db->query($sql);
$berita = $db->fetch($res);

if (!$berita) {
    die("Berita tidak ditemukan!");
}

function tglIndo($date)
{
    return date('d M Y', strtotime($date));
}

include 'includes/header.php';
?>

<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">

        <!-- Tombol kembali -->
        <a href="news.php" class="text-purple-600 hover:text-purple-800 font-semibold mb-6 inline-block">
            ← Kembali ke Semua Berita
        </a>
        <br>
        <!-- Thumbnail -->
        <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full mb-4 inline-block">
                <?= htmlspecialchars($berita['kategori']); ?>
            </span>

            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                <?= htmlspecialchars($berita['judul']); ?>
            </h1>

            <div class="flex items-center text-gray-600 text-sm mb-6">
                <i class="far fa-calendar mr-2"></i> <?= tglIndo($berita['tanggal']); ?>
                <span class="mx-3">•</span>
                <i class="far fa-user mr-2"></i> Uploaded by:
                <span class="font-semibold ml-1"><?= htmlspecialchars($berita['uploader'] ?? 'Unknown'); ?></span>
            </div>
        <?php if ($berita['gambar']): ?>
            <img src="<?= htmlspecialchars($berita['gambar']); ?>" class="w-full h-80 object-cover" alt="Gambar Berita">
        <?php else: ?>
            <div class="w-full h-80 bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center">
                <i class="fas fa-newspaper text-white text-7xl"></i>
            </div>
        <?php endif; ?>

        <div class="p-8">
            <div class="text-gray-700 leading-relaxed quill-content">
                <?= $berita['isi']; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>