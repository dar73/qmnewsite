<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common_front.php');
// Database connection details
$host = DB_HOST; // Database host
$username = DB_USERNAME; // Database username
$password = DB_PASSWORD; // Database password
$dbname = DB_NAME; // Name of the database to backup


// Filename for the dump file
$filename = $dbname . '_' . date('Y-m-d_H-i-s') . '.sql';

// Command to export the database
$command = "mysqldump --host=$host --user=$username --password=$password $dbname";

// Execute the command and capture the output
$output = null;
$returnVar = null;
exec($command, $output, $returnVar);

if ($returnVar !== 0) {
    // If an error occurred
    die('Error generating database dump. Check your credentials or command execution.');
}

// Convert the output array to a string
$dumpContent = implode(PHP_EOL, $output);

// Set headers for file download
header('Content-Type: application/sql');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($dumpContent));

// Output the content
echo $dumpContent;
exit;

?>
