<?php
  require "db.php";
  $error = null;
  $id = $_GET['id'] ?? null;

  session_start();

  if(!isset($_SESSION['user'])) {
    header('Location: login.php');
    return;
  }

  $stmt = $conn->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
  $stmt->execute([":id" => $id]); //array-as with all var to bind and execute in the stmt
  if($stmt->rowCount() == 0) {
    http_response_code(404);
    echo 'HTTP 404 NOT FOUND';
    return;
  }
  $contact = $stmt->fetch(PDO::FETCH_ASSOC);

  if($contact['user_id'] != $_SESSION['user']['id']) {
    http_response_code(403);
    echo 'HTTP 403 UNAUTHORIZED';
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
      $stmt = $conn->prepare("UPDATE contacts SET name= :name, phone_number= :phoneNumber WHERE id = :id");
      $stmt->execute([
        ":id" => $id,
        ":name" => $name,
        ":phoneNumber" => $phoneNumber
      ]);

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
            <div class="card-header">Edit Contact</div>
            <div class="card-body">
              <form method="POST" action="edit.php?id=<?= $contact['id'] ?>">
                <div class="mb-3 row">
                  <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

                  <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="name"  autocomplete="name" value="<?= $contact['name'] ?>" autofocus>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="phone_number" class="col-md-4 col-form-label text-md-end">Phone Number</label>

                  <div class="col-md-6">
                    <input id="phone_number" type="tel" class="form-control" name="phone_number"  autocomplete="phone_number" value="<?= $contact['phone_number'] ?>" autofocus>
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

