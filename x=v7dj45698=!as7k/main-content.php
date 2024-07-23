<?php require_once 'header.php' ?>
<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="../assets/js/plugins/slick/slick.min.css">
<link rel="stylesheet" href="../assets/js/plugins/slick/slick-theme.min.css">

<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">

        <!-- Avatar Sliders -->
        <h2 class="content-heading">Unread Message</h2>
        <div class="row items-push">
            <div class="col-md-12">
                <!-- Slider with Avatars -->
                <div class="block">
                    <div class="block-content">
                        <?php
                        $sql_message = "SELECT a.c_id, c_id,message_id, message_detail, message_code, message_subject, DATE_FORMAT(DATE_ADD(date_send,INTERVAL '".$_SESSION['selisih']."' HOUR), '%d/%m/%Y on %H:%i') as date_send, from_id, a.send_to, (b.full_name) AS dari,message_read_status FROM tbl_messages a INNER JOIN  tbl_user b ON a.from_id = b.user_id WHERE send_to = '".$_SESSION['user_id']."' AND a.client_id = '".$_SESSION['client_id']."' AND message_read_status = 0 order by date_send DESC LIMIT 5";
                        $h_message = mysqli_query($conn,$sql_message);
                        if(mysqli_num_rows($h_message)>0) {
                        ?>
                        <div class="js-slider text-center" data-dots="true" data-arrows="true">
                            <?php while ($row_message = mysqli_fetch_assoc($h_message)) { ?>
                            <div class="py-20">
                                <a class="font-w600" href="message-detail.php?act=29dvi59&st=<?php echo @$_REQUEST['st'] ?>&ntf=29dvi59-<?php echo $row_message["message_code"]?>-<?php echo $row_message["message_id"]?>-<?php echo $row_message["c_id"]?>-94dfvj!sdf-349ffuaw">
                                <div class="mt-10 font-w600"><?php echo $row_message['dari'] ?></div>
                                <div class="font-size-sm text-muted"><?php echo $row_message['message_subject'] ?></div>
                                </a>
                            </div>
                            <?php } ?>
                        </div>
                        <?php }else{ ?>
                        <div class="alert alert-success alert-dismissable" role="alert">
                            <h3 class="alert-heading font-size-h4 font-w400">Hoooraayy!</h3>
                            <p class="mb-0">There is no unread message</p>
                        </div>
                        <?php } mysqli_free_result($h_message); ?>
                    </div>
                </div>
                <!-- END Slider with Avatars -->
            </div>
        </div>
        <!-- END Avatar Sliders -->



        <div class="row gutters-tiny invisible" data-toggle="appear">
            <!-- Row #5 -->
            <div class="col-6 col-md-3 col-xl-3">
                <a class="block block-link-shadow text-center" href="message.php">
                    <div class="block-content ribbon ribbon-bookmark ribbon-success ribbon-left bg-gd-dusk">
                        <p class="mt-5">
                            <i class="si si-envelope-letter fa-3x text-white-op"></i>
                        </p>
                        <p class="font-w600 text-white">Message</p>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3 col-xl-3">
                <a class="block block-link-shadow text-center" href="profile.php">
                    <div class="block-content bg-gd-sea">
                        <p class="mt-5">
                            <i class="si si-user fa-3x text-white-op"></i>
                        </p>
                        <p class="font-w600 text-white">Profile</p>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3 col-xl-3">
                <a class="block block-link-shadow text-center" href="km.php">
                    <div class="block-content ribbon ribbon-bookmark ribbon-primary ribbon-left bg-gd-lake">
                        <p class="mt-5">
                            <i class="si si-book-open fa-3x text-white-op"></i>
                        </p>
                        <p class="font-w600 text-white">Knowledges</p>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3 col-xl-3">
                <a class="block block-link-shadow text-center" href="../logout.php">
                    <div class="block-content bg-gd-corporate">
                        <p class="mt-5">
                            <i class="si si-power fa-3x text-white-op"></i>
                        </p>
                        <p class="font-w600 text-white">Logout</p>
                    </div>
                </a>
            </div>            
            <!-- END Row #5 -->
        </div>

        <div class="row gutters-tiny invisible" data-toggle="appear">
            <?php
            $sql_f  = "SELECT count(safety_finding_id) as total FROM tbl_safety_finding WHERE client_id = '".$_SESSION['client_id']."'";
            $h_f    = mysqli_query($conn,$sql_f);
            $row_f  = mysqli_fetch_assoc($h_f);

            $sql_f2  = "SELECT count(safety_finding_id) as total FROM tbl_safety_finding WHERE safety_finding_status = 'OPEN' AND client_id = '".$_SESSION['client_id']."'";
            $h_f2    = mysqli_query($conn,$sql_f2);
            $row_f2  = mysqli_fetch_assoc($h_f2);

            $sql_f3  = "SELECT count(safety_finding_id) as total FROM tbl_safety_finding WHERE safety_finding_status = 'CLOSED' AND client_id = '".$_SESSION['client_id']."'";
            $h_f3    = mysqli_query($conn,$sql_f3);
            $row_f3  = mysqli_fetch_assoc($h_f3);                        
            ?>
            <div class="col-md-12">
                <a class="block block-link-shadow overflow-hidden" href="safety-finding.php">
                    <div class="block-content block-content-full">
                        <div class="text-center">
                            <H3 class="text-info">Finding Case</H3>
                        </div>
                        <div class="row py-20">
                            <div class="col-4 text-right border-r">
                                <div class="invisible" data-toggle="appear" data-class="animated fadeInLeft">
                                    <div class="font-size-h3 font-w600 text-info"><?php echo $row_f['total'] ?></div>
                                    <div class="font-size-sm font-w600 text-uppercase text-muted">Finding</div>
                                </div>
                            </div>
                            <div class="col-4 border-r text-center">
                                <div class="invisible" data-toggle="appear" data-class="animated fadeIn">
                                    <div class="font-size-h3 font-w600 text-success"><?php echo $row_f3['total'] ?></div>
                                    <div class="font-size-sm font-w600 text-uppercase text-muted">Closed</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="invisible" data-toggle="appear" data-class="animated fadeInRight">
                                    <div class="font-size-h3 font-w600 text-danger"><?php echo $row_f2['total'] ?></div>
                                    <div class="font-size-sm font-w600 text-uppercase text-muted">Open</div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>

<!-- Page Plugins -->
<script src="../assets/js/plugins/slick/slick.min.js"></script>

<!-- Page JS Code -->
<script>
    jQuery(function () {
        // Init page helpers (Slick Slider plugin)
        Codebase.helpers('slick');
    });
</script>

<script type="text/javascript">
window.addEventListener('load', () => {
  if (!('serviceWorker' in navigator)) {
    // service workers not supported
    return
  }

  navigator.serviceWorker.register('../sw-ams.js').then(
    () => {
      // registered!
      //console.log('oke!', err)
    },
    err => {
      console.error('SW registration failed!', err)
    }
  )
})  
</script>