<?php
// Check if the TCPDF class exists
require_once('tcpdf/tcpdf/tcpdf.php');

if (class_exists('TCPDF', false)) {
    echo "TCPDF library is installed.";
} else {
    echo "TCPDF library is not installed.";
}
