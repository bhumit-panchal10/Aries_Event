<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script src="{{ asset('assets/front/js/scripts.js') }}" type="text/javascript"></script>

<script>
    // Restart animation when slide changes
    const carousel = document.querySelector('#heroCarousel');
    carousel.addEventListener('slide.bs.carousel', (e) => {
        const animElems = e.relatedTarget.querySelectorAll('.animate__animated');
        animElems.forEach(el => {
            el.classList.remove('animate__fadeInDown', 'animate__fadeInUp', 'animate__zoomIn');
            void el.offsetWidth; // trigger reflow
            el.classList.add('animate__fadeInDown', 'animate__fadeInUp', 'animate__zoomIn');
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    const swiper = new Swiper(".testimonial-swiper", {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        grabCursor: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            768: {
                slidesPerView: 2
            },
            1024: {
                slidesPerView: 3
            },
        },
    });
</script>
