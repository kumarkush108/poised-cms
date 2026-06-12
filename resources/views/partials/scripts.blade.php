<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Library JS -->
<script src="{{ asset('assets/lib/wow/wow.min.js') }}"></script>

<script src="{{ asset('assets/lib/easing/easing.min.js') }}"></script>

<script src="{{ asset('assets/lib/waypoints/waypoints.min.js') }}"></script>

<script src="{{ asset('assets/lib/counterup/counterup.min.js') }}"></script>

<script src="{{ asset('assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>

<!-- Slick Slider -->
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<!-- Main Custom JS -->
<script src="{{ asset('assets/js/main.js') }}"></script>


<!-- Brand Logo Slider -->
<script>
    $('.logo-slider').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 0,
        speed: 5000,
        cssEase: 'linear',
        infinite: true,
        arrows: false,
        dots: false,
        pauseOnHover: false,

        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 4
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 2
                }
            }
        ]
    });
</script>


<!-- EV Video Parallax Effect -->
<script>

    window.addEventListener("scroll", function () {

        const section = document.querySelector(".parallax-section");

        const video = document.querySelector(".ev-bg-video");

        if (section && video) {

            let scrollPosition = window.pageYOffset;

            let sectionTop = section.offsetTop;

            let distance = scrollPosition - sectionTop;

            video.style.transform =
                `translate(-50%, calc(-50% + ${distance * 0.2}px))`;

        }

    });

</script>


@stack('scripts')