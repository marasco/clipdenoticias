<?php
ini_set("display_errors",true);
    // Test image.
    $fn = 'http://upload.wikimedia.org/wikipedia/commons/d/d9/Test.png';

    // Getting headers sent by the client.
    //$headers = apache_request_headers(); 

    // Checking if the client is validating his cache and if it is current.
    //if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == filemtime($fn))) {
        // Client's cache IS current, so we just respond '304 Not Modified'.
    //    header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 304);
    //} else {
        // Image not cached or cache outdated, we respond '200 OK' and output the image.
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 200);
        header('Content-Length: '.filesize($fn));
        header('Content-Type: image/png');
        print file_get_contents($fn);
    //}

?>

