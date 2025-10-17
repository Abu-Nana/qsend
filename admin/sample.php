function sendPassword($phone_number,$password,$study_center){
	$message_data = "Dear ". $study_center. "Director,\n
						Please here is your password  ". $password;
	try{
	$curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://mps.digitalpulseapi.net/1.0/send-sms/anq',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS =>'{
		"sender": "NOUN",
		"message": "'.$message_data.'",
		"receiver": "'.$phone_number.'"}',
	CURLOPT_HTTPHEADER => array(
		'api-key: N1Y8NIuMPhV5kDwCQgBxEA==',
		'Content-Type: application/json'
	),
	));
	$response = curl_exec($curl);
	curl_close($curl);
	}catch(Exception $e){
	}
}
function securityCode($dir,$object,$date,$connection){
	$code = ":".rand(1,100000000);
	$security_code = $date." ".$code;
	// Create new Landscape PDF
	$pdf = new FPDI('l');

	// Reference the PDF you want to use (use relative path)
	$pagecount = $pdf->setSourceFile( $dir );

	// Import the first page from the PDF and add to dynamic PDF
	$tpl = $pdf->importPage(1);
	$pdf->AddPage();

	// Use the imported page as the template
	$pdf->useTemplate($tpl);

	// Set the default font to use
	$pdf->SetFont('Helvetica');
	$pdf->SetTextColor(255,255,255);//Set to Black color

	// adding a Cell using:
	// $pdf->Cell( $width, $height, $text, $border, $fill, $align);

	// First box - the user's Name
	$pdf->SetFontSize('.8'); // set font size
	$pdf->SetXY(0, 0); // set the position of the box
	$pdf->Cell(0, 3, $security_code, 0, 0, 'L'); // add the text, align to Center of cell
	// render PDF to browser
	$pdf->Output('F',$dir);
	storeSecurityCode($code,$object['id'],$connection); // store to DB
}
function storeSecurityCode($code ,$student_registration_id,$connection){
	$store = "INSERT INTO security_codes (security_code,student_registration_id) values(
				'$code' ,$student_registration_id)";
	mysqli_query($connection,$store)  or die(mysqli_error($connection));
}