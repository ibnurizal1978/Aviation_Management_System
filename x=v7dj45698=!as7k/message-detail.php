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
                            $sql    = "SELECT message_subject FROM tbl_messages WHERE message_id = '".$ntf[2]."' LIMIT 1";
                            $h      = mysqli_query($conn,$sql);
                            $row    = mysqli_fetch_assoc($h);
                            echo '<b>'.$row['message_subject'].'</b>';
                            ?>
                            <div align="right" class="block-options pull-right">
                                <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                                    <i class="fa fa-filter"></i>
                                </button>
                                <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="modal" data-target="#modal-reply">
                                    <i class="si si-action-undo"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <table class="table table-hover table-vcenter">
                                <tbody>
                                    <?php
                                    if($_GET['st']==1) {
                                        $sql = "SELECT a.c_id, message_id,message_detail, message_code,  message_type, DATE_FORMAT(DATE_ADD(date_send,INTERVAL '".$_SESSION['selisih']."' HOUR), '%d/%m/%Y on %H:%i') as date_send2, DATE_FORMAT(DATE_ADD(message_read_status_date,INTERVAL '".$_SESSION['selisih']."' HOUR), '%d/%m/%Y on %H:%i') as message_read_status_date, message_read_status, date_send, from_id, a.send_to as send_to, (b.full_name) AS dari FROM tbl_messages a INNER JOIN  tbl_user b ON a.from_id = b.user_id WHERE  a.client_id = '".$_SESSION['client_id']."' AND a.message_code = '".$ntf[1]."' AND a.c_id = '".$ntf[3]."' order by date_send DESC";
                                    }else{
                                        $sql = "SELECT a.c_id, message_id,message_detail, message_code,  message_type, DATE_FORMAT(DATE_ADD(date_send,INTERVAL '".$_SESSION['selisih']."' HOUR), '%d/%m/%Y on %H:%i') as date_send2, DATE_FORMAT(DATE_ADD(message_read_status_date,INTERVAL '".$_SESSION['selisih']."' HOUR), '%d/%m/%Y on %H:%i') as message_read_status_date, message_read_status, date_send, from_id, a.send_to as send_to, (b.full_name) AS dari FROM tbl_messages a INNER JOIN  tbl_user b ON a.from_id = b.user_id WHERE  a.client_id = '".$_SESSION['client_id']."' AND a.message_code = '".$ntf[1]."' AND a.c_id = '".$ntf[3]."' order by date_send DESC";
                                    }
                                    $h    = mysqli_query($conn,$sql);
                                    while($row  = mysqli_fetch_assoc($h)) {
                                      $message_id   = $row['message_id'];
                                      $message_code = $row['message_code'];
                                      $send_to      = $row['from_id'];
                                    $nama = $row['dari'];
                                    $inisial =  implode('', array_map(function($v) { return $v[0]; }, explode(' ', $nama)));
                                    ?>
                                    <tr>
                                        <td class="d-sm-table-cell font-w600">
                                            <?php
                                              if($row['send_to']==$_SESSION['user_id'] && $row['message_read_status']<>1) {
                                                $sql_u = "UPDATE tbl_messages SET message_read_status = 1,message_read_status_date = UTC_TIMESTAMP() WHERE message_id = '".$message_id."' LIMIT 1";
                                               mysqli_query($conn,$sql_u);
                                              }
                                            ?>                                            
                                            <span class="text-success"><?php echo $row['dari'] ?></span>, <small class="text-danger">Recipient was read on <?php echo $row['message_read_status_date'] ?></small><br/>
                                            <b><?php echo $row['message_subject']; ?></b>
                                            <div class="text-muted mt-5"><?php echo html_entity_decode($row['message_detail']); ?></div>
                                        </td>                                           
                                        <td class="d-none d-xl-table-cell font-w600 font-size-sm text-muted" style="width: 120px;"><?php echo $row['date_send2'] ?></td>
                                    </tr>                                            
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2">
                                            <?php
                                            $sql_f  = "SELECT * FROM tbl_messages_file WHERE message_code = '".$message_code."'";
                                            $h_f    = mysqli_query($conn,$sql_f);
                                            while($row_f = mysqli_fetch_assoc($h_f)) {
                                            if(mysqli_num_rows($h_f)>0) {
                                              
                                            ?>
                                            Attachment: <a href="<?php echo $base_url ?>uploads/messages/<?php echo $row_f['file_name'] ?>" target="_blank"><i class="si si-paper-clip"></i></a>
                                            <?php }} ?>
                                        </td>
                                    </tr>                                    
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


        <!-- Modal detail -->
        <div class="modal fade" id="modal-reply" tabindex="-1" role="dialog" aria-labelledby="modal-message" aria-hidden="true">
            <div class="modal-dialog modal-dialog-popout" role="document">
                <div class="modal-content">
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Reply Message</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <form id="form_simpan2">
                                <input type="hidden" name="message_id" value="<?php echo $message_id ?>" />
                                <input type="hidden" name="send_to" value="<?php echo $send_to ?>" />
                                <input type="hidden" name="message_code" value="<?php echo $message_code ?>" />                                
                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="form-material form-material-primary">
                                            <textarea class="form-control" id="message-msg" name="message_detail" rows="2
                                            <br/" placeholder="Reply here.."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="results2"></div><div id="button2"></div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END modal detail -->

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

$(document).ready(function(){
  $("#button2").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data2"><i class="fa fa-check mr-5"></i>Send</button> <button type="button" class="btn btn-alt-secondary mr-5 mb-5" data-dismiss="modal">Cancel</button>');  
  $('#submit_data2').click(function(){  
    $('#form_simpan2').submit(); 
    $("#results2").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button2").html('<button type="submit" class="btn btn-success pull-right" id="submit_data2">Loading...</button>'); 
  });  
  $('#form_simpan2').on('submit', function(event){  
    $("#results2").html('<i class="fa fa-4x fa-cog fa-spin text-success"></i>');
    $("#button2").html('<button type="submit" class="btn btn-success pull-right" id="submit_data2">Loading...</button>');   
    event.preventDefault(); 
    $.ajax({  
      url:"message-reply.php",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results2').html(data);  
        $('#submit_data2').val('');
        $("#button2").html('<button type="submit" class="btn btn-success mr-5 mb-5" id="submit_data2"><i class="fa fa-check mr-5"></i>Send</button> <button type="button" class="btn btn-alt-secondary mr-5 mb-5" data-dismiss="modal">Cancel</button>');  
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

