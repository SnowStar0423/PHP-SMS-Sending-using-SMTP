<?php
session_start();
  if (isset($_POST["submit"])) {
    include_once 'DBConnect.php';
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $birthday = $_POST['birthday'];
    $phone = $_POST['phone'];
    var_dump($phone);
    $database = new dbConnect();
    
    $db = $database->openConnection();
    $sql1 = "SELECT * FROM users WHERE email='$email'";
    $user = $db->query($sql1);
    $result = $user->fetch_all(); 
    
    if (empty($result)) {
      $sql = "INSERT INTO users (name, email, birthday, phonenumber) VALUES('$name', '$email', '$birthday', '$phone')";
      $use = $db->query($sql);
      return header('location: index.php');
    } else {
      echo "Email already exist in Address.";
    }
    $database->closeConnection();
  }
?>
<!DOCTYPE html>
<html>
  <head>
  <title>Add Email</title>
  <link rel="stylesheet" type="text/css" href="CSS/styles.css">
  </head>
  <body>
    <div class="demo-content">
      <div class="container">
        <?php
        if (! empty($response)) {
            ?>
        <div id="response" class="<?php echo $response["type"]; ?>"><?php echo $response["message"]; ?></div>
        <?php
        }
        ?>
        <form action="" method="POST" enctype="multipart/form-data">
          <div class="row">
            <div class=add-text >Add Email</div>
          </div>
        
          <div class="row">
              <label>Name:</label>
              <div>
                <input class="input" type="text" class="form-control" name="name" id="name" required>
              </div>
          </div>

          
          <div class="row">
            <label>Email</label><span id="email_error"></span>
            <div>
              <input class="input" type="text" name="email" id="email" class="form-control" required>
            </div>
          </div>

          <div class="row">
            <label>Birthday</label>
            <div>
              <input class="input" type="date" name="birthday" id="birthday" class="form-control">
            </div>
          </div>

          <div class="row">
            <label>Phone Number</label>
            <div>
              <input class="input" type="tel" name="phone" id="phone" class="form-control">
            </div>
          </div>

          <div class="row">
            <div>
              <a href="index.php"><button type="button" class="btn-store store-no">Cancel</button></a>
              <button type="submit" name="submit" class="btn-store store-yes">Add</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    
  </body>
</html>