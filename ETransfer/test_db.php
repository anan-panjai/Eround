<?php
require 'DB_transfer.php';
try {
    $db = getDB();
    echo "Connected successfully";
} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}
