<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo (!empty($user['photo'])) ? '../images/'.$user['photo'] : '../images/profile.jpg'; ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $user['firstname'].' '.$user['lastname']; ?></p>
          <a><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">NAVIGATION</li>
        <?php
          // Assuming $user is already set with the current user's details
          $cat = $user['cat'];

          if ($cat == 'dea' || $cat == 'admin') {
            // Show all menus for 'dea' and 'admin' users
            echo '
            <li class=""><a href="home.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li class="header">Study Centre Details</li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-users"></i>
                <span>Study Centre List</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="centres.php"><i class="fa fa-users"></i> Study Centres</a></li>
                <li><a href="add-centre.php"><i class="fa fa-plus"></i> Add New Study Centre</a></li>
              </ul>
            </li>
            <li class="header">QUESTIONS ACTIONS</li>
            <li><a href="reg-data.php"><i class="fa fa-files-o"></i> <span>Student Reg Data</span></a></li>
            <li><a href="qsend.php"><i class="fa fa-files-o"></i> <span>Send Questions</span></a></li>
            <li><a href="se_qsend.php"><i class="fa fa-files-o"></i> <span>Mondays Alone</span></a></li>
            <li><a href="se_qsend2.php"><i class="fa fa-files-o"></i> <span>South East Questions</span></a></li>
            <li><a href="semitems.php"><i class="fa fa-files-o"></i> <span>View All Questions</span></a></li>
            <li><a href="sentitems.php"><i class="fa fa-files-o"></i> <span>View Sent Questions</span></a></li>
            <li><a href="rawslip.php"><i class="fa fa-clock-o"></i> <span>Upload Student Reg Data</span></a></li>
            <li><a href="upload.php"><i class="fa fa-upload"></i> <span>Upload POP Score</span></a></li>
             <li class="active"><a href="uploadescore.php"><i class="fa fa-list-alt"></i> <span>My Uploaded Scores</span></a></li>
            ';
          } else if ($cat == 'others') {
            // Show only Upload POP Score menu for 'others'
            echo '
            <li class=""><a href="home.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li><a href="upload.php"><i class="fa fa-upload"></i> <span>Upload POP Score</span></a></li>
            <li class="active"><a href="uploadescore.php"><i class="fa fa-list-alt"></i> <span>My Uploaded Scores</span></a></li>
            ';
          }
        ?>
      </ul>
    </section>
    <!-- /.sidebar -->
</aside>
