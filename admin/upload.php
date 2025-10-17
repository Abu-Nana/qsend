<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Upload Score</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Upload Score</li>
      </ol>
    </section>

    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <script>
              var errorMsg = '" . $_SESSION['error'] . "';
            </script>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <script>
              var successMsg = '" . $_SESSION['success'] . "';
            </script>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <?php
      include 'includes/db.php';

     // Fetch the user information from the session
      $cat = $user['cat'];
      $userg = $user['username'];
      $uploaded_by = $user['firstname'] . ' ' . $user['lastname'];


      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $centre = $_POST['centre'];
          $file = $_FILES['file'];

          $upload_dir = 'uploads/';
          $zip_path = $upload_dir . basename($file['name']);

          // Ensure the uploads directory exists and is writable
          if (!is_dir($upload_dir)) {
              mkdir($upload_dir, 0777, true);
          }

          // Create a unique directory for the extracted files
          $extract_dir = $upload_dir . pathinfo($file['name'], PATHINFO_FILENAME) . '/';
          if (!is_dir($extract_dir)) {
              mkdir($extract_dir, 0777, true);
          }

          if (move_uploaded_file($file['tmp_name'], $zip_path)) {
              $zip = new ZipArchive;
              if ($zip->open($zip_path) === TRUE) {
                  $zip->extractTo($extract_dir);
                  $zip->close();

                  $inserted_records = 0;
                  $skipped_records = 0;

                  foreach (glob($extract_dir . '*.csv') as $csv_file) {
                      $csv_data = array_map('str_getcsv', file($csv_file));
                      foreach ($csv_data as $index => $row) {
                          // Skip the header row
                          if ($index === 0) {
                              continue;
                          }

                          $matcrs = $row[1] . $row[3]; // Concatenate matno and crscode
                          $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM scores WHERE matcrs = ?");
                          $stmt_check->execute([$matcrs]);
                          $exists = $stmt_check->fetchColumn();

                          if ($exists) {
                              $skipped_records++;
                              continue;
                          }

                          $stmt = $pdo->prepare("INSERT INTO scores (serno, matno, xstat, crscode, score, zstat, stc, envid, marker, matcrs, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                          $stmt->execute(array_merge($row, [$matcrs, $userg]));
                          $inserted_records++;
                      }
                  }

                  $_SESSION['success'] = "Success! $inserted_records records inserted. $skipped_records records already existed and were skipped.";
              } else {
                  $_SESSION['error'] = "Failed to open the zip file.";
              }
          } else {
              $_SESSION['error'] = "Failed to upload the file.";
          }

          header('Location: upload.php');
      }
      ?>

      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Upload Score</h3>
            </div>
            <div class="box-body">
              <form action="upload.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="centre">Marking Centre:</label>
                  <select id="centre" name="centre" class="form-control" required>
                    <?php
                    // Fetch centres from the database
                    $stmt = $pdo->query("SELECT study_center FROM study_centers");
                    while ($centre = $stmt->fetchColumn()) {
                      echo "<option value=\"$centre\">$centre</option>";
                    }
                    ?>
                  </select>
                </div>
                
                <div class="form-group">
                  <label for="file">Select Compressed File:</label>
                  <input type="file" id="file" name="file" accept=".zip,.csv" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload Score</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof errorMsg !== 'undefined') {
      alert("Error: " + errorMsg);
    }
    if (typeof successMsg !== 'undefined') {
      alert("Success: " + successMsg);
    }
  });
</script>

</body>
</html>
