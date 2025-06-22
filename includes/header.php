<header class="bg-white text-primary shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-2 flex justify-between items-center">
            <!-- Logo with animation -->
            <a href="index.html" class="flex items-center space-x-2 hover:scale-105 transition-transform duration-300">
                <img src="img/logo-1.png" class="w-16 h-16 sm:w-20 sm:h-20 transition-all duration-500 hover:rotate-6" alt="Logo">
                <p class="text-xl sm:text-2xl font-bold tracking-wide font-sans text-gray-800 uppercase transform hover:translate-x-1 transition-transform duration-300">
                    Retroviral Solution
                </p>
            </a>
            
            <!-- Desktop Navigation -->
            <nav class="hidden md:flex space-x-6">
                <a href="index.html" class="relative group hover:text-secondary transition-colors duration-300">
                    Home
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-secondary transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="index.html#about" class="relative group hover:text-secondary transition-colors duration-300">
                    About
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-secondary transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="index.html#services" class="relative group hover:text-secondary transition-colors duration-300">
                    Services
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-secondary transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="<?php echo APPURL; ?>/blog.php" class="relative group hover:text-secondary transition-colors duration-300">
                    Blog
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-secondary transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="index.html#partners" class="relative group hover:text-secondary transition-colors duration-300">
                    Partners
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-secondary transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="index.html#faq" class="relative group hover:text-secondary transition-colors duration-300">
                    FAQ
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-secondary transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="index.html#contact" onclick="openModal('contactModal')" class="relative group hover:text-secondary transition-colors duration-300">
                    Contact
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-secondary transition-all duration-300 group-hover:w-full"></span>
                </a>
            </nav>
            
            <!-- Mobile Menu Button -->
            <button id="mobileMenuButton" class="md:hidden text-2xl text-primary focus:outline-none transform transition-transform duration-300 hover:scale-110">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <!-- Mobile Menu - Now sliding from left -->
        <div id="mobileMenu" class="md:hidden fixed inset-y-0 left-0 w-64 bg-white shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out z-50">
            <div class="container mx-auto px-4 py-6 flex flex-col space-y-4 h-full">
                <!-- Close Button -->
                <div class="flex justify-end">
                    <button id="mobileMenuClose" class="text-2xl text-gray-500 hover:text-primary">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Menu Items -->
                <a href="index.html" class="py-3 px-4 hover:bg-gray-50 rounded-lg transition-all duration-300 flex items-center">
                    <i class="fas fa-home mr-3 text-secondary w-5 text-center"></i> 
                    <span>Home</span>
                </a>
                <a href="index.html#about" class="py-3 px-4 hover:bg-gray-50 rounded-lg transition-all duration-300 flex items-center">
                    <i class="fas fa-info-circle mr-3 text-secondary w-5 text-center"></i> 
                    <span>About</span>
                </a>
                <a href="index.html#services" class="py-3 px-4 hover:bg-gray-50 rounded-lg transition-all duration-300 flex items-center">
                    <i class="fas fa-pills mr-3 text-secondary w-5 text-center"></i> 
                    <span>Services</span>
                </a>
                <a href="<?php echo APPURL; ?>/blog.php" class="py-3 px-4 hover:bg-gray-50 rounded-lg transition-all duration-300 flex items-center">
                    <i class="fas fa-newspaper mr-3 text-secondary w-5 text-center"></i> 
                    <span>Blog</span>
                </a>
                <a href="index.html#partners" class="py-3 px-4 hover:bg-gray-50 rounded-lg transition-all duration-300 flex items-center">
                    <i class="fas fa-handshake mr-3 text-secondary w-5 text-center"></i> 
                    <span>Partners</span>
                </a>
                <a href="index.html#faq" class="py-3 px-4 hover:bg-gray-50 rounded-lg transition-all duration-300 flex items-center">
                    <i class="fas fa-question-circle mr-3 text-secondary w-5 text-center"></i> 
                    <span>FAQ</span>
                </a>
                <a href="index.html#contact" onclick="openModal('contactModal')" class="py-3 px-4 hover:bg-gray-50 rounded-lg transition-all duration-300 flex items-center">
                    <i class="fas fa-envelope mr-3 text-secondary w-5 text-center"></i> 
                    <span>Contact</span>
                </a>
            </div>
        </div>
        
        <!-- Overlay (hidden by default) -->
        <div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>
    </header>