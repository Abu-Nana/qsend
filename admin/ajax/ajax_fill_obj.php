<?php
include('../inc/pdo_connectdb.php');
if($_POST['id'])
{
	$id=$_POST['id'];
	switch ($id) {
		case 'ADMIN':
			// code...
			// echo '<option value="DEAN">DEA</option>';
			$query="SELECT DISTINCT 'DEA' AS id,'Directorate of Exams and Assessment' AS desciption
						FROM faculty
		    	";
			break;
	case 'DEAN':
	case 'FAEO':
			$query="SELECT cfacultyid,vfacultyDesc
						FROM faculty
						ORDER BY vfacultyDesc";
							break;
	case 'FHOD':
	case 'FDEO':
	$query="SELECT cdepartmentid,departmentdesc
				FROM department
				ORDER BY Departmentdesc
    	";
				break;
	case 'HDSS':
	$query="SELECT DISTINCT 'HDDS' AS id,'ICT/Counsellor/Support Staff' AS desciption
				FROM faculty
			";
				break;
	case 'TECHO':
	$query="SELECT cstudycenterid,vcityname
				FROM studycenter
				ORDER BY vcityname
			";
				break;
	}
  //$cat =$_POST['cat'];
  // $query="SELECT cprogrammeid,vProgrammeDesc
	// 			FROM programme
	// 			WHERE cfacultyid ='$id'
  //   	";
    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute();
echo '<option value="">Select Option</option>';
  while ($rw = $stmt->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_NEXT))
	{
	$id=$rw[0];
	$data=$rw[1];
	echo '<option value="'.$id.'">'.$data.'</option>';
	}
}
?>
