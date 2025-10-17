<?php
include 'includes/session.php';
include 'includes/header.php';
include 'includes/db.php';

// Ensure user is logged in and retrieve user_id
if (!isset($_SESSION['user'])) {
    // Redirect to login page or handle unauthorized access
    header('Location: login.php');
    exit;
}

//$user_id = $_SESSION['user']['username']; // Assuming 'username' is correct, adjust if necessary
$user_id = $user['username'];

// Pagination logic
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 50;
$offset = ($page - 1) * $records_per_page;

// Query to fetch scores uploaded by the current user
$stmt = $pdo->prepare("SELECT matno, xstat, crscode, score, zstat, stc, envid, marker, matcrs FROM scores WHERE uploaded_by = ? LIMIT ?, ?");
$stmt->execute([$user_id, $offset, $records_per_page]);
$scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_scores_stmt = $pdo->prepare("SELECT COUNT(*) FROM scores WHERE uploaded_by = ?");
$total_scores_stmt->execute([$user_id]);
$total_scores = $total_scores_stmt->fetchColumn();

$total_pages = ceil($total_scores / $records_per_page);

?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  
  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>My Uploaded Scores</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">My Uploaded Scores</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Uploaded Scores</h3>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Mat No</th>
                      <th>Xstat</th>
                      <th>Crs Code</th>
                      <th>Score</th>
                      <th>Zstat</th>
                      <th>STC</th>
                      <th>Envid</th>
                      <th>Marker</th>
                      <th>Matcrs</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($scores as $score): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($score['matno']); ?></td>
                        <td><?php echo htmlspecialchars($score['xstat']); ?></td>
                        <td><?php echo htmlspecialchars($score['crscode']); ?></td>
                        <td><?php echo htmlspecialchars($score['score']); ?></td>
                        <td><?php echo htmlspecialchars($score['zstat']); ?></td>
                        <td><?php echo htmlspecialchars($score['stc']); ?></td>
                        <td><?php echo htmlspecialchars($score['envid']); ?></td>
                        <td><?php echo htmlspecialchars($score['marker']); ?></td>
                        <td><?php echo htmlspecialchars($score['matcrs']); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="box-footer">
              <div class="text-center">
                <ul class="pagination pagination-sm no-margin">
                  <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li <?php if ($i === $page) echo 'class="active"'; ?>><a href="my_scores.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                  <?php endfor; ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
