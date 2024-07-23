<?php 
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "../check-session.php";
//require_once "access.php";

$afml_date     = input_data(filter_var($_POST['afml_date'],FILTER_SANITIZE_STRING));
$afml_page_no     = input_data(filter_var($_POST['afml_page_no'],FILTER_SANITIZE_STRING));
$aircraft_reg_code     = input_data(filter_var($_POST['aircraft_reg_code'],FILTER_SANITIZE_STRING));
$afml_pilot    = input_data(filter_var($_POST['afml_pilot'],FILTER_SANITIZE_STRING));
$afml_copilot    = input_data(filter_var($_POST['afml_copilot'],FILTER_SANITIZE_STRING));
$afml_engineer_on_board    = input_data(filter_var($_POST['afml_engineer_on_board'],FILTER_SANITIZE_STRING));
//$afml_time_preflight    = input_data(filter_var($_POST['afml_time_preflight'],FILTER_SANITIZE_STRING));
//$afml_time_daily           = input_data(filter_var($_POST['afml_time_daily'],FILTER_SANITIZE_STRING));
//$afml_station_preflight          = input_data(filter_var($_POST['afml_station_preflight'],FILTER_SANITIZE_STRING));
//$afml_station_daily      = input_data(filter_var($_POST['afml_station_daily'],FILTER_SANITIZE_STRING));
//$afml_lic_preflight      = input_data(filter_var($_POST['afml_lic_preflight'],FILTER_SANITIZE_STRING));
//$afml_lic_daily    = input_data(filter_var($_POST['afml_lic_daily'],FILTER_SANITIZE_STRING));
$afml_notes_pilot    = input_data(filter_var($_POST['afml_notes_pilot'],FILTER_SANITIZE_STRING));

//etcm
$etcm_time    = input_data(filter_var($_POST['etcm_time'],FILTER_SANITIZE_STRING));
$ectm_altitude    = input_data(filter_var($_POST['ectm_altitude'],FILTER_SANITIZE_STRING));
$ectm_ias    = input_data(filter_var($_POST['ectm_ias'],FILTER_SANITIZE_STRING));
$ectm_tq    = input_data(filter_var($_POST['ectm_tq'],FILTER_SANITIZE_STRING));
$ectm_itt    = input_data(filter_var($_POST['ectm_itt'],FILTER_SANITIZE_STRING));
$ectm_ng    = input_data(filter_var($_POST['ectm_ng'],FILTER_SANITIZE_STRING));
$ectm_np    = input_data(filter_var($_POST['ectm_np'],FILTER_SANITIZE_STRING));
$ectm_ff    = input_data(filter_var($_POST['ectm_ff'],FILTER_SANITIZE_STRING));
$ectm_oil_temp    = input_data(filter_var($_POST['ectm_oil_temp'],FILTER_SANITIZE_STRING));
$ectm_oil_press    = input_data(filter_var($_POST['ectm_oil_press'],FILTER_SANITIZE_STRING));
$ectm_oat    = input_data(filter_var($_POST['ectm_oat'],FILTER_SANITIZE_STRING));

if($afml_page_no == "" || $aircraft_reg_code == "" || $afml_pilot == "") {
?>
<script type="text/javascript">
  Swal.fire({
      type: 'error',
      text: 'Please fill marked (*) forms'
  })
  </script>
<?php
  exit();
}




/*------
    $uploadedFile = $_FILES['upload_file']['tmp_name']; 
    $sourceProperties = getimagesize($uploadedFile);
    $newFileName = time();
    $dirPath = "../uploads/afml-source/";
    $ext = pathinfo($_FILES['upload_file']['name'], PATHINFO_EXTENSION);
    $imageType = $sourceProperties[2];


    switch ($imageType) {


        case IMAGETYPE_PNG:
            $imageSrc = imagecreatefrompng($uploadedFile); 
            $tmp = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1]);
            imagepng($tmp,$dirPath. $newFileName. "_thump.". $ext);
            break;           

        case IMAGETYPE_JPEG:
            $imageSrc = imagecreatefromjpeg($uploadedFile); 
            $tmp = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1]);
            imagejpeg($tmp,$dirPath. $newFileName. "_thump.". $ext);
            break;
        
        case IMAGETYPE_GIF:
            $imageSrc = imagecreatefromgif($uploadedFile); 
            $tmp = imageResize($imageSrc,$sourceProperties[0],$sourceProperties[1]);
            imagegif($tmp,$dirPath. $newFileName. "_thump.". $ext);
            break;

        default:
            echo "Invalid Image type.";
            exit;
            break;
    }


    move_uploaded_file($uploadedFile, $dirPath. $newFileName. ".". $ext);
    echo "Image Resize Successfully.";


function imageResize($imageSrc,$imageWidth,$imageHeight) {

    $newImageWidth =200;
    $newImageHeight =200;

    $newImageLayer=imagecreatetruecolor($newImageWidth,$newImageHeight);
    //imagecopyresampled($newImageLayer,$imageSrc,0,0,0,0,$newImageWidth,$newImageHeight,$imageWidth,$imageHeight);

    return $newImageLayer;
}
---*/







class Image
{
  /**
   * @param string form_field - The HTML form field name to check
   * @param string upload_path - Image uploading path
   * @param string image_name - Name for the saving image file
   * @param string width - width for the resizing image
   * @param string height - height for the resizing image
   */
  function upload_image($form_field, $upload_path, $image_name, $width, $height)
  {
    //image upload
    if(isset($_FILES[$form_field]))
    {
      //get uploading file's extention
      $extention=strtolower($_FILES[$form_field]["type"]);
      
      $exp_del = "."; //end delimiter
      $file_name = $_FILES[$form_field]["name"];
      $file_name = explode($exp_del, $file_name);
      $extention = strtolower(end($file_name));
      
      //validate uploading file
      $validate=$this->validate_uploading_file($form_field, $extention);
      
      if($validate)
      {
        //build path if does not exists
        if(!is_dir($upload_path)){ mkdir($upload_path, 0755); }
        
        //here you can use two types of methods to resize image
        //first one is resize image to the aspect ratio
        //second one is crop image to the provided width and height
        //you can use one of the following two methods to perform above operations
        
        //resize image and save
        $this->create_thumb($_FILES[$form_field]["tmp_name"], $upload_path.$image_name, $width, $height, $extention);
        
        return true;
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }
  
  /**
   * @param string path_to_image - Path for the source image
   * @param string path_to_thumb - Path for the saving image
   * @param string thumb_width - New width for the saving image
   * @param string thumb_height - New height for the saving image
   * @param string extension - image file's extension
   */
  function create_thumb($path_to_image, $path_to_thumb, $thumb_width, $thumb_height, $extention)
  {
    $thumb_width=intval($thumb_width);
    $thumb_height=intval($thumb_height);
    
    $x1_source=0;
    $y1_source=0;
    
    //get uploading image's width and height
    list($width, $height, $img) = $this->get_image_width_height($extention, $path_to_image);
    
    //resize image for the aspect ratio
    if($width > $height)
    {
      if($thumb_height>$thumb_width)
      {
        $new_height=$height;
        $new_width=floor($new_height*($thumb_width/$thumb_height));
        
        $x1_source=floor(($width-$new_width)/2);
      }
      else
      {
        $new_width=$width;
        $new_height=floor($new_width*($thumb_height/$thumb_width));
        
        $y1_source=floor(($height-$new_height)/2);
      }
    }
    else
    {
      if($thumb_height>$thumb_width)
      {
        $new_width=$width;
        $new_height=floor($new_width*($thumb_height/$thumb_width));
        
        $y1_source=floor(($height-$new_height)/2);
      }
      else
      {
        $new_height=$height;
        $new_width=floor($new_height*($thumb_width/$thumb_height));
        
        $x1_source=floor(($width-$new_width)/2);
      }
    }
    
    if($thumb_width > $width)
    {
      $thumb_width=$width;
      $new_width=$width;
      
      $x1_source=0;
    }
    else
    {
      $x1_source=floor(($width-$new_width)/2);
    }
    
    if($thumb_height > $height)
    {
      $thumb_height=$height;
      $new_height=$height;
      
      $y1_source=0;
    }
    else
    {
      $y1_source=floor(($height-$new_height)/2);
    }
    
    $tmp_img=$this->create_temp_image($thumb_width, $thumb_height);
    
    // copy and resize old image into new image
    imagecopyresampled($tmp_img, $img, 0, 0, $x1_source, $y1_source, $thumb_width, $thumb_height, $new_width, $new_height);
    
    $this->save_image($extention, $path_to_thumb, $tmp_img);
  }

  /**
   * @param string extension - Uploading image's extension
   * @param string path_to_image - Path for of the source image
   */
  function get_image_width_height($extension, $path_to_image)
  {
    $extension=strtolower($extension);
    
    // load image and get image size
    if($extension == "jpg" || $extension == "jpeg")
    {
      $img = imagecreatefromjpeg($path_to_image);
    }
    else if( $extension == "gif")
    {
      $img = imagecreatefromgif($path_to_image);
    }
    else if( $extension == "png")
    {
      $img = imagecreatefrompng($path_to_image);
    }
    
    $width = imagesx($img);
    $height= imagesy($img);
    
    return array($width, $height, $img);
  }
  
  /**
   * @param string width - Width for the temporary image
   * @param string height - Height for the temporary image
   */
  function create_temp_image($width, $height)
  {
    // create a new temporary image
    $tmp_img = imagecreatetruecolor($width, $height);
    
    //making a transparent background for image
    imagealphablending($tmp_img, false);
    $color_transparent = imagecolorallocatealpha($tmp_img, 0, 0, 0, 127);
    imagefill($tmp_img, 0, 0, $color_transparent);
    imagesavealpha($tmp_img, true);
    
    return $tmp_img;
  }
  
  /**
   * @param string extension - image file's extension
   * @param string path - Path for the image which should be uploaded
   * @param string tmp_img - Temporary image which was created using GD libarary
   */
  function save_image($extension, $path, $tmp_img)
  {
    $extension=strtolower($extension) ;
    
    // save thumbnail into a file
    if( $extension == "jpg" || $extension == "jpeg" )
    {
      imagejpeg( $tmp_img, $path, 100 );
    }
    else if( $extension == "gif")
    {
      imagegif( $tmp_img, $path, 100 );
    }
    else if( $extension == "png")
    {
      imagepng( $tmp_img, $path, 9 );
    }
  }
  
  /**
   * @param string $field - The HTML form field name to check.
   * @param string extension - image file's extension
   */
  function validate_uploading_file($field, $extension)
  {
    //assume uploading file is not a valid image
    $match_found=false;
    
    //set valid file types and extensions for image upload
    $file_types=array();

    $file_types[]=array("type" => "image/jpeg", "ext" => "jpg");
    $file_types[]=array("type" => "image/png", "ext" => "png");
    $file_types[]=array("type" => "image/jpg", "ext" => "jpg");
    $file_types[]=array("type" => "image/gif", "ext" => "gif");

    foreach($file_types as $file_type)
    {
      $this_file_type=strtolower($_FILES[$field]["type"]);
      
      if($this_file_type == strtolower($file_type["type"]) && $extension == strtolower($file_type["ext"]))
      {
        $match_found=true;
        break;
      }
    }
    
    return $match_found;
  }
  
  /**
   * @param string path_to_image - Path for the source image
   */
  function is_valid_image($path_to_image)
  {
    //assume uploading file is not a valid image
    $valid = false;
    
    //check if file exists
    if(@file_exists($path_to_image))
    {
      try{
        //validate uploading file
        $image_size = getimagesize($path_to_image);
        
        if(isset($image_size[2]) && in_array($image_size[2], array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
        {
          $valid = true;
        }
      }
      catch(Exception $e)
      {
      }
    }
    
    return $valid;
  }
}


$form_field = "upload_file";
$upload_path = "../uploads/afml-form/";

//assume uploading a jpeg image file.
//This can be determined by file type while uploading and here we do not care about the image since it's not a big deal here.
$image_name = uniqid().".jpg";
$width = 1366;
$height = 768;

//Create an object of 'Image' class and call to 'upload_image' function which we are going to use here for our process.
$imgObj = new Image();
$upload = $imgObj->upload_image($form_field, $upload_path, $image_name, $width, $height);

if($upload)
{
  echo "Successfully uploaded the image.<br>";
}
else
{
  echo "Image uploading was failed.";
}











//date
$afml_date_y   = substr($afml_date,6,4);
$afml_date_m   = substr($afml_date,3,2);
$afml_date_d   = substr($afml_date,0,2);
$afml_date_f   = $afml_date_y.'-'.$afml_date_m.'-'.$afml_date_d;


$sql  = "SELECT afml_page_no FROM tbl_afml WHERE client_id = '".$_SESSION['client_id']."' ORDER BY afml_id DESC LIMIT 1";
$h    = mysqli_query($conn,$sql);
$row  = mysqli_fetch_assoc($h);
if($row['afml_page_no'] == $afml_page_no) {
  header('location:afml-new2.php?act=70dvi59&ntf=29dvi59-94dfvj!sdf-349ffuaw');
  exit(); 
}

//cari atribut aircraft
$sql_master  = "SELECT aircraft_serial_number,aircraft_ac_total_hrs,aircraft_ac_total_ldg,aircraft_eng_1_total_hrs,aircraft_eng_1_total_ldg,aircraft_eng_2_total_hrs,aircraft_eng_2_total_ldg,aircraft_prop_total_hrs FROM tbl_aircraft_master WHERE aircraft_reg_code = '".$aircraft_reg_code."' LIMIT 1";
$h_master    = mysqli_query($conn,$sql_master);
$row_master  = mysqli_fetch_assoc($h_master);

$aircraft_type = explode('-', $row_master['aircraft_serial_number']);

$aircraft_ac_total_hrs    = $row_master['aircraft_ac_total_hrs'];
$aircraft_ac_total_ldg    = $row_master['aircraft_ac_total_ldg'];
$aircraft_eng_1_total_hrs = $row_master['aircraft_eng_1_total_hrs'];
$aircraft_eng_1_total_ldg = $row_master['aircraft_eng_1_total_ldg'];
$aircraft_prop_total_hrs  = $row_master['aircraft_prop_total_hrs'];

$temp = explode(".", $_FILES["upload_file"]["name"]);
$target_dir = "../uploads/afml-form/";
$newfilename = $afml_page_no.'-'.date('dmY').'.'.end($temp);
$target_file = $target_dir.$newfilename;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


//file yang boleh diupload hanya PDF, WORD, XLS, JPG, PNG, GIF, PPT
/*if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "pdf" && $imageFileType != "gif" && $imageFileType != "jpeg") {
  header('location:afml-new2.php?act=70dvi59&ntf=29dvi59-94dfvj!sdf-349ffuaw');
  exit();
}*/



//if(move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file)) {


  $sql   = "INSERT INTO tbl_afml (afml_page_no,afml_date,aircraft_reg_code,aircraft_serial_number,afml_type,afml_captain_user_id,afml_copilot_user_id,afml_engineer_on_board_user_id,afml_pilot_sign,brought_fwd_ac_hrs,brought_fwd_ac_ldg,brought_fwd_eng_1_hrs,brought_fwd_eng_1_ldg,brought_fwd_prop_hrs,etcm_time,ectm_altitude,ectm_ias,ectm_tq,ectm_itt,ectm_ng,ectm_np,ectm_ff,ectm_oil_temp,ectm_oil_press,ectm_oat,afml_file,created_date,user_id,client_id) VALUES ('".$afml_page_no."','".$afml_date_f."','".$aircraft_reg_code."','".$row_master['aircraft_serial_number']."','".$aircraft_type[0]."','".$afml_pilot."','".$afml_copilot."','".$afml_engineer_on_board."','".$afml_pilot_sign."','".$aircraft_ac_total_hrs."','".$aircraft_ac_total_ldg."','".$aircraft_eng_1_total_hrs."','".$aircraft_eng_1_total_ldg."','".$aircraft_prop_total_hrs."', '".$etcm_time."','".$ectm_altitude."','".$ectm_ias."','".$ectm_tq."','".$ectm_itt."','".$ectm_ng."','".$ectm_np."','".$ectm_ff."','".$ectm_oil_temp."','".$ectm_oil_press."','".$ectm_oat."','".$newfilename."', UTC_TIMESTAMP(),'".$_SESSION['user_id']."','".$_SESSION['client_id']."')";
  //mysqli_query($conn,$sql);

  $sql   = "INSERT INTO tbl_afml_notes (afml_page_no,afml_notes_pilot,pilot_user_id,pilot_created_date,client_id) VALUES ('".$afml_page_no."','".$afml_notes_pilot."','".$_SESSION['user_id']."', UTC_TIMESTAMP(),'".$_SESSION['user_id']."')";
  mysqli_query($conn,$sql);

  //include 'afml-add-csv.php';

  //insert ke table log user
  $sql_log   = "INSERT INTO tbl_user_log (user_log_action,user_log_module,user_id,user_log_date,user_log_notes,client_id) VALUES ('AFML-ADD','AFML','".$_SESSION['user_id']."',UTC_TIMESTAMP(),'add new AFML page no: $afml_page_no','".$_SESSION['client_id']."')";
  mysqli_query($conn,$sql_log);
  header('location:afml-new2.php?act=999&ntf=29dvi59-94dfvj!sdf-349ffuaw');
//}