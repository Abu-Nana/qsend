<?php
// Quick script to check import results
require_once __DIR__ . '/../config/database.php';

echo "<h2>Student Registration Records</h2>";

$result = $conn->query("SELECT COUNT(*) as total FROM student_registrations");
$count = $result->fetch_assoc()['total'];

echo "<p><strong>Total Records: $count</strong></p>";

if($count > 0) {
    echo "<h3>Recent Records:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr>
            <th>Matric Number</th>
            <th>Study Center</th>
            <th>Center Code</th>
            <th>Course</th>
            <th>Exam Day</th>
            <th>Exam Session</th>
            <th>Exam Date</th>
          </tr>";
    
    $records = $conn->query("SELECT * FROM student_registrations ORDER BY id DESC LIMIT 10");
    while($row = $records->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['matric_number']}</td>";
        echo "<td>{$row['study_center']}</td>";
        echo "<td>{$row['study_center_code']}</td>";
        echo "<td>{$row['course']}</td>";
        echo "<td>{$row['exam_day']}</td>";
        echo "<td>{$row['exam_session']}</td>";
        echo "<td>{$row['exa_date']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>No records found. Import may have failed.</p>";
}

echo "<hr>";
echo "<p><a href='rawslip.php'>Back to Upload Page</a></p>";
?>

