<?php
  require "db.php";
  $error = null;
  $sucess = null;
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])) {
      $error = 'Please fill all the fields';
    }else if(!str_contains($_POST['email'], '@')){
      $error = 'Incorrect email format';
    }else{
      $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email');
      $stmt->execute([':email' => $_POST['email']]);
      if($stmt->rowCount() == 0){
        $stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $stmt->execute(
          [
            ':name' => $_POST['name'],
            ':email' => $_POST['email'],
            ':password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
          ]
        );
        header('Location: home.php');
      }else{
        $error = 'this user already has an account';
      }
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
            <div class="card-header">Register</div>
            <div class="card-body">
              <form method="POST" action="register.php">
                <div class="mb-3 row">
                  <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

                  <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="name"  autocomplete="name" autofocus>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>

                  <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email"  autocomplete="email" autofocus>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

                  <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password"  autocomplete="password" autofocus>
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
