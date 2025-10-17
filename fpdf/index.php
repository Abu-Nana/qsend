<?php session_start();
require('fpdf.php');

class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
    
    $this->Image('../nounlogo1.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',28);
    // Move to the right
    $this->Cell(80);
    // Title
    include '../connection.php';
    $this->Cell(100,10,'NATIONAL OPEN UNIVERSITY OF NIGERIA',0,0,'C');//30
    $this->Ln();
    
    	$this->SetFont('Arial','',10);
		$len=300;
		$this->Cell($len,5,'University Village,Nnamdi Azikwe Expressway, Plot 91,Candastral Zone,Jabi,Abuja',0,0,'C');
    // $this->SetFont('Arial','',16);
     $this->Ln();
     $this->SetTextColor(128);
    $this->Cell(199,9,'RESULT SHEET'.'( '.date('d/M/Y h:i A').' )',0,0,'R');
    // Line break
    
    $this->Ln(20);
     
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    $this->Cell(0,10,'powered by NOUMIS',0,0,'R');
}
function LoadData($file)
{
    // Read file lines
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}

// Simple table
function BasicTable($header, $data)
{
    // Header
    foreach($header as $col)
    $this->Cell(90,10,'                            ',0,0,'L');
        $this->Cell(70,6,$col,1);
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        foreach($row as $col)
        $this->Cell(90,10,'                            ',0,0,'L');
            $this->Cell(70,6,$col,1);
        $this->Ln();
    }
}

// Better table
function ImprovedTable($header, $data)
{
    // Column widths
    $w = array(40, 35, 40, 45);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR');
        $this->Cell($w[1],6,$row[1],'LR');
        $this->Cell($w[2],6,$row[2],'LR',0,'C');
        $this->Cell($w[3],6,$row[3],'LR',0,'R');
        $this->Ln();
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}

// Colored table
function FancyTable($header, $data)
{
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    //$w = array(30,40,100, 15, 15, 60);
    $w = array(30,40,100, 15,  60);
    for($i=0;$i<count($header);$i++)
        //$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $this->Cell($w[$i],6,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = false;
   /* foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);
        $this->Cell($w[3],6,$row[3],'LR',0,'R',$fill);
        $this->Cell($w[4],6,$row[4],'LR',0,'L',$fill);
      //  $this->Cell($w[5],6,$row[5],'LR',0,'L',$fill);
        $this->Ln();
        $fill = !$fill;
    }*/
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}
}
function remark($str) 
	{
		switch(trim($str))
		{
			case "A": return "Excellent";
			case "B": return "Very Good";
			case "C": return "Good";
			case "D": return "Fair";
			case "E": return "Pass";
			case "F": return "Fail";
			default: return;
		}		
		 
	}
	function grade($str) 
	{
		switch(trim($str))
		{
			case "A": return "5";
			case "B": return "4";
			case "C": return "3";
			case "D": return "2";
			case "E": return "1";
			case "F": return "0";
			default: return;
		}		
		 
	}
	
$pdf = new PDF('L','mm','A4');
// Column headings
include '../connection.php';
$pdf->SetFont('Arial','',14);

//$pdf->BasicTable($header,$data);
$pdf->AliasNbPages();

//$pdf->Ln(20);
$matno=$_SESSION['matno'];//='nou134219535';
$sql='SELECT * FROM mytable where matno = "'.$matno.'"  ';
//$params=array($matno );
$rsql=mysqli_query($cnx,$sql) or die('unable to query3');	
$tab=mysqli_fetch_array($rsql);
$pdf->AddPage();
$pdf->SetFont('Arial','',16);
$pdf->Cell(90,10,'Matric. No: '.$tab['matno'],0,0,'L');
$pdf->Ln(10);
$pdf->Cell(90,10,'Name : '.$tab['name'].' '.$tab['name'],0,0,'L');
$pdf->Ln(10);


	
	$pdf->SetFont('Times','',10);
	$pdf->FancyTable($header,$data);
	$pdf->Ln(20);
	/*$pdf->SetFont('Arial','',8);
	$arr1=array();
	$header1 = array('SUMMARY');$str1='';
	$str1='Total Credit Carried : '.$sum_carried;
	$arr1[]=explode(';',$str1);
	$str1='Total Credit Earned : '.$sum_earned;
	$arr1[]=explode(';',$str1);
	$str1='Total Grade Point Earned : '.$tgp;
	$arr1[]=explode(';',$str1);
	$str1='Cummulative Grade Point Average : '.@round($tgp/$sum_carried,2);
	$arr1[]=explode(';',$str1);
	$data1 = $arr1;
	
	$pdf->BasicTable($header1,$data1);
	*/	
	
	
	



// Data loading


//put in the tables here
//for($i=1;$i<=40;$i++)
  //  $pdf->Cell(0,10,'Printing line number '.$i,0,1);
$pdf->Output();
?>