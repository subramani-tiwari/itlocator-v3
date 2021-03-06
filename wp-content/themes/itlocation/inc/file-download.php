<?php

if (isset($_REQUEST['file_id'])) {
    include("../../../../wp-config.php");
    $upload_dir = wp_upload_dir();
    $destination_path = $upload_dir["basedir"] . "/comp_files/";

    $file_model = new compFileMgnModelItlocation();
    $data_obj = $file_model->get_by_id($_REQUEST['file_id']);
    $file = $destination_path . $data_obj->comp_id . '/' . stripslashes($data_obj->real_filename);
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . stripslashes($data_obj->filename));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
}
?>
