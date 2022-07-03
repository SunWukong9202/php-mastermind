<?php
  require "db.php";
  $error = null;

  session_start();


  if(!isset($_SESSION['user'])) {
    header('Location: login.php');
    return;
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(empty($_POST['name']) || empty($_POST['phone_number'])) {
      $error = "Please fill all the fields.";
    }else if(strlen($_POST['phone_number']) < 9) {
      $error = 'Wrong format please add only 9 digits';
    }else{
      $name = $_POST['name'];
      $phoneNumber = $_POST['phone_number'];
      $user_id = $_SESSION['user']['id'];

      $statement = $conn->prepare("INSERT INTO contacts 
      (name,user_id, phone_number) VALUES (:name, $user_id,:phone_number)");
      
      $statement->bindParam(":name", $_POST['name']);
      $statement->bindParam(":phone_number", $_POST['phone_number']);
      $statement->execute();

      header("Location: home.php");
    } 

  }
?>
<?php require "partials/header.php" ?>

    <div class="container pt-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <?php if($error) :?>
            <div class="alert alert-danger"><?= $error ?></div>
          <?php endif ?>
          <div class="card">
            <div class="card-header">Add New Contact</div>
            <div class="card-body">
              <form method="POST" action="add.php">
                <div class="mb-3 row">
                  <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

                  <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="name"  autocomplete="name" autofocus>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="phone_number" class="col-md-4 col-form-label text-md-end">Phone Number</label>

                  <div class="col-md-6">
                    <input id="phone_number" type="tel" class="form-control" name="phone_number"  autocomplete="phone_number" autofocus>
                  </div>
                </div>

                <div class="mb-3 row">
                  <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php require "partials/footer.php" ?>

