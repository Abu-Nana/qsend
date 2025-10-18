<?php
/**
 * Simple test script to test the database query
 */
session_start();
require_once __DIR__ . '/../config/database.php';

// Set session for testing
$_SESSION['admin'] = 'test001';

$conn = Database::getPDO();

try {
    echo "<h2>Testing Database Query</h2>";
    
    // Test the exact query from qsend_ajax_original.php
    $exam_session = "8:30am";
    $day = "Day 1";
    
    $script = "SELECT s.id, s.matric_number, s.study_center, s.study_center_code, s.course, s.exam_day, s.exam_session, s.exa_date, c.study_center_code, c.study_centre_email, c.phone_number, c.director 
                FROM student_registrations s
                INNER JOIN study_centers c 
                ON c.study_center_code = s.study_center_code 
                WHERE s.exam_session = ? 
                AND s.exam_day = ?
                GROUP BY c.study_center_code, s.course";
    
    echo "<h3>Query:</h3>";
    echo "<pre>$script</pre>";
    
    echo "<h3>Parameters:</h3>";
    echo "<pre>exam_session: '$exam_session'<br>day: '$day'</pre>";
    
    $stmt = $conn->prepare($script);
    
    if (!$stmt) {
        echo "<p style='color:red'>❌ Statement preparation failed</p>";
        exit;
    }
    
    echo "<p style='color:green'>✅ Statement prepared successfully</p>";
    
    $result = $stmt->execute([$exam_session, $day]);
    
    if (!$result) {
        echo "<p style='color:red'>❌ Query execution failed</p>";
        exit;
    }
    
    echo "<p style='color:green'>✅ Query executed successfully</p>";
    
    $query_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Results:</h3>";
    echo "<p>Found " . count($query_data) . " rows</p>";
    
    if (count($query_data) > 0) {
        echo "<pre>";
        print_r($query_data);
        echo "</pre>";
    } else {
        echo "<p style='color:orange'>⚠️ No data found for these parameters</p>";
        
        // Let's check what data actually exists
        echo "<h3>Sample data from student_registrations:</h3>";
        $stmt2 = $conn->query("SELECT exam_session, exam_day, course FROM student_registrations LIMIT 10");
        $sample_data = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($sample_data);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "<h3 style='color:red'>Error:</h3>";
    echo "<p style='color:red'>" . $e->getMessage() . "</p>";
    echo "<pre style='color:red'>" . $e->getTraceAsString() . "</pre>";
}
?>
