<?php include '../api/login.php'; ?>
<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>WARDIAN - SYSTEM</title>
      <!-- Favicon -->
      <link rel="shortcut icon" href="images/favicon.ico" />
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <!-- Typography CSS -->
      <link rel="stylesheet" href="css/typography.css">
      <!-- Style CSS -->
      <link rel="stylesheet" href="css/style.css">
      <!-- Responsive CSS -->
      <link rel="stylesheet" href="css/responsive.css">
   </head>
   <body>
      <!-- loader Start -->
      <div id="loading">
         <div id="loading-center">
         </div>
      </div>
      <!-- loader END -->
        <!-- Sign in Start -->
        <section class="sign-in-page">
          <div id="container-inside">
              <div class="cube"></div>
              <div class="cube"></div>
              <div class="cube"></div>
              <div class="cube"></div>
              <div class="cube"></div>
          </div>
            <div class="container p-0">
                <div class="row no-gutters height-self-center">
                  <div class="col-sm-12 align-self-center bg-primary rounded">
                    <div class="row m-0">
                      <div class="col-md-5 bg-white sign-in-page-data">
                          <div class="sign-in-from">
                              <h1 class="mb-0 text-center">Iniciar sesión</h1>
                              <p class="text-center text-dark">Ingrese su dirección de correo electrónico y contraseña para acceder.</p>
                              <form class="mt-4" method="POST" action="">
                                  <div class="form-group">
                                      <label for="exampleInputEmail1">Dirección de correo electrónico</label>
                                      <input type="email" class="form-control mb-0" name="username" placeholder="Ingrese el correo electrónico">
                                  </div>
                                  <div class="form-group">
                                      <label for="exampleInputPassword1">Contraseña</label>
                                      <a href="pages-recoverpw.php" class="float-right">¿Has olvidado tu contraseña?</a>
                                      <input type="password" class="form-control mb-0" name="password" placeholder="Contraseña">
                                  </div>
                                  <div class="d-inline-block w-100">
                                      <div class="custom-control custom-checkbox d-inline-block mt-2 pt-1">
                                          <input type="checkbox" class="custom-control-input" id="customCheck1">
                                          <label class="custom-control-label" for="customCheck1">Acuérdarme</label>
                                      </div>
                                  </div>
                                  <div class="sign-info text-center">
                                      <button type="submit" class="btn btn-primary d-block w-100 mb-2">Acceder</button>
                                      <span class="text-dark dark-color d-inline-block line-height-2">¿Necesitas ayuda? <a href="#">click aqui</a></span>
                                      
                                      <?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
                                        <span class="text-dark dark-color d-inline-block line-height-2">Tu contraseña ha sido restablecida con éxito.</span>
                                      <?php endif; ?>
                                  </div>
                              </form>
                          </div>
                      </div>
                      <div class="col-md-7 text-center sign-in-page-image">
                          <div class="sign-in-detail text-white">
                            <a class="sign-in-logo mb-5" href="#"><img src="images/logo-full.png" class="img-fluid" alt="logo"></a>
                              <div class="owl-carousel" data-autoplay="true" data-loop="true" data-nav="false" data-dots="true" data-items="1" data-items-laptop="1" data-items-tab="1" data-items-mobile="1" data-items-mobile-sm="1" data-margin="0">
                                  <div class="item">
                                      <img src="images/login/1.png" class="img-fluid mb-4" alt="logo">
                                      <h4 class="mb-1 text-white">AI</h4>
                                      <p>Con la Contabilidad Inteligente de WARDIAN, tu negocio tiene el poder..</p>
                                  </div>
                                  <div class="item">
                                      <img src="images/login/1.png" class="img-fluid mb-4" alt="logo"> 
                                      <h4 class="mb-1 text-white">API</h4>
                                      <p>El poder de hacer tareas en automático.</p>
                                  </div>
                                  <div class="item">
                                      <img src="images/login/1.png" class="img-fluid mb-4" alt="logo">
                                      <h4 class="mb-1 text-white">SEGURIDAD</h4>
                                      <p>Compromiso con la seguridad de la información y la excelencia en los procesos.</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </section>
        <!-- Sign in END -->
       
      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="js/jquery.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <!-- Appear JavaScript -->
      <script src="js/jquery.appear.js"></script>
      <!-- Countdown JavaScript -->
      <script src="js/countdown.min.js"></script>
      <!-- Counterup JavaScript -->
      <script src="js/waypoints.min.js"></script>
      <script src="js/jquery.counterup.min.js"></script>
      <!-- Wow JavaScript -->
      <script src="js/wow.min.js"></script>
      <!-- Apexcharts JavaScript -->
      <script src="js/apexcharts.js"></script>
      <!-- lottie JavaScript -->
      <script src="js/lottie.js"></script>
      <!-- Slick JavaScript --> 
      <script src="js/slick.min.js"></script>
      <!-- Select2 JavaScript -->
      <script src="js/select2.min.js"></script>
      <!-- Owl Carousel JavaScript -->
      <script src="js/owl.carousel.min.js"></script>
      <!-- Magnific Popup JavaScript -->
      <script src="js/jquery.magnific-popup.min.js"></script>
      <!-- Smooth Scrollbar JavaScript -->
      <script src="js/smooth-scrollbar.js"></script>
      <!-- Style Customizer -->
      <script src="js/style-customizer.js"></script>
      <!-- Chart Custom JavaScript -->
      <script src="js/chart-custom.js"></script>
      <!-- Custom JavaScript -->
      <script src="js/custom.js"></script>
   </body>
</html>
