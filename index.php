<?php
session_start();
if(isset($_POST['submit'])){
  include_once("DBConnect.php");
  include_once("class.phpmailer.php");
  include_once("phpmailer/src/PHPMailer.php");
  include_once("phpmailer/src/SMTP.php");
  include_once("phpmailer/src/Exception.php");

  // $uploaddir = 'upload';
  // $key = 0;
  // $tmp_name = $_FILES["userfile"]["tmp_name"][$key];
  //         $name = $_FILES["userfile"]["name"][$key];
  //         $sendfile = "$uploaddir/$name";
  // move_uploaded_file($tmp_name, $sendfile);

  $sender_name = $_POST['sender_name'];
  $sender_email = $_POST['sender_email'];
  $host = $_POST['host'];
  $port = $_POST['port'];
  if ($port == "465") {
    $secure = "ssl";
  } else if ($port == "587") {
    $secure = "tls";
  } 
  $password = $_POST['password'];
  $receiver_email = $_POST['receiver_email'];
  // $to = $_POST['receiver_email'];
  // $receiver_email = array();
  // $receiver_email = explode("\n",$to);
  $msg_subject = $_POST['msg_subject'];
  $msg_body = $_POST['msg_body'];
  $Email_body = str_replace("\n", "<br>", $msg_body);
  $attachments = array();

  $database = new dbConnect();
  $db = $database->openConnection();
  
  $sql = "SELECT * From users";
  $result = $db->query($sql);
  $address = $result->fetch_all();
  $_SESSION["address"] = $address;

  
  $sql1 = "INSERT INTO smtp (hostname, username, password, port) VALUES('$host', '$sender_email', '$password', '$port')";
  $result1 = $db->query($sql1);
  
  foreach ($receiver_email as $Email_to)
  {
    $mail = new PHPMailer();
    $mail->IsSMTP(); 
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = TRUE;
    $mail->SMTPSecure = $secure;
    $mail->Port     = $port; 
    $mail->Host     = $host; 
    $mail->Username = $sender_email;  
    $mail->Password = $password; 
    $mail->Mailer   = "smtp";
    $mail->SetLanguage("fr", 'phpmailer/language/');
    $mail->From     = $sender_email;
    $mail->FromName = $sender_name;
    // $mail->setFrom($sender_email, $sender_name);
    $mail->addReplyTo($sender_email, $sender_name);
    $mail->AddAddress($Email_to);  
    //$mail->AddReplyTo("info@worldtradetown.com","Information");
    //foreach($attachments as $key => $value) { //loop the Attachments to be added …
    //$mail->AddAttachment(”uploads”.”/”.$value);
    //}
    // $mail->AddAttachment($sendfile);
    $mail->WordWrap = 80;                              // set word wrap
    $mail->IsHTML(true);                               // send as HTML
    $mail->Subject  =  $msg_subject;
    $mail->Body     =  $Email_body;
    $mail->AltBody  =  $msg_body;

    if(!$mail->Send())
    {
      echo "Message was not sent <p>";
      echo "Mailer Error: " . $mail->ErrorInfo;
      exit;
    }
    echo "Message to $Email_to has been sent<br>";
  }

  
}
?>


<!DOCTYPE html>
<html>
  <head>
    <title>SendMail</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <!-- Include the default stylesheet -->
    <link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/wenzhixin/multiple-select/e14b36de/multiple-select.css">
    <!-- Include plugin -->
    <script src="https://cdn.rawgit.com/wenzhixin/multiple-select/e14b36de/multiple-select.js"></script>
    <style>
    .ms-parent {
      margin-left: -14px;
      width: 290px !important;
    }
    .ms-choice {
      height: 38px;
    }
    </style>
  </head>
  <body>
    <div class="container col-11 col-sm-10 col-md-8 col-lg-7 col-xl-6 mt-4" style="background-color: #fff2e6; border: 2px solid #cebeb0;">
      <div class="row d-flex justify-content-center pt-3">
        <form action="" method="POST" enctype="multipart/form-data">
          <div class="text-center pb-2" style="color: #25e003; font-size: 30px; font-weight: bold;">
              <div class=login-text>Send Message</div>
          </div>
          <div class="col-8 pb-2">
              <label>Sender's Name:</label><span id="name_error"></span>
              <div>
                  <input type="text" class="form-control" name="sender_name" value="">
              </div>
          </div>
          <div class="col-8 pb-2">
            <label>Sender's Email:</label><span id="email_error"></span>
            <div>
              <input type="text" name="sender_email" value="demisterzisstar21@gmail.com" class="form-control" required>
            </div>
          </div>
          <div class="col-8 pb-2">
            <label>SMTP Host:</label>
            <div>
              <input type="text" name="host" value="smtp.gmail.com" class="form-control" required>
            </div>
          </div>
          <div class="col-8 pb-2">
            <label>SMTP Port:</label>
            <div class="d-flex">
              <input type="radio" class="align-self-center" name="port" value="465" style="height: 18px; width: 40px;" required>465
              <input type="radio" class="align-self-center ml-5" name="port" value="587" style="height: 18px; width: 40px;"  required>587
            </div>
          </div>
          <div class="col-8 pb-2">
            <label>SMTP Password:</label>
            <div>
              <input type="password" name="password" value="" class="form-control" required>
            </div>
          </div>
          <div class="col-8 pb-2">
            <label>Receiver's Email Address:</label><a href="add.php"  class="add" style="padding-left: 2%;"><button type="button" class="add-button btn btn-success" style="border-radius: 15px; padding-top: 1px; padding-bottom: 1px;">Add Email</button></a>
            <div class="col-12 pt-2">
              <!-- <textarea name="receiver_email" rows="15" cols="60" value="" class="form-control" required></textarea> -->
              <select id="email-select" name="receiver_email[]" size="7" style="width:275px;" multiple required>
                <?php 
                include_once("DBConnect.php");
                $database = new dbConnect();
                $db = $database->openConnection();
                $sql = "SELECT email From users";
                $result = $db->query($sql);
                while($address = mysqli_fetch_array($result)) {
                // echo "<option><label><input type='checkbox' name='receiver_email[]'>" .$address['email']."</label></option>";
                echo "<option value='".$address['email']."'>" .$address['email']. "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="col-8 pb-2">
            <label>Subject:</label><span id="email_error"></span>
            <div>
              <input type="text" name="msg_subject" value="" class="form-control" required>
            </div>
          </div>
          <div class="col-12 pb-2">
            <label>Message Body:</label><span id="email_error"></span>
            <div>
              <textarea name="msg_body" rows="10" cols="60" value="" class="form-control" required></textarea>
            </div>
          </div>
          <div class="col-12 pt-2 pb-4">
              <div class="d-flex justify-content-center">
                <button type="submit" name="submit" class="col-12 btn btn-primary">Send Message</button>
              </div>
          </div>
        </form>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        $("#email-select").multipleSelect({
          filter: true
        });
      })
    </script>
  </body>
</html>