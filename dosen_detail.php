<?php
require_once 'config/database.php';
$db = new Database();

$id = intval($_GET['id'] ?? 0);

$query = $db->query("
    SELECT d.*, j.nama_jabatan 
    FROM dosen_detail d
    JOIN jabatan j ON d.jabatan_id = j.id
    WHERE d.id = $id
");
$dosen = $db->fetch($query);

if (!$dosen) {
    die("Dosen tidak ditemukan.");
}

$pubQuery = $db->query("
    SELECT * FROM dosen_publikasi 
    WHERE dosen_id = $id 
    ORDER BY tahun DESC
");
$publikasi = $db->fetchAll($pubQuery);

$pageTitle = $dosen['nama'];
include 'includes/header.php';
?>

<section class="py-20 bg-white">
    <div class="container mx-auto px-6 max-w-4xl">

        <!-- Profil Dosen -->
        <div class="bg-gray-50 p-8 rounded-2xl shadow-xl mb-10">
            <div class="flex gap-8 items-center">
                <div class="w-40 h-40 rounded-2xl overflow-hidden shadow">
                    <?php if ($dosen['foto']): ?>
                        <img src="<?= htmlspecialchars($dosen['foto']); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-blue-700 flex items-center justify-center text-white text-5xl">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <div>
                    <h1 class="text-4xl font-bold text-gray-800"><?= htmlspecialchars($dosen['nama']); ?></h1>
                    <p class="text-blue-700 font-semibold text-lg"><?= htmlspecialchars($dosen['nama_jabatan']); ?></p>

                    <div class="mt-3 text-gray-600">
                        <?php if ($dosen['email']): ?>
                            <p><i class="fas fa-envelope mr-2"></i><?= htmlspecialchars($dosen['email']); ?></p>
                        <?php endif; ?>

                        <?php if ($dosen['phone']): ?>
                            <p><i class="fas fa-phone mr-2"></i><?= htmlspecialchars($dosen['phone']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-gray-700 leading-relaxed">
                <?= nl2br(htmlspecialchars($dosen['deskripsi'])); ?>
            </div>
        </div>


        <!-- Publikasi -->
        <h2 class="text-3xl font-bold mb-6 text-center">Publikasi Ilmiah</h2>

        <?php if ($publikasi && count($publikasi) > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-xl rounded-xl overflow-hidden">
                    <thead class="bg-blue-700 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Judul Publikasi</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tahun</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        <?php $no = 1;
                        foreach ($publikasi as $pub): ?>
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4"><?= $no++; ?></td>

                                <td class="px-6 py-4 font-medium">
                                    <?= htmlspecialchars($pub['judul']); ?>
                                </td>

                                <td class="px-6 py-4">
                                    <?= $pub['tahun']; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500">Belum ada publikasi.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>