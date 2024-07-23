<?php 
require_once 'header.php';
//require_once 'components.php';
?>

        <link rel="stylesheet" href="../assets/js/plugins/select2/select2.min.css">
        <link rel="stylesheet" href="../assets/js/plugins/select2/select2-bootstrap.min.css">


<!-- Side Overlay-->
<aside id="side-overlay">
    <!-- Side Overlay Scroll Container -->
    <div id="side-overlay-scroll">
        <!-- Side Header -->
        <div class="content-header content-header-fullrow">
            <div class="content-header-section align-parent">
                <button type="button" class="btn btn-circle btn-dual-secondary align-v-r" data-toggle="layout" data-action="side_overlay_close">
                    <i class="fa fa-times text-danger"></i>
                </button>

                <div class="content-header-item">
                    <a class="align-middle link-effect text-primary-dark font-w600" href="#">Filter</a>
                </div>
                <!-- END User Info -->
            </div>
        </div>
        <!-- END Side filter -->

        <!-- side kanan -->
        <div class="content-side">
            <!-- Search -->
            <div class="block pull-t pull-r-l">
                <div class="block-content block-content-full block-content-sm bg-body-light">
                    <form action="message.php" method="post">
                        <input type="hidden" name="s" value="1091vdf8ame151">
                        <div class="input-group">
                            <input type="text" class="form-control" id="side-overlay-search" name="txt_search" placeholder="Search..">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-secondary px-10">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END Search -->

            <!-- Profile -->
            <div class="block pull-r-l">
                <div class="block-header bg-body-light">
                    <h3 class="block-title">
                        <i class="fa fa-fw fa-pencil font-size-default mr-5"></i>Sort by
                    </h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                    </div>
                </div>
                <div class="block-content">
                    <form action="message.php" method="post">
                        <div class="form-group mb-15">
                            <div class="form-material floating">
                                <select class="form-control" id="material-select2" name="s">
                                    <option></option><!-- Empty value for demostrating material select box -->
                                    <option value="1">By Sender A-Z</option>
                                </select>
                                <label for="material-select2">Please Select</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-block btn-alt-primary">
                                    <i class="fa fa-refresh mr-5"></i> View
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END filter -->
        </div>
        <!-- END Side filter -->
    </div>
    <!-- END Side Overlay Scroll Container -->
</aside>
<!-- END Side Overlay -->

<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">

    <!-- Small Table -->
    <!--begin list data-->
      <?php
      @$page = @$_REQUEST['page'];
      $dataPerPage = 10;
      if(isset($_GET['page']))
      {
          $noPage = $_GET['page'];
      }
      else $noPage = 1;
      @$offset = ($noPage - 1) * $dataPerPage;
      //for total count data
        if(@$_REQUEST['st']==0) {
            if(@$_REQUEST['s']=='1091vdf8ame151') {
                $txt_search   = input_data(filter_var($_REQUEST['txt_search'],FILTER_SANITIZE_STRING));
                $sql = "SELECT message_id,c_id,message_detail,message_subject,full_name,DATE_FORMAT(DATE_ADD(date_send,INTERVAL '".$_SESSION['selisih']."' HOUR), '%d/%m/%Y on %H:%i') as date_send FROM tbl_messages a INNER JOIN tbl_user b ON a.from_id = b.user_id WHERE (message_subject LIKE '%$txt_search%' OR message_detail LIKE '%$txt_search%') AND a.send_to = '".$_SESSION['user_id']."' AND a.client_id = '".$_SESSION['client_id']."' LIMIT $offset, $dataPerPage";
            }elseif (@$_REQUEST['s']=='1'){
                $sql = "SELECT message_id,c_id,message_detail,message_subject,full_name,DATE_FORMAT(DATE_ADD(date_send,INTERVAL '".$_SESSION['selisih']."' HOUR), '%d/%m/%Y on %H:%i') as date_send FROM tbl_messages a INNER JOIN tbl_user b ON a.from_id = b.user_id WHERE a.send_to = '".$_SESSION['user_id']."' AND a.client_id = '".$_SESSION['client_id']."' ORDER by full_name LIMIT $offset, $dataPerPage";      
            }else{
                $sql = "SELECT a.c_id, c_id,message_id, message_detail, message_code, message_subject, DATE_FORMAT(DATE_ADD(date_send,INTERVAL '".$_SESSION['selisih']."' HOUR), '%d/%m/%Y on %H:%i') as date_send, from_id, a.send_to, (b.full_name) AS dari,message_read_status FROM tbl_messages a INNER JOIN  tbl_user b ON a.from_id = b.user_id WHERE send_to = '".$_SESSION['user_id']."' AND a.client_id = '".$_SESSION['client_id']."' order by date_send DESC LIMIT $offset, $dataPerPage";
            }
        }else{
            //sent items
            if(@$_REQUEST['s']=='1091vdf8ame151') {
                $txt_search   = input_data(filter_var($_REQUEST['txt_search'],FILTER_SANITIZE_STRING));
                $sql = "SELECT message_id,c_id,message_detail,message_subject,full_name,DATE_FORMAT(DATE_ADD(date_send,INTERVAL '".$_SESSION['selisih']."' HOUR), '%d/%m/%Y on %H:%i') as date_send FROM tbl_messages a INNER JOIN tbl_user b ON a.send_to = b.user_id WHERE (message_subject LIKE '%$txt_search%' OR message_detail LIKE '%$txt_search%') AND a.from_id = '".$_SESSION['user_id']."' AND a.client_id = '".$_SESSION['client_id']."' LIMIT $offset, $dataPerPage";
            }elseif (@$_REQUEST['s']=='1'){
                $sql = "SELECT message_id,c_id,message_detail,message_subject,full_name,DATE_FORMAT(DATE_ADD(date_send,INTERVAL '".$_SESSION['selisih']."' HOUR), '%d/%m/%Y on %H:%i') as date_send FROM tbl_messages a INNER JOIN tbl_user b ON a.send_to = b.user_id WHERE a.from_id = '".$_SESSION['user_id']."' AND a.client_id = '".$_SESSION['client_id']."' ORDER by full_name LIMIT $offset, $dataPerPage";      
            }else{
                $sql = "SELECT a.c_id, c_id,message_id, message_detail, message_code, message_subject, DATE_FORMAT(DATE_ADD(date_send,INTERVAL '".$_SESSION['selisih']."' HOUR), '%d/%m/%Y on %H:%i') as date_send, from_id, a.send_to, (b.full_name) AS dari,message_read_status FROM tbl_messages a INNER JOIN tbl_user b ON a.send_to = b.user_id WHERE a.from_id = '".$_SESSION['user_id']."' AND a.client_id = '".$_SESSION['client_id']."' order by date_send DESC LIMIT $offset, $dataPerPage";
            }            
        }
      $rs_result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($rs_result)==0) {
      ?>
        <p class="text-center text-danger" role="alert">No one sent you a message :(</p>
      <?php  
      //exit(); 
      }
      ?>




        <!-- Page Content -->
        <div class="content">
            <h2 class="content-heading">
                <button type="button" class="btn btn-sm btn-rounded btn-primary d-md-none float-right ml-5" data-toggle="class-toggle" data-target=".js-inbox-nav" data-class="d-none d-md-block">Menu</button>
                <button type="button" class="btn btn-sm btn-rounded btn-success float-right" data-toggle="modal" data-target="#modal-compose">New Message</button>
                Message Center
            </h2>
            <div class="row">
                <div class="col-md-5 col-xl-3">
                    <!-- Collapsible Inbox Navigation -->
                    <div class="js-inbox-nav d-none d-md-block">
                        <div class="block">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">Menu</h3>
                            </div>
                            <div class="block-content">
                                <ul class="nav nav-pills flex-column push">
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center justify-content-between" href="message.php?q=<?php echo date('iHydsm') ?>&st=0&vk=uhnj34t">
                                            <span><i class="fa fa-fw fa-inbox mr-5"></i> Inbox</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center justify-content-between" href="message.php?q=<?php echo date('iHydsm') ?>&st=1&vk=uhnj34t">
                                            <span><i class="fa fa-fw fa-send mr-5"></i> Sent</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- END Collapsible Inbox Navigation -->

                </div>
                <div class="col-md-7 col-xl-9">
                    <!-- Message List -->
                    <div class="block">
                        <div class="block-header block-header-default">
                            <?php 
                            if(@$_REQUEST['st']==1) {
                                echo '<b>Sent Items</b>';
                            }else{
                                echo '<b>Inbox</b>';
                            } 
                            ?>
                            <div align="right" class="block-options pull-right">
                                <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                                    <i class="fa fa-filter"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <table class="table table-hover table-vcenter">
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($rs_result)) { ?>
                                    <tr>
                                        <td><?php echo $row['dari'] ?></td>
                                        <td>
                                            <a class="font-w600" href="message-detail.php?act=29dvi59&st=<?php echo @$_REQUEST['st'] ?>&ntf=29dvi59-<?php echo $row["message_code"]?>-<?php echo $row["message_id"]?>-<?php echo $row["c_id"]?>-94dfvj!sdf-349ffuaw">
                                                <?php if($row['message_read_status']==0) { 
                                                echo '<b>'.$row['message_subject'].'</b>';
                                                }else{
                                                echo $row['message_subject'];
                                                }
                                                ?>
                                            </a>
                                            <div class="text-muted mt-5"><?php echo substr($row['message_detail'],0,100); ?>...</div>
                                        </td>
                                        <td><?php echo $row['date_send'] ?></td>
                                    </tr>

                                    
                                                                                                           
                                    <?php } ?>
                                </tbody>
                            </table>
                            <!-- END Messages -->
                        </div>
                    </div>
                    <!-- END Message List -->
                </div>
            </div>
        </div>
        <!-- END Page Content -->


        <!-- Compose Modal -->
        <div class="modal fade" id="modal-compose" tabindex="-1" role="dialog" aria-labelledby="modal-compose" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header">
                            <h3 class="block-title">
                                <i class="fa fa-pencil mr-5"></i> New Message
                            </h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <form id="form_simpan">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="form-material form-material-primary input-group">
                                            <select class="js-select2 form-control" id="example2-select2-multiple" name="send_to_recipients[]" style="width: 100%;" data-placeholder="Choose..." multiple>
                                                <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->                                                
                                                <?php
                                                $sql  = "SELECT user_id,full_name FROM tbl_user where client_id = '".$_SESSION['client_id']."' AND user_id <> '".$_SESSION['user_id']."' ORDER BY full_name";
                                                $h    = mysqli_query($conn,$sql);
                                                while($row1 = mysqli_fetch_assoc($h)) {
                                                ?>                                                
                                                <option value="<?php echo $row1['user_id'] ?>"><?php echo $row1['full_name'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <label for="example2-select2-multiple">Send to Recipients</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="form-material form-material-primary input-group">
                                            <select class="js-select2 form-control" id="example2-select2-multiple" name="send_to_group[]" style="width: 100%;" data-placeholder="Choose..." multiple>
                                                <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->                                                
                                                <option value="ALLA">All users</option>
                                                <option value="ALLE">Engineer Team</option>
                                                <option value="ALLP">Pilot & Ops Team</option>
                                            </select>
                                            <label for="example2-select2-multiple">Send to Group</label>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="form-material form-material-primary input-group">
                                            <input type="text" class="form-control" id="message-subject" name="message_subject" placeholder="What is this about?">
                                            <label for="message-subject">Subject</label>
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="si si-book-open"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="form-material form-material-primary">
                                            <textarea class="form-control" id="message-msg" name="message_detail" rows="6" placeholder="Write your message.."></textarea>
                                            <label for="message-msg">Message</label>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <div class="form-group row">
                                    <label class="col-12">Attachment (Format JPG, BMP, GIF, PNG, PDF, XLS, DOC)</label>
                                    <div class="col-12">
                                    <input type="file" name="files[]" multiple="multiple">
                                  </div>
                                </div>
                                <br/><br/>
                                <div id="results"></div><div id="button"></div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Compose Modal -->

        <?php
        // COUNT TOTAL NUMBER OF ROWS IN TABLE
        if(@$_REQUEST['s']=='1091vdf8ame151') {           
          $sql = "SELECT count(message_id) as jumData FROM tbl_messages a INNER JOIN tbl_user b ON a.send_to = b.user_id WHERE (message_subject LIKE '%$txt_search%' OR message_detail LIKE '%$txt_search%') AND a.send_to = '".$_SESSION['user_id']."' AND a.client_id = '".$_SESSION['client_id']."'";
        }elseif(@$_REQUEST['s']=='1') {           
          $sql = "SELECT count(message_id) as jumData FROM tbl_messages a INNER JOIN tbl_user b ON a.send_to = b.user_id WHERE a.send_to = '".$_SESSION['user_id']."' AND a.client_id = '".$_SESSION['client_id']."'";
        }else{            
          $sql = "SELECT count(message_id) as jumData FROM tbl_messages a INNER JOIN  tbl_user b ON a.from_id = b.user_id WHERE send_to = '".$_SESSION['user_id']."' AND a.client_id = '".$_SESSION['client_id']."'";
        }          

        $hasil  = mysqli_query($conn,$sql);
        $data     = mysqli_fetch_assoc($hasil);
        $jumData = $data['jumData'];
        $jumPage = ceil($jumData/$dataPerPage);

        if ($noPage > 1) echo  "<a class=hal href='".$_SERVER['PHP_SELF']."?s=".@$_REQUEST['s']."&client_id=".@$_REQUEST['client_id']."&txt_search=".@$_REQUEST['txt_search']."&page=".($noPage-1)."'><i class=\"fa fa-angle-left\"></i></a>";

        for($page = 1; $page <= $jumPage; $page++)
        {
            if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage))
            {
                if ((@$showPage == 1) && ($page != 2))  echo "...";
                if ((@$showPage != ($jumPage - 1)) && ($page == $jumPage))  echo "...";
                if ($page == $noPage) echo " <b>".$page."</b> ";
                else echo " <a class=hal href='".$_SERVER['PHP_SELF']."?s=".@$_REQUEST['s']."&client_id=".@$_REQUEST['client_id']."&txt_search=".@$_REQUEST['txt_search']."&page=".$page."'>".$page."</a> ";
                @$showPage = $page;
            }
        }

        if ($noPage < $jumPage) echo "<a class=hal href='".$_SERVER['PHP_SELF']."?s=".@$_REQUEST['s']."&client_id=".@$_REQUEST['client_id']."&txt_search=".@$_REQUEST['txt_search']."&page=".($noPage+1)."'><i class=\"fa fa-angle-right\"></i></a>";
        mysqli_free_result($hasil); 
        ?>

    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<?php require_once 'footer.php' ?>

<script>  
$(document).ready(function(){
  $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Send</button> <button type="button" class="btn btn-alt-secondary mr-5 mb-5" data-dismiss="modal">Cancel</button>');  
  $('#submit_data').click(function(){  
    $('#form_simpan').submit(); 
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>'); 
  });  
  $('#form_simpan').on('submit', function(event){
    $("#results").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button").html('<button type="submit" class="btn btn-success pull-right" id="submit_data">Loading...</button>');   
    event.preventDefault();  
    $.ajax({  
      url:"message-add.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);  
        $('#submit_data').val('');
        $("#button").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data"><i class="fa fa-check mr-5"></i>Send</button> <button type="button" class="btn btn-alt-secondary mr-5 mb-5" data-dismiss="modal">Cancel</button>');  
      }  
    });  
  });

});
</script>

<script src="../assets/js/plugins/select2/select2.full.min.js"></script>
        <script src="../assets/js/pages/be_forms_plugins.js"></script>
        <script>
            jQuery(function () {
                // Init page helpers (BS Datepicker + BS Colorpicker + BS Maxlength + Select2 + Masked Input + Range Sliders + Tags Inputs plugins)
                Codebase.helpers(['select2']);
            });
        </script>

