<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['user_id']);
session_destroy();
?>
<!doctype html>
<!--[if lte IE 9]>     <html lang="en" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en" class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>AMS</title>
        <link rel="stylesheet" id="css-main" href="assets/css/codebase.min.css" async>
        <link rel="stylesheet" id="css-main" href="assets/css/themes/earth.min.css" async>
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet" async>
    </head>
    <body>
        <div id="page-container" class="main-content-boxed">
            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Content -->                
                <div class="bg-image" style="background-image: url('assets/img/bg-login.jpg');">
                    <div class="hero-static content content-full bg-white-op-90 invisible" data-toggle="appear" data-class="animated fadeIn">
                        <!-- Avatar -->
                        <div class="py-30 px-5 text-center">
                            <a class="link-effect text-pulse font-w700" href="index.html">
                                <i class="si si-fire"></i>
                                <span class="font-size-xl text-pulse-dark">Aviation</span> <span class="font-size-xl text-success">Management System</span>
                            </a>
                            <h1 class="h2 font-w700 mt-50 mb-10">Welcome back, </h1>
                            <h2 class="h4 font-w400 text-muted mb-30">Please enter your password</h2>
                            <img class="img-avatar img-avatar96" src="" alt="">
                        </div>
                        <!-- END Avatar -->

                        <!-- Unlock Content -->
                        <div class="row justify-content-center px-5">
                            <div class="col-sm-8 col-md-6 col-xl-4">
                                <form method="POST" action="auth/" id="time_form" name="time_form">
                                    <?php if(@$_GET['r']=="80t1zjysirkvk769s8dvs") {?>
                                        <div class="text-center" style="background: rgba(255,33,30,0.8) !important; color: #fff; padding: 10px; margin-bottom: 10px" role="alert">Please fill your username with yours, and password also.</div>
                                    <?php } ?>  
                                    <?php if(@$_GET['r']=="8vt1zjysirkvk769s8dvs") {?>
                                        <div class="text-center" style="background: rgba(255,33,30,0.8) !important; color: #fff; padding: 10px; margin-bottom: 10px" role="alert">Wrong Login. Please try again.</div>
                                    <?php } ?>          
                                    <?php if(@$_GET['r']=="8xt1zjysirkvk769s8dvs") {?>
                                        <div class="text-center" style="background: rgba(255,33,30,0.8) !important; color: #fff; padding: 10px; margin-bottom: 10px" role="alert">Your client account has been suspended</div>
                                    <?php } ?>
                                    <?php if(@$_GET['r']=="8nt1zjysirkvk769s8dvs") {?>
                                        <div class="text-center" style="background: rgba(255,33,30,0.8) !important; color: #fff; padding: 10px; margin-bottom: 10px" role="alert">Your account has been suspended</div>
                                    <?php } ?>                                    
                                    <script type="text/javascript">
                                        tzo = - new Date().getTimezoneOffset()*60;
                                        document.write('<input type="hidden" value="'+tzo+'" name="timezoneoffset">');
                                    </script>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="text" class="form-control" id="username" name="txt_username">
                                                <label for="lock-password">Username</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="password" class="form-control" id="password" name="txt_password">
                                                <label for="lock-password">Password</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-hero btn-noborder btn-rounded btn-alt-success">
                                            <i class="si si-lock-open mr-10"></i> Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- END Unlock Content -->
                    </div>
                </div>
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->
        </div>
        <!-- END Page Container -->

        <!-- Codebase Core JS -->
        <script src="assets/js/core/jquery.min.js"></script>
        <script src="assets/js/core/bootstrap.bundle.min.js"></script>
        <script src="assets/js/core/jquery.appear.min.js"></script>
        <script src="assets/js/codebase.js"></script>
    </body>
</html>