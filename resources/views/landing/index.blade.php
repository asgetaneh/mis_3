<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>JU MIS - Home page</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- Favicons -->
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="{{ asset('assets/landing-page/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon" />

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/landing-page/assets/vendor/aos/aos.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/landing-page/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link href="{{ asset('assets/landing-page/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/landing-page/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/landing-page/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/landing-page/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/landing-page/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet" />

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/landing-page/assets/css/style.css') }}" rel="stylesheet" />

</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <h1 class="logo me-auto">
                <a href="{{ url('/') }}"><img src="{{ asset('assets/landing-page/assets/img/logo.png') }}"
                        class="img-fluid" alt="" /> JU
                    MIS</a>
            </h1>
            <!-- Uncomment below if you prefer to use an image logo -->
            <!-- <a href="index.html" class="logo me-auto"><img src="{{ asset('assets/landing-page/assets/img/logo.png') }}" alt="" class="img-fluid"></a>-->

            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="#">Home</a></li>
                    {{-- <li>
                        <a class="nav-link scrollto" href="#">Information</a>
                    </li>
                    <li><a class="nav-link scrollto" href="#">Plan</a></li> --}}
                    <li><a class="nav-link scrollto" href="{{ route('feedback') }}">Feedback</a></li>

                    {{-- <li class="dropdown">
              <a href="#"
                ><span>Switch Language</span> <i class="bi bi-chevron-down"></i
              ></a>
              <ul>
                <li><a href="#">English</a></li>
                <li><a href="#">Amharic</a></li>
                <li><a href="#">Afan Oromo</a></li>
              </ul>
            </li> --}}

                    <li>
                        <a class="getstarted scrollto" href="{{ route('login') }}">Log in <i
                                class="bx bx-log-in"></i></a>
                    </li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
            <!-- .navbar -->
        </div>
    </header>
    <!-- End Header -->

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1"
                    data-aos="fade-up" data-aos-delay="200">
                    <h1>Vision 2030</h1>
                    <h2>
                        Aspiring to be one of the leading Community-based research
                        universities in Africa and renowned in the world by 2030
                    </h2>
                    <div class="d-flex justify-content-center justify-content-lg-start">
                        <a href="#about" class="btn-get-started scrollto">Read more</a>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
                    <img src="{{ asset('assets/landing-page/assets/img/smis.png') }}" class="img-fluid animated"
                        alt="" />
                </div>
            </div>
        </div>
    </section>
    <!-- End Hero -->

    <main id="main">
        <!-- ======= About Us Section ======= -->
        <section id="about" class="about">
            <div class="container" data-aos="fade-up">
                <div class="section-title">
                    <h2>About MIS</h2>
                </div>

                <div class="row content">
                    <div class="col-lg-6">
                        <p>
                            Management information systems (MIS) is the study and
                            application of information systems that organizations use for
                            data access, management, and analytics.
                        </p>
                        <ul>
                            <li>
                                <i class="ri-check-double-line"></i> Allow businesses to have
                                access to accurate data and powerful analytical tools
                            </li>
                            <li>
                                <i class="ri-check-double-line"></i> Identify problems and
                                opportunities quickly and make decisions accordingly
                            </li>
                            <li>
                                <i class="ri-check-double-line"></i> Gathers data from various
                                sources and processes it to provide information tailored to
                                the managers' and their staff's needs.
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-6 pt-4 pt-lg-0">
                        <p>
                            While businesses use different types of MISs, they all share one
                            common goal: to provide managers with the information to make
                            better decisions. In today's fast-paced business environment,
                            having access to accurate and timely information is critical for
                            success. MIS allows managers to track performance indicators,
                            identify trends, and make informed decisions about where to
                            allocate resources.
                        </p>
                        <!-- <a href="#" class="btn-learn-more">Learn More</a> -->
                    </div>
                </div>
            </div>
        </section>
        <!-- End About Us Section -->

        <!-- ======= Services Section ======= -->
        <section id="services" class="services section-bg">
            <div class="container" data-aos="fade-up">
                <div class="section-title">
                    <h2>Benefits</h2>
                    <p>
                        Management information systems (MIS) is the study and
                        application of information systems that organizations use for
                        data access, management, and analytics.
                    </p>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bx-edit-alt"></i></div>
                            <h4>Planning</h4>
                            <p>
                                Flexible and user-friendly system to
                                manage and organize the university's plan and reporting.
                            </p>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in"
                        data-aos-delay="200">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bx-file"></i></div>
                            <h4>Reporting</h4>
                            <p>
                                Automated system of
                                planning and reporting which helps the organization to achieve its goal.
                            </p>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 d-flex align-items-stretch mt-4 mt-xl-0" data-aos="zoom-in"
                        data-aos-delay="300">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bx-tachometer"></i></div>
                            <h4>Visualization</h4>
                            <p>
                                Graphical representations, drill-down capabilities, performance
                                measure indexing capabilities, and automated task scheduling.
                            </p>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 d-flex align-items-stretch mt-4 mt-xl-0" data-aos="zoom-in"
                        data-aos-delay="400">
                        <div class="icon-box">
                            <div class="icon"><i class="bx bx-layer"></i></div>
                            <h4>Information</h4>
                            <p>
                                Collect and organize plan and report information from individual employees
                                to the university as a whole.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Services Section -->

    </main>
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Useful Links</h4>
                        <ul>
                            <li>
                                <i class="bx bx-chevron-right"></i> <a href="{{ route('login') }}">Login</a>
                            </li>
                            <li>
                                <i class="bx bx-chevron-right"></i> <a href="https://ju.edu.et" target="_blank">JU
                                    Website</a>
                            </li>

                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Social</h4>
                        <div class="social-links mt-3">
                            <a href="https://twitter.com/jimmauniv" target="_blank" class="twitter"><i class="bx bxl-twitter"></i></a>
                            <a href="https://www.facebook.com/JimmaUniv" target="_blank" class="facebook"><i class="bx bxl-facebook"></i></a>
                            <a href="https://www.linkedin.com/school/jimma-university" target="_blank" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container footer-bottom clearfix">
            <div class="copyright">MIS Application
                &copy; <strong><span>Jimma University</span></strong>.
            </div>
            <div class="credits">
                Developed by <a style="color: #fff; font-weight: bolder; text-decoration: underline;"
                    href="https://ju.edu.et/ict/" target="_blank">JU-ICT Directorate</a>
            </div>
        </div>
    </footer>
    <!-- End Footer -->

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/landing-page/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/landing-page/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/landing-page/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/landing-page/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/landing-page/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/landing-page/assets/vendor/waypoints/noframework.waypoints.js') }}"></script>
    <script src="{{ asset('assets/landing-page/assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/landing-page/assets/js/main.js') }}"></script>

    @if (session()->has('success'))
        <script>
            var notyf = new Notyf({dismissible: true})
            notyf.success('{{ session('success') }}')
        </script>
    @endif
</body>

</html>
