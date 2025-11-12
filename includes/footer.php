    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-12 pb-6">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- About -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center overflow-hidden p-2">
                            <img src="../img/polinema.png" alt="Logo" class="w-full h-full object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <i class="fas fa-flask hidden"></i>
                        </div>
                        <h3 class="text-xl font-bold">Lab Kampus</h3>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Laboratorium unggulan yang menghasilkan penelitian dan inovasi berkualitas tinggi.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="index.php#profil" class="text-gray-400 hover:text-purple-400 transition">Profil</a></li>
                        <li><a href="index.php#scope" class="text-gray-400 hover:text-purple-400 transition">Scope</a></li>
                        <li><a href="index.php#blueprint" class="text-gray-400 hover:text-purple-400 transition">Blueprint</a></li>
                        <li><a href="index.php#struktur" class="text-gray-400 hover:text-purple-400 transition">Struktur</a></li>
                        <li><a href="index.php#gallery" class="text-gray-400 hover:text-purple-400 transition">Galeri</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><i class="fas fa-map-marker-alt mr-2"></i>Jl. Kampus No. 123</li>
                        <li><i class="fas fa-phone mr-2"></i>+62 123 456 789</li>
                        <li><i class="fas fa-envelope mr-2"></i>info@lab.ac.id</li>
                    </ul>
                </div>
                
                <!-- Social Media -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-6 text-center text-sm text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> Laboratorium Kampus. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- AOS Animation Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    </script>
</body>
</html>
