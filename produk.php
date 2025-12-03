<?php
require_once 'config/database.php';
$db = new Database();

$pageTitle = "Produk Kami";

$produk = $db->fetchAll(
    $db->query("SELECT * FROM product ORDER BY id DESC")
);

include 'includes/header.php';
?>

<section class="py-20 bg-white">
    <div class="container mx-auto px-6">

        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Produk</h2>
            <div class="w-24 h-1 gradient-bg mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

            <?php foreach ($produk as $p): ?>
                <a href="<?= htmlspecialchars($p['link']) ?>" target="_blank"
                   class="block card-hover"
                   data-aos="fade-up">

                    <div class="bg-gray-50 p-6 rounded-2xl shadow-lg hover:shadow-2xl transition">

                        <div class="w-full h-40 mb-4 overflow-hidden rounded-lg bg-gray-200">
                            <?php if ($p['image']): ?>
                                <img src="<?= htmlspecialchars($p['image']) ?>"
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="fas fa-image text-4xl"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <h3 class="font-bold text-lg text-center text-gray-800">
                            <?= htmlspecialchars($p['title']) ?>
                        </h3>
                    </div>
                </a>
            <?php endforeach; ?>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
