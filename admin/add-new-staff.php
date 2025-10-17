<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add New Staff Record
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Staff</li>
        <li class="active">Add New Staff</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
            <div class="box-body">
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
                    	<select class="form-control" name="bankname" value="" class="form-control" required="required"/>
                                 <option value="" selected>- Select State -</option>
                       <option value="FidelityBankPlc">Fidelity Bank Plc</option>
                        <option value="FirstCityMonumentBankLimited">First City Monument Bank Limited</option>
                        <option value="FirstBankofNigeriaLimited">First Bank of Nigeria Limited</option>
                        <option value="GuarantyTrustHoldingCompanyPlc">Guaranty Trust Holding Company Plc</option>
                        <option value="UnionBankofNigeriaPlc">Union Bank of Nigeria Plc</option>
                        <option value="UnitedBankforAfricaPlc">United Bank for Africa Plc</option>
                        <option value="ZenithBankPlc">Zenith Bank Plc</option>
                        <option value="CitibankNigeriaLimited">Citibank Nigeria Limited</option>
                        <option value="EcobankNigeria">Ecobank Nigeria</option>
                        <option value="HeritageBankPlc">Heritage Bank Plc</option>
                        <option value="KeystoneBankLimited">Keystone Bank Limited</option>
                        <option value="StanbicIBTCBankPlc">Stanbic IBTC Bank Plc</option>
                        <option value="StandardChartered">Standard Chartered</option>
                        <option value="SterlingBankPlc">Sterling Bank Plc</option>
                        <option value="TitanTrustBankLimited">Titan Trust Bank Limited</option>
                        <option value="UnityBankPlc">Unity Bank Plc</option>
                        <option value="WemaBankPlc">Wema Bank Plc</option>
                        <option value="GlobusBankLimited">Globus Bank Limited</option>
                        <option value="ParallexBankLimited">Parallex Bank Limited</option>
                        <option value="ProvidusBankLimited">Providus Bank Limited</option>
                        <option value="SunTrustBankNigeriaLimited">SunTrust Bank Nigeria Limited</option>
                        <option value="JaizBankPlc">Jaiz Bank Plc</option>
                        <option value="TAJBankLimited">TAJBank Limited</option>
                        <option value="MutualTrustMicrofinanceBank">Mutual Trust Microfinance Bank</option>
                        <option value="RephidimMicrofinanceBank">Rephidim Microfinance Bank</option>
                        <option value="ShepherdTrustMicrofinanceBank">Shepherd Trust Microfinance Bank</option>
                        <option value="EmpireTrustMicrofinanceBank">Empire Trust Microfinance Bank</option>
                        <option value="FincaMicrofinanceBankLimited">Finca Microfinance Bank Limited</option>
                        <option value="FinaTrustMicrofinanceBank">Fina Trust Microfinance Bank</option>
                        <option value="AccionMicrofinanceBank">Accion Microfinance Bank</option>
                        <option value="PeaceMicrofinanceBank">Peace Microfinance Bank</option>
                        <option value="InfinityMicrofinanceBank">Infinity Microfinance Bank</option>
                        <option value="PearlMicrofinanceBankLimited">Pearl Microfinance Bank Limited</option>
                        <option value="CovenantMirofinanceBankLtd">Covenant Mirofinance Bank Ltd</option>
                        <option value="SparkleBank">Sparkle Bank</option>
                        <option value="KudaBank">Kuda Bank</option>
                        <option value="RubiesBank">Rubies Bank</option>
                        <option value="VFDMFB">VFD MFB</option>
                        <option value="MintFinexMFB">Mint Finex MFB</option>
                        <option value="MkoboMFB">Mkobo MFB</option>
                        <option value="CoronationMerchantBank">Coronation Merchant Bank</option>
                        <option value="FBNQuestMerchantBank">FBNQuest Merchant Bank</option>
                        <option value="FSDHMerchantBank">FSDH Merchant Bank</option>
                        <option value="RandMerchantBank">Rand Merchant Bank</option>
                        <option value="NovaMerchantBank">Nova Merchant Bank</option>
                      </select>
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
                    	<input type="text" class="form-control" id="designation" name="designation" required>
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
                    	<input type="text" class="form-control" id="department" name="department" required>
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
                      <select class="form-control" name="taxstate" value="" id=taxtstate required="required"/>
                                  <option value="" selected>- Select -</option>
                        <option value="Abia">Abia</option>
                        <option value="Adamawa">Adamawa</option>
                        <option value="AkwaIbom">Akwa Ibom</option>
                        <option value="Anambra">Anambra</option>
                        <option value="Bauchi">Bauchi</option>
                        <option value="Bayelsa">Bayelsa</option>
                        <option value="Benue">Benue</option>
                        <option value="Borno">Borno</option>
                        <option value="CrossRiver">Cross River</option>
                        <option value="Delta">Delta</option>
                        <option value="Ebonyi">Ebonyi</option>
                        <option value="Edo">Edo</option>
                        <option value="Ekiti">Ekiti</option>
                        <option value="Enugu">Enugu</option>
                        <option value="Gombe">Gombe</option>
                        <option value="Imo">Imo</option>
                        <option value="Jigawa">Jigawa</option>
                        <option value="Kaduna">Kaduna</option>
                        <option value="Kano">Kano</option>
                        <option value="Katsina">Katsina</option>
                        <option value="Kebbi">Kebbi</option>
                        <option value="Kogi">Kogi</option>
                        <option value="Kwara">Kwara</option>
                        <option value="Lagos">Lagos</option>
                        <option value="Nasarawa">Nasarawa</option>
                        <option value="Niger">Niger</option>
                        <option value="Ogun">Ogun</option>
                        <option value="Ondo">Ondo</option>
                        <option value="Osun">Osun</option>
                        <option value="Oyo">Oyo</option>
                        <option value="Plateau">Plateau</option>
                        <option value="Rivers">Rivers</option>
                        <option value="Sokoto">Sokoto</option>
                        <option value="Taraba">Taraba</option>
                        <option value="Yobe">Yobe</option>
                        <option value="Zamfara">Zamfara</option>
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
            	
            	<button type="submit" class="btn btn-primary btn-flat" name="add_staff"><i class="fa fa-save"></i> Add Staff</button>
            	</form>
          	</div>
        </div>
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

