<?php
/*
 * Secure file download / view.
 * Uploaded files live in a folder that cannot be opened directly from the browser,
 * so every request has to pass through here:
 *   - admin    -> can access every submitted file
 *   - employee -> can access only the files he/she uploaded
 */
session_start();

if (!isset($_SESSION['role']) || !isset($_SESSION['id'])) {
	header("Location: login.php?error=" . urlencode("First login"));
	exit();
}

include "DB_connection.php";
include "app/Model/Submission.php";

$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$file = get_submission_by_id($conn, $id);

if ($file == 0) {
	http_response_code(404);
	exit("File not found.");
}

if ($_SESSION['role'] !== 'admin') {
	if ($_SESSION['role'] !== 'employee' || (int)$file['uploaded_by'] !== (int)$_SESSION['id']) {
		http_response_code(403);
		exit("Access denied.");
	}
}

$path = SUBMISSION_UPLOAD_DIR . basename($file['stored_name']);
if (!is_file($path)) {
	http_response_code(404);
	exit("The file is no longer on the server.");
}

$types = submission_allowed_types();
$ext   = strtolower($file['file_ext']);
$mime  = isset($types[$ext]) ? $types[$ext] : 'application/octet-stream';

// "View" opens pdf/images in the browser; everything else is always downloaded
$inline = isset($_GET['view']) && in_array($ext, submission_viewable_exts(), true);
$disposition = $inline ? 'inline' : 'attachment';

// safe file name for the Content-Disposition header (ASCII fallback + UTF-8 version)
$ascii = preg_replace('/[^A-Za-z0-9._-]+/', '_', $file['original_name']);
if ($ascii === '' || $ascii === '_') $ascii = 'file.' . $ext;

session_write_close();          // don't block other requests while streaming
while (ob_get_level()) ob_end_clean();

header('Content-Type: ' . $mime);
header('Content-Disposition: ' . $disposition . '; filename="' . $ascii . '"; filename*=UTF-8\'\'' . rawurlencode($file['original_name']));
header('Content-Length: ' . filesize($path));
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store');
readfile($path);
exit();
