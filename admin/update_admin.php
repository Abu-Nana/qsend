<div class="modal fade" id="update_modal<?php echo $fetch['id'];?>" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST" action="admin_update_query.php">
				<div class="modal-header">
					<h3 class="modal-title">Update Admin Account</h3>
				</div>
				<div class="modal-body">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<div class="form-group">
							<label>Firstname</label>
							<input type="hidden" name="id" value="<?php echo $fetch['id'];?>"/>
							<input type="text" name="firstname" value="<?php echo $fetch['firstname'];?>" class="form-control" required="required"/>
						</div>
						<div class="form-group">
							<label>Latsname</label>
							<input type="text" name="lastname" value="<?php echo $fetch['lastname'];?>" class="form-control" required="required" />
						</div>
						<div class="form-group">
							<label>Password</label>
							<input type="password" name="password" value="<?php echo $fetch['password'];?>" class="form-control" required="required"/>
						</div>
                        
					</div>
				</div>
				<div style="clear:both;"></div>
				<div class="modal-footer">
					<button name="update_admin" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Update Admin</button>
                    
					<button class="btn btn-danger" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
				</div>
				</div>
			</form>
		</div>
	</div>
</div>