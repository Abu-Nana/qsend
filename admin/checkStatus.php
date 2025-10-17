<?php
/**
 * Check Login Status
 * Returns 1 if user is logged in, 0 otherwise
 */
session_start();

if (isset($_SESSION['admin']) && !empty($_SESSION['admin'])) {
    echo "1";
} else {
    echo "0";
}

