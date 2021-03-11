<?php
$pdo = new PDO('oci:dbname=localhost/XE', 'samson', '1342');
if (!$pdo) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

 ?>
