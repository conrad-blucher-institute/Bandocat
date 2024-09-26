<?php
 $input = $_GET['file'];
 $name = pathinfo($input);
 $filename = $name['basename']; // Prevent directory traversal attacks

 // Optional: Define a root directory for security purposes (useful for restricting access to certain folders)
 $rootDir = realpath(__DIR__ . '/../'); // Adjust this path as necessary to limit access

 // Construct the full path dynamically
 $filepath = realpath($input);

 // Get the MIME type and file size
 $filesize = filesize($filepath);
 $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
 $mimetype = finfo_file($fileinfo, $filepath);
 finfo_close($fileinfo);

 // Set headers for file download
 header("Pragma: public");
 header("Expires: 0");
 header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
 header("Cache-Control: public");
 header("Content-Description: File Transfer");
 header("Content-Type: $mimetype");
 header("Content-Disposition: attachment; filename=\"$filename\"");
 header("Content-Transfer-Encoding: binary");
 header("Content-Length: $filesize");

 // Clear the output buffer if there's any content in it
 if (ob_get_length()) {
	 ob_clean();
 }
 flush(); // Ensure headers are sent immediately

 // Read and output the file
 readfile($filepath);
 exit;
?>
