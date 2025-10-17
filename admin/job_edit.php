<div class="modal fade" id="update_modal<?php echo $fetch['id']?>" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST" action="update_query.php">
				<div class="modal-header">
					<h3 class="modal-title">Update User</h3>
				</div>
				<div class="modal-body">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<div class="form-group">
							<label>Designation</label>
							<input type="hidden" name="id" value="<?php echo $fetch['id']?>"/>
							<input type="text" name="Full_Name" value="<?php echo $fetch['Full_Name']?>" class="form-control" required="required"/>
						</div>
						<div class="form-group">
							<label>IPPISS</label>
							<input type="text" name="IPPIS_ID" value="<?php echo $fetch['IPPIS_ID']?>" class="form-control" required="required" />
						</div>
						<div class="form-group">
							<label>Address</label>
							<input type="text" name="address" value="<?php echo $fetch['address']?>" class="form-control" required="required"/>
						</div>
					</div>
				</div>
				<div style="clear:both;"></div>
				<div class="modal-footer">
					<button name="update" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Update</button>
					<button class="btn btn-danger" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
				</div>
				</div>
			</form>
		</div>
	</div>
</div>