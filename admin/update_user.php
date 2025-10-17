<div class="modal fade" id="update_modal<?php echo $fetch['id'];?>" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST" action="update_query.php">
				<div class="modal-header">
					<h3 class="modal-title">Update Centre Details</h3>
				</div>
				<div class="modal-body">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						
						<div class="form-group">
						<input type="hidden" name="id" value="<?php echo $fetch['id'];?>"/>
							<label>Email Address</label>
							<input type="text" name="study_centre_email" value="<?php echo $fetch['study_centre_email'];?>" class="form-control" required="required" />
						</div>
						<div class="form-group">
							<label>Centre Director</label>
							<input type="text" name="director" value="<?php echo $fetch['director'];?>" class="form-control" required="required"/>
						</div>
                        <div class="form-group">
							<label>Phone Number</label>
							<input type="text" name="phone_number" value="<?php echo $fetch['phone_number'];?>" class="form-control" required="required"/>
						</div>
                         
                        
					</div>
				</div>
				<div style="clear:both;"></div>
				<div class="modal-footer">
					<button name="update" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Update Centre</button>
					<button class="btn btn-danger" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
				</div>
				</div>
			</form>
		</div>
	</div>
</div>