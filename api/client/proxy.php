<?php
if (isset($_GET['url'])) {
    $url = $_GET['url'];

    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);

        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        if ($response) {
            header("Content-Type: $contentType");
            echo $response;
        } else {
            header("HTTP/1.1 404 Not Found");
            echo "Image not found";
        }
    } else {
        header("HTTP/1.1 400 Bad Request");
        echo "Invalid URL";
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    echo "No URL provided";
}

