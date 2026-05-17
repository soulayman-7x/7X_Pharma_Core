document.addEventListener("DOMContentLoaded", () => {
    // --- State ---
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const totalSlides = slides.length;
    let isAnimating = false;
    let uiTimeout;

    // --- UI Elements ---
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const currentSlideEl = document.getElementById('currentSlide');
    const totalSlidesEl = document.getElementById('totalSlides');
    const progressBar = document.getElementById('progressBar');
    const dotsContainer = document.getElementById('dotsContainer');
    const presentationUi = document.getElementById('presentationUi');
    const fullscreenBtn = document.getElementById('fullscreenBtn');

    // --- Initialization ---
    totalSlidesEl.textContent = totalSlides < 10 ? `0${totalSlides}` : totalSlides;
    
    // Create dots
    slides.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });
    
    const dots = document.querySelectorAll('.dot');

    // Setup initial state
    gsap.set(slides, { opacity: 0, visibility: 'hidden', scale: 0.9 });
    gsap.set(slides[0], { opacity: 1, visibility: 'visible', scale: 1 });
    slides[0].classList.add('active');
    updateUI();

    // Setup Typed.js if needed on first slide
    if (document.getElementById('typed-text')) {
        new Typed('#typed-text', {
            strings: ['A Premium MVC Architecture', 'Scalable Pharmacy Management', 'Built with PHP & Tailwind'],
            typeSpeed: 50,
            backSpeed: 30,
            backDelay: 2000,
            loop: true
        });
    }

    // --- Core Slide Logic ---
    function goToSlide(index) {
        if (isAnimating || index === currentSlide || index < 0 || index >= totalSlides) return;
        isAnimating = true;

        const previous = currentSlide;
        const next = index;
        const direction = next > previous ? 1 : -1;

        // Animate out current slide
        const currentSlideEl = slides[previous];
        const nextSlideEl = slides[next];

        // GSAP Timeline for smooth transition
        const tl = gsap.timeline({
            onComplete: () => {
                currentSlideEl.classList.remove('active');
                nextSlideEl.classList.add('active');
                currentSlide = next;
                isAnimating = false;
                updateUI();
                animateSlideContent(nextSlideEl);
            }
        });

        // Hide current
        tl.to(currentSlideEl, {
            opacity: 0,
            scale: 0.95,
            x: direction * -100, // slide out
            duration: 0.6,
            ease: "power2.inOut"
        });

        // Show next
        gsap.set(nextSlideEl, { opacity: 0, visibility: 'visible', scale: 1.05, x: direction * 100 });
        tl.to(nextSlideEl, {
            opacity: 1,
            scale: 1,
            x: 0,
            duration: 0.6,
            ease: "power2.out"
        }, "-=0.3");
    }

    function animateSlideContent(slide) {
        // Animate inner elements of the slide
        const elements = slide.querySelectorAll('.gsap-anim');
        if (elements.length) {
            gsap.fromTo(elements, 
                { y: 30, opacity: 0 }, 
                { y: 0, opacity: 1, duration: 0.6, stagger: 0.1, ease: "back.out(1.7)", clearProps: "all" }
            );
        }
    }

    function nextSlide() {
        if (currentSlide < totalSlides - 1) goToSlide(currentSlide + 1);
    }

    function prevSlide() {
        if (currentSlide > 0) goToSlide(currentSlide - 1);
    }

    // --- UI Updates ---
    function updateUI() {
        // Update Counter
        const displayNum = currentSlide + 1;
        currentSlideEl.textContent = displayNum < 10 ? `0${displayNum}` : displayNum;
        
        // Update Progress
        const progress = ((currentSlide) / (totalSlides - 1)) * 100;
        progressBar.style.width = `${progress}%`;

        // Update Dots
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlide);
        });

        // Update Buttons
        prevBtn.disabled = currentSlide === 0;
        nextBtn.disabled = currentSlide === totalSlides - 1;
    }

    // --- Event Listeners ---
    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);

    // Keyboard Navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight' || e.key === 'Space') {
            nextSlide();
        } else if (e.key === 'ArrowLeft') {
            prevSlide();
        }
    });

    // Mouse Wheel Navigation (optional, with debounce)
    let wheelTimeout;
    document.addEventListener('wheel', (e) => {
        clearTimeout(wheelTimeout);
        wheelTimeout = setTimeout(() => {
            if (e.deltaY > 50) nextSlide();
            else if (e.deltaY < -50) prevSlide();
        }, 100);
    });

    // Auto-hide UI
    function showUI() {
        presentationUi.classList.remove('hidden');
        fullscreenBtn.style.opacity = '1';
        document.body.style.cursor = 'default';
        clearTimeout(uiTimeout);
        uiTimeout = setTimeout(hideUI, 3000);
    }

    function hideUI() {
        presentationUi.classList.add('hidden');
        fullscreenBtn.style.opacity = '0';
        document.body.style.cursor = 'none';
    }

    document.addEventListener('mousemove', showUI);
    uiTimeout = setTimeout(hideUI, 3000);

    // Fullscreen Toggle
    fullscreenBtn.addEventListener('click', () => {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                console.log(`Error attempting to enable fullscreen: ${err.message}`);
            });
            fullscreenBtn.innerHTML = '<i class="ri-fullscreen-exit-line"></i>';
        } else {
            document.exitFullscreen();
            fullscreenBtn.innerHTML = '<i class="ri-fullscreen-line"></i>';
        }
    });
    
    // Initial content animation for first slide
    animateSlideContent(slides[0]);
});
