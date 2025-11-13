<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Laboratorium Kampus</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .gradient-bg {
            background: blue;
        }
        
        .gradient-text {
            background: blue;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hover-scale {
            transition: transform 0.3s ease;
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: blue;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .blob {
            background: blue;
            filter: blur(40px);
            opacity: 0.5;
            animation: blob 7s infinite;
        }
        
        @keyframes blob {
            0%, 100% {
                border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            }
            50% {
                border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 gradient-bg rounded-lg flex items-center justify-center">
                        <i class="fas fa-flask text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold gradient-text">Lab Kampus</h1>
                        <p class="text-xs text-gray-600">Innovation Center</p>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-6">
                    <a href="index.php" class="nav-link text-gray-700 hover:text-purple-600">Beranda</a>
                    <a href="index.php#profil" class="nav-link text-gray-700 hover:text-purple-600">Profil</a>
                    <a href="index.php#scope" class="nav-link text-gray-700 hover:text-purple-600">Scope</a>
                    <a href="index.php#blueprint" class="nav-link text-gray-700 hover:text-purple-600">Blueprint</a>
                    <a href="index.php#struktur" class="nav-link text-gray-700 hover:text-purple-600">Struktur</a>
                    <a href="index.php#gallery" class="nav-link text-gray-700 hover:text-purple-600">Galeri</a>
                    <a href="admin/login.php" class="px-4 py-2 gradient-bg text-white rounded-lg hover-scale">
                        <i class="fas fa-lock mr-2"></i>Admin
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4">
                <a href="index.php" class="block py-2 text-gray-700 hover:text-purple-600">Beranda</a>
                <a href="index.php#profil" class="block py-2 text-gray-700 hover:text-purple-600">Profil</a>
                <a href="index.php#visi-misi" class="block py-2 text-gray-700 hover:text-purple-600">Visi & Misi</a>
                <a href="index.php#scope" class="block py-2 text-gray-700 hover:text-purple-600">Scope</a>
                <a href="index.php#blueprint" class="block py-2 text-gray-700 hover:text-purple-600">Blueprint</a>
                <a href="index.php#struktur" class="block py-2 text-gray-700 hover:text-purple-600">Struktur</a>
                <a href="index.php#gallery" class="block py-2 text-gray-700 hover:text-purple-600">Galeri</a>
                <a href="index.php#contact" class="block py-2 text-gray-700 hover:text-purple-600">Kontak</a>
                <a href="admin/login.php" class="block py-2 text-purple-600 font-semibold">
                    <i class="fas fa-lock mr-2"></i>Admin Login
                </a>
            </div>
        </div>
    </nav>
    
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
        
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
