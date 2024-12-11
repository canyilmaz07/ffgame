// main.js

document.addEventListener('DOMContentLoaded', () => {
    // Initialize main timeline
    const tl = gsap.timeline();

    // Sidebar animations
    tl.to('.sidebar', {
        opacity: 1,
        duration: 0.5
    })
        .to('.store-title', {
            opacity: 1,
            y: 0,
            duration: 0.3
        })
        .to('.menu-item', {
            opacity: 1,
            y: 0,
            duration: 0.3,
            stagger: 0.1
        })
        .to('.help-card', {
            opacity: 1,
            y: 0,
            duration: 0.3
        })
        .to('.user-profile', {
            opacity: 1,
            y: 0,
            duration: 0.3
        })

        // Main content animations - starting after sidebar
        .to('.welcome-text', {
            opacity: 0,
            y: -20,
            duration: 0,
        })
        .to('.welcome-text', {
            opacity: 1,
            y: 0,
            duration: 0.3
        })
        .to('.search-container', {
            opacity: 0,
            y: -20,
            duration: 0
        })
        .to('.search-container', {
            opacity: 1,
            y: 0,
            duration: 0.3
        })
        .to('.header-actions', {
            opacity: 0,
            y: -20,
            duration: 0
        })
        .to('.header-actions', {
            opacity: 1,
            y: 0,
            duration: 0.3
        })
        .to('.slider-container', {
            opacity: 0,
            y: -20,
            duration: 0
        })
        .to('.slider-container', {
            opacity: 1,
            y: 0,
            duration: 0.5
        })
        .to('.section-title', {
            opacity: 0,
            y: -20,
            duration: 0
        })
        .to('.section-title', {
            opacity: 1,
            y: 0,
            duration: 0.3
        })
        .to('.game-card', {
            opacity: 0,
            y: -20,
            duration: 0
        })
        .to('.game-card', {
            opacity: 1,
            y: 0,
            duration: 0.3,
            stagger: 0.1
        });

    // Initialize slider variables
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const progressBars = document.querySelectorAll('.progress-bar');
    const slideCount = slides.length;

    // Function to handle slide transitions
    function nextSlide() {
        // Fade out current slide
        gsap.to(slides[currentSlide], {
            opacity: 0,
            duration: 0.5
        });
        progressBars[currentSlide].classList.remove('active');

        // Update current slide index
        currentSlide = (currentSlide + 1) % slideCount;

        // Fade in next slide
        gsap.to(slides[currentSlide], {
            opacity: 1,
            duration: 0.5
        });
        progressBars[currentSlide].classList.add('active');
    }

    // Add click events to progress bars
    progressBars.forEach((bar, index) => {
        bar.addEventListener('click', () => {
            if (currentSlide !== index) {
                // Fade out current slide
                gsap.to(slides[currentSlide], {
                    opacity: 0,
                    duration: 0.5
                });
                progressBars[currentSlide].classList.remove('active');

                // Update current slide
                currentSlide = index;

                // Fade in selected slide
                gsap.to(slides[currentSlide], {
                    opacity: 1,
                    duration: 0.5
                });
                progressBars[currentSlide].classList.add('active');
            }
        });
    });

    // Start automatic slider
    setInterval(nextSlide, 5000);
});

// Kategorilere tıklama işleyicisi
document.querySelector('.menu-items .menu-item:nth-child(2)').addEventListener('click', function (e) {
    e.preventDefault();

    // Menu item active state
    document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
    this.classList.add('active');

    const tl = gsap.timeline();

    // Ana sayfa içeriğini gizle
    tl.to('.homepage-content', {
        opacity: 0,
        y: -20,
        duration: 0.3,
        onComplete: () => {
            document.querySelector('.homepage-content').style.display = 'none';
            document.querySelector('.categories-container').style.display = 'block';
            // Display'i block yaptıktan sonra opacity'yi 0'a set et
            gsap.set('.categories-container', { opacity: 0 });
            gsap.set('.category-card', { opacity: 0, y: 20 });
        }
    })
        // Kategori container'ı göster
        .to('.categories-container', {
            opacity: 1,
            duration: 0.3
        })
        // Kategori kartlarını sırayla göster
        .to('.category-card', {
            opacity: 1,
            y: 0,
            duration: 0.3,
            stagger: 0.05 // Kartlar arası süreyi azalttık
        });
});

// Ana sayfaya dönüş işleyicisi
document.querySelector('.menu-items .menu-item:first-child').addEventListener('click', function (e) {
    e.preventDefault();

    document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
    this.classList.add('active');

    const tl = gsap.timeline();

    // Tüm containerları kontrol et ve görüneni bul
    const containers = ['.homepage-content', '.categories-container', '.library-container', '.discounts-container'];
    const visibleContainer = containers.find(container =>
        document.querySelector(container).style.display !== 'none'
    ) || '.homepage-content';

    tl.to(visibleContainer, {
        opacity: 0,
        y: -20,
        duration: 0.3,
        onComplete: () => {
            // Tüm containerları gizle
            containers.forEach(container => {
                document.querySelector(container).style.display = 'none';
            });
            document.querySelector('.homepage-content').style.display = 'block';
            gsap.set('.homepage-content', { opacity: 0 });
            gsap.set('.game-card', { opacity: 0, y: 20 });
        }
    })
        .to('.homepage-content', {
            opacity: 1,
            duration: 0.3
        })
        .to('.slider-container', {
            opacity: 1,
            y: 0,
            duration: 0.3
        })
        .to('.game-card', {
            opacity: 1,
            y: 0,
            duration: 0.3,
            stagger: 0.05
        });
});

// Kütüphane sayfasına geçiş
document.querySelector('.menu-items .menu-item:nth-child(3)').addEventListener('click', function (e) {
    e.preventDefault();

    document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
    this.classList.add('active');

    const tl = gsap.timeline();

    // Tüm containerları kontrol et ve görüneni bul
    const containers = ['.homepage-content', '.categories-container', '.library-container', '.discounts-container'];
    const visibleContainer = containers.find(container =>
        document.querySelector(container).style.display !== 'none'
    ) || '.homepage-content';

    tl.to(visibleContainer, {
        opacity: 0,
        y: -20,
        duration: 0.3,
        onComplete: () => {
            // Tüm containerları gizle
            containers.forEach(container => {
                document.querySelector(container).style.display = 'none';
            });
            document.querySelector('.library-container').style.display = 'block';
            gsap.set('.library-container', { opacity: 0 });
            gsap.set('.library-card', { opacity: 0, y: 20 });
        }
    })
        .to('.library-container', {
            opacity: 1,
            y: 0,
            duration: 0.3
        })
        .to('.library-card', {
            opacity: 1,
            y: 0,
            duration: 0.3,
            stagger: 0.05
        });
});

// İndirimler sayfasına geçiş
document.querySelector('.menu-items .menu-item:nth-child(4)').addEventListener('click', function (e) {
    e.preventDefault();

    document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
    this.classList.add('active');

    const tl = gsap.timeline();

    // Tüm containerları kontrol et ve görüneni bul
    const containers = ['.homepage-content', '.categories-container', '.library-container', '.discounts-container'];
    const visibleContainer = containers.find(container =>
        document.querySelector(container).style.display !== 'none'
    ) || '.homepage-content';

    tl.to(visibleContainer, {
        opacity: 0,
        y: -20,
        duration: 0.3,
        onComplete: () => {
            // Tüm containerları gizle
            containers.forEach(container => {
                document.querySelector(container).style.display = 'none';
            });
            document.querySelector('.discounts-container').style.display = 'block';
            gsap.set('.discounts-container', { opacity: 0 });
            gsap.set('.discount-card', { opacity: 0, y: 20 });
        }
    })
        .to('.discounts-container', {
            opacity: 1,
            y: 0,
            duration: 0.3
        })
        .to('.discount-card', {
            opacity: 1,
            y: 0,
            duration: 0.3,
            stagger: 0.05
        });
});