<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<style>
/* Modern Centres Page Styles */
.content-wrapper {
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  min-height: 100vh;
}

.page-header-elegant {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 30px;
  border-radius: 15px;
  margin-bottom: 30px;
  box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
  position: relative;
  overflow: hidden;
}

.page-header-elegant::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -10%;
  width: 300px;
  height: 300px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
}

.page-header-elegant h1 {
  margin: 0;
  font-size: 32px;
  font-weight: 700;
  color: white;
  position: relative;
  z-index: 2;
}

.page-header-elegant p {
  margin: 5px 0 0 0;
  opacity: 0.9;
  font-size: 16px;
  position: relative;
  z-index: 2;
}

.stats-mini-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.mini-stat-card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
  border-left: 4px solid var(--accent-color);
}

.mini-stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.12);
}

.mini-stat-card.card-primary { --accent-color: #667eea; }
.mini-stat-card.card-success { --accent-color: #38ef7d; }
.mini-stat-card.card-warning { --accent-color: #f2c94c; }
.mini-stat-card.card-info { --accent-color: #00f2fe; }

.mini-stat-card i {
  font-size: 28px;
  color: var(--accent-color);
  margin-bottom: 10px;
}

.mini-stat-card .stat-value {
  font-size: 28px;
  font-weight: 700;
  color: #2c3e50;
  margin: 5px 0;
}

.mini-stat-card .stat-label {
  font-size: 13px;
  color: #7f8c8d;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.table-container-elegant {
  background: white;
  border-radius: 15px;
  padding: 30px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
  overflow: hidden;
}

.table-header-elegant {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
  padding-bottom: 20px;
  border-bottom: 2px solid #f0f0f0;
  flex-wrap: wrap;
  gap: 15px;
}

.table-header-elegant h3 {
  margin: 0;
  font-size: 22px;
  font-weight: 700;
  color: #2c3e50;
}

.table-header-elegant h3 i {
  margin-right: 10px;
  color: #667eea;
}

.search-box-elegant {
  position: relative;
  flex: 1;
  max-width: 400px;
  min-width: 250px;
}

.search-box-elegant input {
  width: 100%;
  padding: 12px 45px 12px 20px;
  border: 2px solid #e0e0e0;
  border-radius: 10px;
  font-size: 14px;
  transition: all 0.3s ease;
}

.search-box-elegant input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.search-box-elegant i {
  position: absolute;
  right: 18px;
  top: 50%;
  transform: translateY(-50%);
  color: #7f8c8d;
}

#example1 {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

#example1 thead th {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 12px;
  letter-spacing: 1px;
  padding: 15px 12px;
  border: none;
  white-space: nowrap;
}

#example1 thead th:first-child {
  border-radius: 10px 0 0 0;
}

#example1 thead th:last-child {
  border-radius: 0 10px 0 0;
}

#example1 tbody tr {
  transition: all 0.3s ease;
  border-bottom: 1px solid #f0f0f0;
}

#example1 tbody tr:hover {
  background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, rgba(255,255,255,1) 100%);
  transform: scale(1.01);
}

#example1 tbody td {
  padding: 15px 12px;
  color: #2c3e50;
  vertical-align: middle;
  font-size: 14px;
}

.btn-elegant-edit {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-elegant-edit:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
  color: white;
}

.btn-elegant-edit:active {
  transform: translateY(0);
}

.btn-elegant-edit i {
  margin-right: 5px;
}

.alert-modern {
  border-radius: 10px;
  border: none;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  padding: 20px;
  animation: slideInDown 0.5s ease;
}

@keyframes slideInDown {
  from {
    transform: translateY(-20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.alert-success.alert-modern {
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  color: white;
}

.alert-danger.alert-modern {
  background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
  color: white;
}

.alert-modern .close {
  color: white;
  opacity: 0.8;
  text-shadow: none;
}

.alert-modern .close:hover {
  opacity: 1;
}

.breadcrumb {
  background: white;
  border-radius: 10px;
  padding: 12px 20px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  margin-bottom: 20px;
}

/* DataTables custom styling */
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  padding: 8px 12px;
  transition: all 0.3s ease;
}

.dataTables_wrapper .dataTables_length select:focus,
.dataTables_wrapper .dataTables_filter input:focus {
  border-color: #667eea;
  outline: none;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
  border-radius: 8px;
  margin: 0 2px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  color: white !important;
}

/* Responsive */
@media (max-width: 768px) {
  .page-header-elegant h1 {
    font-size: 24px;
  }
  
  .table-header-elegant {
    flex-direction: column;
    align-items: stretch;
  }
  
  .search-box-elegant {
    max-width: 100%;
  }
  
  .stats-mini-cards {
    grid-template-columns: 1fr;
  }
  
  .table-container-elegant {
    padding: 20px 15px;
    overflow-x: auto;
  }
}

/* Loading animation */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.table-container-elegant {
  animation: fadeIn 0.5s ease;
}

.mini-stat-card:nth-child(1) { animation: fadeIn 0.5s ease 0.1s both; }
.mini-stat-card:nth-child(2) { animation: fadeIn 0.5s ease 0.2s both; }
.mini-stat-card:nth-child(3) { animation: fadeIn 0.5s ease 0.3s both; }
.mini-stat-card:nth-child(4) { animation: fadeIn 0.5s ease 0.4s both; }
</style>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="page-header-elegant">
        <h1>
          <i class="fa fa-building"></i> Study Centres Management
        </h1>
        <p>View and manage all study centres in the system</p>
      </div>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Study Centres</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible alert-modern'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible alert-modern'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      
      <!-- Statistics Mini Cards -->
      <div class="stats-mini-cards">
        <?php
          $sql_total = "SELECT COUNT(*) as total FROM study_centers";
          $result_total = $conn->query($sql_total);
          $total_centres = $result_total->fetch_assoc()['total'];
          
          $sql_active = "SELECT COUNT(*) as total FROM study_centers WHERE study_centre_email IS NOT NULL AND study_centre_email != ''";
          $result_active = $conn->query($sql_active);
          $active_centres = $result_active->fetch_assoc()['total'];
          
          $sql_directors = "SELECT COUNT(DISTINCT director) as total FROM study_centers WHERE director IS NOT NULL AND director != ''";
          $result_directors = $conn->query($sql_directors);
          $total_directors = $result_directors->fetch_assoc()['total'];
          
          $sql_with_phone = "SELECT COUNT(*) as total FROM study_centers WHERE phone_number IS NOT NULL AND phone_number != ''";
          $result_phone = $conn->query($sql_with_phone);
          $with_phone = $result_phone->fetch_assoc()['total'];
        ?>
        
        <div class="mini-stat-card card-primary">
          <i class="fa fa-building"></i>
          <div class="stat-value"><?php echo $total_centres; ?></div>
          <div class="stat-label">Total Centres</div>
        </div>
        
        <div class="mini-stat-card card-success">
          <i class="fa fa-envelope"></i>
          <div class="stat-value"><?php echo $active_centres; ?></div>
          <div class="stat-label">With Email</div>
        </div>
        
        <div class="mini-stat-card card-warning">
          <i class="fa fa-user"></i>
          <div class="stat-value"><?php echo $total_directors; ?></div>
          <div class="stat-label">Directors</div>
        </div>
        
        <div class="mini-stat-card card-info">
          <i class="fa fa-phone"></i>
          <div class="stat-value"><?php echo $with_phone; ?></div>
          <div class="stat-label">With Phone</div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-xs-12">
          <div class="table-container-elegant">
            <div class="table-header-elegant">
              <h3><i class="fa fa-list"></i> Study Centres List</h3>
              <div class="search-box-elegant">
                <input type="text" id="quickSearch" placeholder="Quick search centres...">
                <i class="fa fa-search"></i>
              </div>
            </div>
            <div class="table-responsive">
             <table id="example1" class="table">
                <thead>
                    
                  <th> ID</th>
                  <th> Centre Code</th>
                  <th>Centre Name</th>
                   <th>Email Address</th>
                  <th>Centre Director</th>
                    <th>Centre Mobile Number</th>
                  
                  <th>Edit</th>
                </thead>
                <tbody>
                     
                  <?php
                   
				//	require 'conn.php';
                        $sql="SELECT *, study_centers.id AS id FROM study_centers";
                        $query = $conn->query($sql);
                         while($fetch = $query->fetch_assoc())
					{
				?>
                        <tr>
                            
                            <td><?php echo $fetch['id'];?></td>
                            <td><?php echo $fetch['study_center_code'];?></td>
                            <td><?php echo $fetch['study_center'];?></td>
                            <td><?php echo $fetch['study_centre_email'];?></td>
                            <td><?php echo $fetch['director'];?></td>
                            <td><?php echo $fetch['phone_number'];?></td>
                            
                          <td><button class="btn btn-elegant-edit" data-toggle="modal" type="button" data-target="#update_modal<?php echo $fetch['id'];?>"><i class="fa fa-edit"></i> Edit Details</button></td>
                                     
                           
                            
                        
                        </tr>
                      <?php
					
					include 'update_user.php';
                         
					
					}
				?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>   
  </div>
    
  <?php include 'includes/footer.php'; ?>
 
</div>
<?php include 'includes/scripts.php'; ?>

<?php include 'includes/datatable_initializer.php'; ?>

<script>
$(document).ready(function() {
  // Enhanced quick search functionality
  $('#quickSearch').on('keyup', function() {
    var value = $(this).val().toLowerCase();
    $('#example1 tbody tr').filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });
  
  // Add smooth scroll to top button
  var scrollButton = $('<button class="scroll-to-top" title="Scroll to top"><i class="fa fa-chevron-up"></i></button>');
  $('body').append(scrollButton);
  
  $(window).scroll(function() {
    if ($(this).scrollTop() > 300) {
      scrollButton.fadeIn();
    } else {
      scrollButton.fadeOut();
    }
  });
  
  scrollButton.on('click', function() {
    $('html, body').animate({scrollTop: 0}, 600);
  });
  
  // Row click animation enhancement
  $('#example1 tbody tr').on('click', function(e) {
    if (!$(e.target).is('button') && !$(e.target).closest('button').length) {
      $(this).addClass('row-flash');
      setTimeout(() => {
        $(this).removeClass('row-flash');
      }, 300);
    }
  });
});
</script>

<style>
/* Scroll to top button */
.scroll-to-top {
  position: fixed;
  bottom: 30px;
  right: 30px;
  width: 50px;
  height: 50px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 50%;
  font-size: 20px;
  cursor: pointer;
  box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
  transition: all 0.3s ease;
  display: none;
  z-index: 1000;
}

.scroll-to-top:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
}

.scroll-to-top:active {
  transform: translateY(-2px);
}

/* Row flash animation */
@keyframes rowFlash {
  0%, 100% { background: transparent; }
  50% { background: rgba(102, 126, 234, 0.1); }
}

.row-flash {
  animation: rowFlash 0.3s ease;
}

/* Enhanced hover effect for table rows */
#example1 tbody tr {
  cursor: pointer;
}

/* Add badges for email status */
.badge-has-email {
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 600;
  margin-left: 5px;
}

.badge-no-email {
  background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 600;
  margin-left: 5px;
}
</style>
</body>
</html>

