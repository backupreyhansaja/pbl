<?php
require_once 'config/database.php';
$db = new Database();

$pageTitle = "Daftar Member / Dosen";

// pagination
$perPage = 12;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $perPage;

// total row
$totalRow = $db->fetch($db->query("SELECT COUNT(*) AS t FROM dosen_detail"));
$total = intval($totalRow['t'] ?? 0);
$totalPages = $total > 0 ? ceil($total / $perPage) : 1;

// ambil data dosen
$memberResult = $db->query("
        SELECT d.*, j.nama_jabatan, j.urutan
        FROM dosen_detail d
        LEFT JOIN jabatan j ON j.id = d.jabatan_id
        ORDER BY j.urutan ASC, d.nama ASC
        LIMIT $perPage OFFSET $offset
    ");
$member = $db->fetchAll($memberResult);

include 'includes/header.php';
?>

<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Daftar Member</h2>
            <div class="w-24 h-1 gradient-bg mx-auto rounded-full"></div>
            <p class="text-gray-600 mt-4">Profil lengkap seluruh member laboratorium</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

            <?php if ($member && count($member) > 0): ?>
                <?php foreach ($member as $index => $d): ?>
                    <a href="dosen_detail.php?id=<?= $d['id']; ?>"
                       class="text-center block card-hover"
                       data-aos="fade-up"
                       data-aos-delay="<?= $index * 70; ?>">

                        <div class="bg-gray-50 p-6 rounded-2xl shadow-lg hover:shadow-2xl transition">
                            <div class="w-32 h-32 mx-auto mb-4 gradient-bg rounded-full flex items-center justify-center overflow-hidden">
                                <?php if ($d['foto']): ?>
                                    <img src="<?= htmlspecialchars($d['foto']); ?>"
                                         alt="<?= htmlspecialchars($d['nama']); ?>"
                                         class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-user text-white text-5xl"></i>
                                <?php endif; ?>
                            </div>

                            <h3 class="font-bold text-lg text-gray-800 mb-1">
                                <?= htmlspecialchars($d['nama']); ?>
                            </h3>

                            <p class="text-blue-700 font-semibold">
                                <?= htmlspecialchars($d['nama_jabatan']); ?>
                            </p>
                        </div>

                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada data member</p>
                </div>
            <?php endif; ?>

        </div>

        <!-- PAGINATION -->
        <?php if ($totalPages > 1): ?>
            <div class="flex justify-center mt-12 space-x-2">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>"
                       class="px-4 py-2 rounded-lg 
                         <?= $i == $page ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
