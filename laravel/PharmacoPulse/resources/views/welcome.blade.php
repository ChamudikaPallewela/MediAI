<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="images/favicon.png" type="">

  <title> MediAI </title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />

</head>

<body>

  <div class="hero_area">

    <div class="hero_bg_box">
      <img src="images/hero-bg.png" alt="">
    </div>

    <!-- header section strats -->
    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="{{ route('welcome') }}">
          <img src="img/logo.png" alt="Logo" style="height: 60px; width: auto;">
            <span>
              MediAI
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
              <li class="nav-item active">
                <a class="nav-link" href="{{ route('welcome') }}">Home <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
    <a class="nav-link" href="{{ route('about') }}">About</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('help') }}">How to use</a>
</li>

              
              @if (Route::has('login'))
      <li class="nav-item">
        @auth
          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
              {{ Auth::user()->name }}
            </a>

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="{{ route('logout') }}"
                 onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </div>
          </li>
        @else
          <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Log in</a></li>
          
        @endauth
      </li>
    @endif
            </ul>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->
    <!-- slider section -->
    <section class="slider_section ">
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container ">
              <div class="row">
                <div class="col-md-7">
                  <div class="detail-box">
                  <h1>
                  Empowering Healthcare with AI
                </h1>
                <p>
                  MediAI leverages cutting-edge AI technologies to deliver accurate diagnostics, predictive healthcare, and personalized treatment plans. Our mission is to enhance patient outcomes and streamline healthcare processes through intelligent automation.
                </p>
                    <div class="btn-box">
                      <a href="" class="btn1">
                        Read More
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item ">
            <div class="container ">
              <div class="row">
                <div class="col-md-7">
                  <div class="detail-box">
                  <h1>
                  AI-Powered Diagnostics
                </h1>
                <p>
                  Our AI algorithms provide real-time, precise diagnostics, reducing human error and enabling faster, more accurate healthcare decisions. From radiology to pathology, MediAI is revolutionizing the way healthcare professionals diagnose and treat illnesses.
                </p>
                    <div class="btn-box">
                      <a href="" class="btn1">
                        Read More
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="container ">
              <div class="row">
                <div class="col-md-7">
                  <div class="detail-box">
                  <h1>
                  Personalized Healthcare Solutions
                </h1>
                <p>
                  With MediAI, healthcare becomes personalized. Our AI-driven insights offer tailored treatment plans for individual patients, considering their medical history, genetics, and lifestyle, ensuring that care is as unique as the patient.
                </p>
                    <div class="btn-box">
                      <a href="" class="btn1">
                        Read More
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <ol class="carousel-indicators">
          <li data-target="#customCarousel1" data-slide-to="0" class="active"></li>
          <li data-target="#customCarousel1" data-slide-to="1"></li>
          <li data-target="#customCarousel1" data-slide-to="2"></li>
        </ol>
      </div>

    </section>
    <!-- end slider section -->
  </div>


  <!-- department section -->

  <section class="department_section layout_padding">
    <div class="department_container">
      <div class="container ">
        <div class="heading_container heading_center">
          <h2>
            Our Services
          </h2>
          
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="box ">
              <div class="img-box">
                <img src="images/s1.png" alt="">
              </div>
              <div class="detail-box">
              <h5>
                Medicine Identification
              </h5>
              <p>
                Quickly identify medications by scanning labels or barcodes. MediAI ensures patients take the right medicine at the right time.
              </p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="box ">
              <div class="img-box">
                <img src="images/s2.png" alt="">
              </div>
              <div class="detail-box">
              <h5>
                Diagnosis
              </h5>
              <p>
                Powered by AI, our diagnostic tool provides accurate, real-time analysis of patient symptoms for faster, more precise treatment.
              </p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="box ">
              <div class="img-box">
                <img src="images/s3.png" alt="">
              </div>
              <div class="detail-box">
              <h5>
                Remedy Recommendation
              </h5>
              <p>
                Based on your diagnosis, MediAI offers personalized treatment plans, ensuring the best course of action for your recovery.
              </p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="box ">
              <div class="img-box">
                <img src="images/s4.png" alt="">
              </div>
              <div class="detail-box">
              <h5>
                Days Until Medicine Availability
              </h5>
              <p>
                Keep track of when your prescribed medicine will be available and get real-time updates on delivery or pickup.
              </p>
              </div>
            </div>
          </div>
        </div>
        <div class="btn-box">
          <a href="">
            View All
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- end department section -->

  <!-- about section -->

  <section class="about_section layout_margin-bottom">
    <div class="container  ">
      <div class="row">
        <div class="col-md-6 ">
          <div class="img-box">
            <img src="images/about-img.jpg" alt="">
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
            <h2>
  About <span>Us</span>
</h2>
<p>
  MediAI is at the forefront of revolutionizing healthcare through artificial intelligence. Our mission is to provide innovative, AI-driven solutions that enhance medical diagnosis, treatment recommendations, and patient care. By integrating advanced technology with healthcare, we strive to make medical processes more efficient, accurate, and accessible to everyone.
</p>
<p>
  Whether it's identifying the right medicine, offering precise diagnostics, or personalizing treatment plans, MediAI is dedicated to improving patient outcomes and supporting healthcare professionals. Our platform harnesses the power of AI to bridge the gap between traditional healthcare and modern technology, ensuring the best care for every individual.
</p>

            <a href="">
              Read More
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end about section -->

  

  <!-- footer section -->
  <footer class="footer_section">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-lg-3 footer_col">
          <div class="footer_contact">
            <h4>
              Reach at..
            </h4>
            <div class="contact_link_box">
              <a href="">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>
                  Location
                </span>
              </a>
              <a href="">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>
                  Call +94703121778
                </span>
              </a>
              <a href="">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>
                  mediAI@gmail.com
                </span>
              </a>
            </div>
          </div>
          <div class="footer_social">
            <a href="">
              <i class="fa fa-facebook" aria-hidden="true"></i>
            </a>
            <a href="">
              <i class="fa fa-twitter" aria-hidden="true"></i>
            </a>
            <a href="">
              <i class="fa fa-linkedin" aria-hidden="true"></i>
            </a>
            <a href="">
              <i class="fa fa-instagram" aria-hidden="true"></i>
            </a>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 footer_col">
          <div class="footer_detail">
            <h4>
              About
            </h4>
            <p>
            MediAI is at the forefront of revolutionizing healthcare through artificial intelligence. Our mission is to provide innovative, AI-driven solutions that enhance medical diagnosis, treatment recommendations, and patient care. 
            </p>
          </div>
        </div>
        <div class="col-md-6 col-lg-2 mx-auto footer_col">
          <div class="footer_link_box">
            <h4>
              Links
            </h4>
            <div class="footer_links">
              <a class="active" href="{{ route('welcome') }}">
                Home
              </a>
              
    <a class="nav-link" href="{{ route('about') }}">About</a>
    <a class="nav-link" href="{{ route('help') }}">How to use</a>

              
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 footer_col ">
          
          </form>
        </div>
      </div>
      <div class="footer-info">
        <p>
          &copy; <span id="displayYear"></span> All Rights Reserved By
          <a href="">MediAI<br><br></a>
            &copy; <span id="displayYear"></span> Developed By
            <a href="">Chamudika Pallewela</a>
        </p>
       
      </div>  
    </div>
  </footer>
  <!-- footer section -->

  <!-- jQery -->
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <!-- bootstrap js -->
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- custom js -->
  <script type="text/javascript" src="js/custom.js"></script>
  <!-- Google Map -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
  </script>
  <!-- End Google Map -->

</body>

</html>