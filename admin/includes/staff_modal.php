<!-- Add -->
<div class="modal fade" id="add_staff">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Add New Staff Reocord</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="staff_add.php" enctype="multipart/form-data">
          		  <div class="form-group">
                  	<label for="ID" class="col-sm-3 control-label">ID</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="id" name="id" required>
                  	</div>
                </div>
                    <div class="form-group">
                  	<label for="name" class="col-sm-3 control-label">Email</label>

                  	<div class="col-sm-9">
                    	<input type="email" class="form-control" id="email" name="email" required>
                  	</div>
                </div>
                    <div class="form-group">
                  	<label for="ippis" class="col-sm-3 control-label">IPPIS Number</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="ippis" name="ippis" required>
                  	</div>
                </div>
                <div class="form-group">
                  	<label for="name" class="col-sm-3 control-label">Full Staff Name</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="name" name="name" required>
                  	</div>
                </div>
                    <div class="form-group">
                  	<label for="bankname" class="col-sm-3 control-label">Bank Name</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="bankname" name="bankname" required>
                  	</div>
                </div>
                    <div class="form-group">
                  	<label for="accountno" class="col-sm-3 control-label">Account Naumber</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="acno" name="acno" required>
                  	</div>
                </div>
                    
                     <div class="form-group">
                    <label for="designation" class="col-sm-3 control-label">Designation</label>

                    <div class="col-sm-9">
                      <select class="form-control" name="designation" id="designation" required>
                        <option value="" selected>- Select -</option>
                        <?php
                          $sql = "SELECT * FROM positions";
                          $query = $conn->query($sql);
                          while($prow = $query->fetch_assoc()){
                            echo "
                              <option value='".$prow['id']."'>".$prow['name']."</option>
                            ";
                          }
                        ?>
                      </select>
                    </div>
                </div>
                    
                    <div class="form-group">
                  	<label for="school" class="col-sm-3 control-label">School</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="school" name="school" required>
                  	</div>
                </div>
                   <div class="form-group">
                    <label for="department" class="col-sm-3 control-label">Department</label>

                    <div class="col-sm-9">
                      <select class="form-control" name="department" id="department" required>
                        <option value="" selected>- Select -</option>
                        <?php
                          $sql = "SELECT * FROM positions";
                          $query = $conn->query($sql);
                          while($prow = $query->fetch_assoc()){
                            echo "
                              <option value='".$prow['id']."'>".$prow['name']."</option>
                            ";
                          }
                        ?>
                      </select>
                    </div>
                </div>
                    <div class="form-group">
                  	<label for="location" class="col-sm-3 control-label">Location</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="location" name="location" required>
                  	</div>
                </div>
                     <div class="form-group">
                  	<label for="unit" class="col-sm-3 control-label">Unit</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="unit" name="unit" required>
                  	</div>
                </div>
                     <div class="form-group">
                  	<label for="grade" class="col-sm-3 control-label">Grade</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="grade" name="grade" required>
                  	</div>
                </div>
                     <div class="form-group">
                  	<label for="step" class="col-sm-3 control-label">Grade Step</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="step" name="step" required>
                  	</div>
                </div>
                     <div class="form-group">
                  	<label for="pension_admin" class="col-sm-3 control-label">Pension Administrator</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="pension_admin" name="pension_admin" required>
                  	</div>
                </div>
                     <div class="form-group">
                  	<label for="pin" class="col-sm-3 control-label">Pension PIN</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="pin" name="pin" required>
                  	</div>
                </div>
                     <div class="form-group">
                  	<label for="tin" class="col-sm-3 control-label">TIN Number</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="tin" name="tin" required>
                  	</div>
                </div>
                     <div class="form-group">
                  	<label for="appointment_date" class="col-sm-3 control-label">Date of Appointment</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="appointment_date" name="appointment_date" required>
                  	</div>
                </div>
                    <div class="form-group">
                  	<label for="datepicker_add" class="col-sm-3 control-label">Birth date</label>

                  	<div class="col-sm-9"> 
                      <div class="date">
                        <input type="text" class="form-control" id="birthdate" name="birthdate">
                      </div>
                  	</div>
                </div>
               
                     <div class="form-group">
                  	<label for="union" class="col-sm-3 control-label">Union Name</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="union" name="union" required>
                  	</div>
                </div>
                     <div class="form-group">
                  	<label for="nhf" class="col-sm-3 control-label">NHF Number</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="nhf" name="nhf" required>
                  	</div>
                </div>
             
             
               
                 <div class="form-group">
                    <label for="taxstate" class="col-sm-3 control-label">Tax State</label>

                    <div class="col-sm-9"> 
                      <select class="form-control" name="taxstate" id="taxstate" required>
                        <option value="" selected>- Select -</option>
                        <option value="abuja">Abuja</option>
                        <option value="enugu">Enugu</option>
                      </select>
                    </div>
                </div>
               
                <div class="form-group">
                    <label for="gender" class="col-sm-3 control-label">Gender</label>

                    <div class="col-sm-9"> 
                      <select class="form-control" name="gender" id="gender" required>
                        <option value="" selected>- Select -</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                    </div>
                </div>
                
               
                <div class="form-group">
                    <label for="photo" class="col-sm-3 control-label">Photo</label>

                    <div class="col-sm-9">
                      <input type="file" name="photo" id="photo">
                    </div>
                </div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-primary btn-flat" name="add_staff"><i class="fa fa-save"></i> Add Staff</button>
            	</form>
          	</div>
        </div>
    </div>
</div>

<!-- Edit -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="staff_edit.php">
            		<input type="hidden" class="id" name="id">
                <div class="form-group">
                    <label for="edit_name" class="col-sm-3 control-label">Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="edit_name" name="name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit_title" class="col-sm-3 control-label">Designation</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="edit_title" name="title">
                    </div>
                </div>
                
                   
                   
                   
                    <div class="form-group">
                    <label for="edit_department" class="col-sm-3 control-label">Department</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="edit_department" name="department">
                    </div>
                </div>
                   
               
              
                
                
               
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i> Update</button>
            	</form>
          	</div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="employee_delete.php">
            		<input type="hidden" class="empid" name="id">
            		<div class="text-center">
	                	<p>DELETE EMPLOYEE</p>
	                	<h2 class="bold del_employee_name"></h2>
	            	</div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-danger btn-flat" name="delete"><i class="fa fa-trash"></i> Delete</button>
            	</form>
          	</div>
        </div>
    </div>
</div>

<!-- Update Photo -->
<div class="modal fade" id="edit_photo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b><span class="del_employee_name"></span></b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="employee_edit_photo.php" enctype="multipart/form-data">
                <input type="hidden" class="empid" name="id">
                <div class="form-group">
                    <label for="photo" class="col-sm-3 control-label">Photo</label>

                    <div class="col-sm-9">
                      <input type="file" id="photo" name="photo" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="upload"><i class="fa fa-check-square-o"></i> Update</button>
              </form>
            </div>
        </div>
    </div>
</div>    