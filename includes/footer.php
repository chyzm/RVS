<footer id="contact" class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <img src="img/logo-1.png" class="w-16 h-16 mr-2" alt="Retroviral Solutions Logo">
                        <h3 class="text-xl font-bold">Retroviral Solution</h3>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Restoring reliable access to antiretroviral medications across Nigeria through innovative logistics solutions.
                    </p>
                    <div class="flex space-x-4">
                        <!--<a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-twitter"></i>
                        </a>-->
                        <a href="https://www.linkedin.com/search/results/all/?fetchDeterministicClustersOnly=true&heroEntityKey=urn%3Ali%3Aorganization%3A107609553&keywords=retroviral%20solution&origin=RICH_QUERY_SUGGESTION&position=0&searchId=c8c673c5-b1d7-4fbf-a878-f2c121455452&sid=uw%3B&spellCorrectionEnabled=false" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="index.html" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="index.html#about" class="text-gray-400 hover:text-white transition">About</a></li>
                        <li><a href="index.html#services" class="text-gray-400 hover:text-white transition">Our Services</a></li>
                        <li><a href="<?php echo APPURL; ?>/blog.php" class="text-gray-400 hover:text-white transition">Blog</a></li>
                        <li><a href="index.html#partners" class="text-gray-400 hover:text-white transition">Our Partners</a></li>
                        <li><a href="index.html#faq" class="text-gray-400 hover:text-white transition">FAQ</a></li>
                        <li><a href="index.html#contact" class="text-gray-400 hover:text-white transition">Contact Us</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Contact Information</h3>
                    <p class="text-gray-400 mb-2"><i class="fas fa-map-marker-alt mr-2"></i>  4 Aminu Kanu Crescent, Omega Centre, Wuse 2, Abuja, Nigeria.
                    </p>
                    <p class="text-gray-400 mb-2"><i class="fas fa-phone mr-2"></i> +234 800 000 0000</p>
                    <p class="text-gray-400 mb-2"><i class="fas fa-envelope mr-2"></i> info@retroviralsolution.org</p>
                    <p class="text-gray-400"><i class="fas fa-clock mr-2"></i> Mon-Fri: 8AM - 5PM WAT</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Retroviral Solution. All rights reserved.</p>
                <p>Developed by <a href="https://durieltech.vercel.app" class="text-yellow-300" target="_blank">Duriel Tech</a>.</p>
            </div>
        </div>
    </footer>

    <script>
    // Mobile menu toggle with left-slide animation
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenuClose = document.getElementById('mobileMenuClose');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    
    function openMobileMenu() {
        mobileMenuOverlay.classList.remove('hidden');
        mobileMenu.classList.remove('-translate-x-full');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMobileMenu() {
        mobileMenu.classList.add('-translate-x-full');
        mobileMenuOverlay.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Wait for animation to complete before hiding overlay
        setTimeout(() => {
            mobileMenuOverlay.classList.add('hidden');
        }, 300);
    }
    
    // Open menu when hamburger is clicked
    mobileMenuButton.addEventListener('click', openMobileMenu);
    
    // Close menu when X is clicked
    mobileMenuClose.addEventListener('click', closeMobileMenu);
    
    // Close menu when overlay is clicked
    mobileMenuOverlay.addEventListener('click', closeMobileMenu);
    
    // Close menu when a link is clicked
    document.querySelectorAll('#mobileMenu a').forEach(link => {
        link.addEventListener('click', closeMobileMenu);
    });
    
    // Close menu when ESC key is pressed
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !mobileMenu.classList.contains('-translate-x-full')) {
            closeMobileMenu();
        }
    });



        // Modal control functions
        function openModal(modalId) {
            document.getElementById('modalBackdrop').classList.remove('hidden');
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById('modalBackdrop').classList.add('hidden');
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking on backdrop
        document.getElementById('modalBackdrop').addEventListener('click', function() {
            document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                modal.classList.add('hidden');
            });
            this.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });

        // Form submission handlers
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Here you would typically send the form data to your server
            alert('Thank you for your message! We will contact you soon.');
            closeModal('contactModal');
            this.reset();
        });

        document.getElementById('partnerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Here you would typically send the form data to your server
            alert('Thank you for your partnership request! We will review it and get back to you.');
            closeModal('partnerModal');
            this.reset();
        });


           // FAQ toggle functionality with animations
    document.querySelectorAll('.faq-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            const icon = button.querySelector('i');
            
            // Toggle the 'active' class on the button
            button.classList.toggle('active');
            
            // Toggle content visibility
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
                icon.classList.remove('rotate-180');
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
                icon.classList.add('rotate-180');
            }
            
            // Close other open FAQs
            document.querySelectorAll('.faq-content').forEach(item => {
                if (item !== content && item.style.maxHeight) {
                    item.style.maxHeight = null;
                    item.previousElementSibling.querySelector('i').classList.remove('rotate-180');
                    item.previousElementSibling.classList.remove('active');
                }
            });
        });
        
        // Initialize all FAQs as closed
        const content = button.nextElementSibling;
        content.style.maxHeight = null;
    });




    // Initialize animations on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add(entry.target.dataset.animation);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('[class*="animate-"]').forEach(el => {
        const animationClass = Array.from(el.classList).find(c => c.startsWith('animate-'));
        if (animationClass) {
            el.dataset.animation = animationClass;
            el.classList.remove(animationClass);
            observer.observe(el);
        }
    });

    // Initialize count-up animations
  function animateCountUp() {
    const counters = document.querySelectorAll('.animate-count-up');
    const speed = 200;
    
    counters.forEach(counter => {
      const target = +counter.getAttribute('data-target');
      const count = +counter.innerText;
      const increment = target / speed;
      
      if (count < target) {
        counter.innerText = Math.ceil(count + increment);
        setTimeout(animateCountUp, 1);
      } else {
        counter.innerText = target;
      }
    });
  }
  
  // Trigger animations when element is in view
  const observer1 = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add(entry.target.dataset.animation);
        if (entry.target.classList.contains('animate-count-up')) {
          animateCountUp();
        }
        observer1.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });
  
  document.querySelectorAll('[class*="animate-"]').forEach(el => {
    observer1.observe(el);
  });




// Enhanced Smooth Scrolling for all anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    e.preventDefault();
    
    const targetId = this.getAttribute('href');
    if (targetId === '#') return;
    
    const targetElement = document.querySelector(targetId);
    if (targetElement) {
      targetElement.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
      
      // Update URL without jumping
      history.pushState(null, null, targetId);
    }
  });
});

    </script>