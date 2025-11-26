<?php
require_once 'config/database.php';
$pageTitle = 'Beranda';

// Fetch data
$db = new Database();

// Get Visi Misi
$visiMisiResult = $db->query("SELECT * FROM visi_misi LIMIT 1");
$visiMisi = $db->fetch($visiMisiResult);

// Get Sejarah
$sejarahResult = $db->query("SELECT * FROM sejarah LIMIT 1");
$sejarah = $db->fetch($sejarahResult);

// Get Struktur Organisasi
$strukturResult = $db->query("SELECT * FROM struktur_organisasi ORDER BY urutan ASC");
$struktur = $db->fetchAll($strukturResult);

// Get News/Announcements
$newsResult = $db->query("SELECT * FROM Berita ORDER BY tanggal DESC, created_at DESC LIMIT 6");
$news = $db->fetchAll($newsResult);

// Get Gallery
$galleryResult = $db->query("SELECT * FROM gallery ORDER BY created_at DESC LIMIT 9");
$gallery = $db->fetchAll($galleryResult);

//Get Scope
$scopeResult = $db->query("SELECT * FROM scope ORDER BY urutan ASC, id ASC");
$scope = $db->fetchAll($scopeResult);


include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #3b82f6 100%);">
    <!-- Animated Background Blobs -->
    <div class="absolute top-20 left-20 w-72 h-72 blob"></div>
    <div class="absolute bottom-20 right-20 w-96 h-96 blob" style="animation-delay: 2s;"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center text-white" data-aos="fade-up">
            <div class="mb-6 animate-float">
                <i class="fas fa-flask text-8xl opacity-90"></i>
            </div>
            <h1 class="text-5xl md:text-7xl font-bold mb-6">
                Selamat Datang di<br>
                <span class="text-blue-200">Laboratorium Kampus</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-100 max-w-3xl mx-auto">
                Pusat Inovasi dan Penelitian Teknologi Informasi
            </p>
            <div class="flex justify-center space-x-4">
                <a href="#profil" class="px-8 py-4 bg-white text-blue-900 rounded-lg font-semibold hover-scale shadow-lg">
                    Explore Now
                </a>
                <a href="#contact" class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
        <i class="fas fa-chevron-down text-white text-3xl"></i>
    </div>
</section>

<!-- Profil Section (Logo + Sejarah) -->
<section id="profil" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Profil Laboratorium</h2>
            <div class="w-24 h-1 gradient-bg mx-auto rounded-full"></div>
        </div>
        
        <!-- Logo + Sejarah dalam satu kotak -->
        <div class="max-w-6xl mx-auto" data-aos="fade-up">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-3xl shadow-2xl overflow-hidden">
                <div class="grid md:grid-cols-3 gap-8 p-8">
                    <!-- Logo Section -->
                    <div class="flex items-center justify-center" data-aos="zoom-in">
                        <div class="w-48 h-48 bg-gradient-to-br from-white to-white rounded-2xl shadow-xl flex items-center justify-center p-6">
                            <img src="img/AI Logo.png" alt="Logo Lab Kampus" class="w-full h-full object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="hidden w-full h-full flex-col items-center justify-center text-white">
                                <i class="fas fa-flask text-6xl mb-2"></i>
                                <span class="text-sm font-semibold">LOGO</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sejarah Section -->
                    <div class="md:col-span-2" data-aos="fade-left">
                        <div class="flex items-start mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-900 to-blue-700 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-book text-white text-xl"></i>
                            </div>
                            <h3 class="text-3xl font-bold text-blue-900 mt-2">Sejarah</h3>
                        </div>
                        <div class="text-gray-700 leading-relaxed text-justify">
                            <?php echo nl2br(htmlspecialchars($sejarah['content'] ?? 'Belum ada data sejarah')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Visi & Misi Section -->
<section id="visi-misi" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Visi & Misi</h2>
            <div class="w-24 h-1 gradient-bg mx-auto rounded-full"></div>
        </div>
        
        <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            <!-- Visi -->
            <div class="bg-white p-8 rounded-2xl shadow-lg card-hover" data-aos="fade-right">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 gradient-bg rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-eye text-white text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800">Visi</h3>
                </div>
                <p class="text-gray-700 leading-relaxed text-lg">
                    <?php echo nl2br(htmlspecialchars($visiMisi['visi'] ?? 'Belum ada data visi')); ?>
                </p>
            </div>
            
            <!-- Misi -->
            <div class="bg-white p-8 rounded-2xl shadow-lg card-hover" data-aos="fade-left">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 gradient-bg rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-bullseye text-white text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800">Misi</h3>
                </div>
                <div class="text-gray-700 leading-relaxed text-lg">
                    <?php echo nl2br(htmlspecialchars($visiMisi['misi'] ?? 'Belum ada data misi')); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SCOPE Section -->
<section id="scope" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">SCOPE</h2>
            <div class="w-24 h-1 gradient-bg mx-auto rounded-full"></div>
            <p class="text-gray-600 mt-4">Ruang Lingkup Penelitian dan Pengembangan</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">

            <?php if ($scope && count($scope) > 0): ?>
                <?php foreach ($scope as $index => $item): ?>
                    <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border-l-4"
                         style="border-color: <?php echo htmlspecialchars($item['color'] ?? '#6C5CE7'); ?>;"
                         data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">

                        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl flex items-center justify-center transform group-hover:scale-110 transition-all duration-300"
                             style="background: <?php echo htmlspecialchars($item['color'] ?? '#6C5CE7'); ?>;">
                            <i class="<?php echo htmlspecialchars($item['icon']); ?> text-white text-3xl"></i>
                        </div>

                        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">
                            <?php echo htmlspecialchars($item['title']); ?>
                        </h3>

                        <p class="text-gray-600 text-center leading-relaxed">
                            <?php echo htmlspecialchars($item['description']); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-info-circle text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada data SCOPE</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>


<!-- BLUEPRINT Section -->
<section id="blueprint" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">

        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Blueprint</h2>
            <div class="w-24 h-1 gradient-bg mx-auto rounded-full"></div>
            <p class="text-gray-600 mt-4">Cetak Biru Pengembangan Teknologi</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">

            <?php 
            $blueprintResult = $db->query("SELECT * FROM blueprint ORDER BY urutan ASC, id ASC");
            $blueprints = $db->fetchAll($blueprintResult);
            ?>

            <?php if ($blueprints && count($blueprints) > 0): ?>
                <?php foreach ($blueprints as $index => $bp): ?>
                    <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border-l-4 hover:scale-105"
                         style="border-color: <?= htmlspecialchars($bp['color'] ?? '#6C5CE7'); ?>;"
                         data-aos="fade-up"
                         data-aos-delay="<?= $index * 120; ?>">

                        <!-- ICON -->
                        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl flex items-center justify-center transform group-hover:scale-110 transition-all duration-300"
                             style="background: <?= htmlspecialchars($bp['color'] ?? '#6C5CE7'); ?>;">
                            <i class="<?= htmlspecialchars($bp['icon']); ?> text-white text-3xl"></i>
                        </div>

                        <!-- TITLE -->
                        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">
                            <?= htmlspecialchars($bp['title']); ?>
                        </h3>

                        <!-- DESCRIPTION -->
                        <p class="text-gray-600 text-center leading-relaxed">
                            <?= htmlspecialchars($bp['description']); ?>
                        </p>

                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-diagram-project text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada data blueprint</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>


<!-- Struktur Organisasi Section -->
<section id="struktur" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Struktur Organisasi</h2>
            <div class="w-24 h-1 gradient-bg mx-auto rounded-full"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php if ($struktur && count($struktur) > 0): ?>
                <?php foreach ($struktur as $index => $org): ?>
                    <div class="text-center card-hover" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <div class="bg-gray-50 p-6 rounded-2xl shadow-lg">
                            <div class="w-32 h-32 mx-auto mb-4 gradient-bg rounded-full flex items-center justify-center overflow-hidden">
                                <?php if ($org['foto']): ?>
                                    <img src="<?php echo htmlspecialchars($org['foto']); ?>" alt="<?php echo htmlspecialchars($org['nama']); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-user text-white text-5xl"></i>
                                <?php endif; ?>
                            </div>
                            <h3 class="font-bold text-lg text-gray-800 mb-1"><?php echo htmlspecialchars($org['nama']); ?></h3>
                            <p class="text-blue-700 font-semibold"><?php echo htmlspecialchars($org['jabatan']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada data struktur organisasi</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- News & Announcements Section -->
<section id="news" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Berita & Pengumuman</h2>
            <div class="w-24 h-1 gradient-bg mx-auto rounded-full"></div>
            <p class="text-gray-600 mt-4">Informasi terbaru dan pengumuman penting dari laboratorium</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if ($news && count($news) > 0): ?>
                <?php foreach ($news as $index => $item): ?>
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <?php if ($item['gambar']): ?>
                            <div class="h-48 overflow-hidden">
                                <img src="<?php echo htmlspecialchars($item['gambar']); ?>" alt="<?php echo htmlspecialchars($item['judul']); ?>" class="w-full h-full object-cover">
                            </div>
                        <?php else: ?>
                            <div class="h-48 gradient-bg flex items-center justify-center">
                                <i class="fas fa-newspaper text-white text-6xl"></i>
                            </div>
                        <?php endif; ?>
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <?php if ($item['kategori']): ?>
                                    <span class="px-3 py-1 bg-purple-100 text-purple-600 rounded-full text-xs font-semibold">
                                        <?php echo htmlspecialchars($item['kategori']); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($item['tanggal']): ?>
                                    <span class="ml-auto text-xs text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i><?php echo date('d M Y', strtotime($item['tanggal'])); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <h4 class="font-bold text-xl mb-3 text-gray-800 line-clamp-2"><?php echo htmlspecialchars($item['judul']); ?></h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3"><?php echo($item['isi'] ?? $item['deskripsi'] ?? ''); ?></p>
                            <a href="news_detail.php?id=<?php echo $item['id']; ?>" class="inline-flex items-center text-purple-600 font-semibold hover:text-purple-700 transition">
                                Baca Selengkapnya <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-newspaper text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada berita atau pengumuman</p>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ($news && count($news) > 0): ?>
            <div class="text-center mt-12" data-aos="fade-up">
                <a href="news.php" class="inline-block px-8 py-4 gradient-bg text-white rounded-lg font-semibold hover-scale shadow-lg">
                    Lihat Semua Berita <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Gallery Section -->
<section id="gallery" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Galeri Kegiatan</h2>
            <div class="w-24 h-1 gradient-bg mx-auto rounded-full"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if ($gallery && count($gallery) > 0): ?>
                <?php foreach ($gallery as $index => $item): ?>
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg card-hover" data-aos="zoom-in" data-aos-delay="<?php echo $index * 100; ?>">
                        <div class="aspect-w-16 aspect-h-12 bg-gray-200">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="w-full h-64 object-cover">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                            <div class="p-6 text-white">
                                <h4 class="font-bold text-xl mb-2"><?php echo htmlspecialchars($item['title']); ?></h4>
                                <p class="text-sm text-gray-200"><?php echo htmlspecialchars($item['description'] ?? ''); ?></p>
                                <?php if ($item['tanggal']): ?>
                                    <p class="text-xs text-gray-300 mt-2">
                                        <i class="fas fa-calendar mr-1"></i><?php echo date('d M Y', strtotime($item['tanggal'])); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-images text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada foto galeri</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Hubungi Kami</h2>
            <div class="w-24 h-1 gradient-bg mx-auto rounded-full"></div>
        </div>
        
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="grid md:grid-cols-2">
                    <!-- Contact Info -->
                    <div class="bg-gradient-to-br from-blue-900 to-blue-700 p-10 text-white" data-aos="fade-right">
                        <h3 class="text-2xl font-bold mb-6">Informasi Kontak</h3>
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-2xl mr-4 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold mb-1">Alamat</h4>
                                    <p class="text-gray-100">Jl. Kampus No. 123, Kota, Provinsi 12345</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-phone text-2xl mr-4 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold mb-1">Telepon</h4>
                                    <p class="text-gray-100">+62 123 456 789</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-envelope text-2xl mr-4 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold mb-1">Email</h4>
                                    <p class="text-gray-100">info@lab.ac.id</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-clock text-2xl mr-4 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold mb-1">Jam Operasional</h4>
                                    <p class="text-gray-100">Senin - Jumat: 08:00 - 17:00</p>
                                    <p class="text-gray-100">Sabtu: 08:00 - 13:00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Form -->
                    <div class="p-10" data-aos="fade-left">
                        <form id="contactForm" action="api/contact.php" method="POST">
                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Nama</label>
                                <input type="text" name="name" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Email</label>
                                <input type="email" name="email" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Subjek</label>
                                <input type="text" name="subject" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition">
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 font-semibold mb-2">Pesan</label>
                                <textarea name="message" rows="4" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition"></textarea>
                            </div>
                            <button type="submit" class="w-full gradient-bg text-white font-semibold py-3 rounded-lg hover-scale shadow-lg">
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Contact Form Submission
document.getElementById('contactForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('api/contact.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Pesan berhasil dikirim! Terima kasih.');
            this.reset();
        } else {
            alert('Terjadi kesalahan: ' + result.message);
        }
    } catch (error) {
        alert('Terjadi kesalahan saat mengirim pesan.');
    }
});
</script>

<?php include 'includes/footer.php'; ?>
