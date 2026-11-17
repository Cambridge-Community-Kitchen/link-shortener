<?php
    // get request, e.g. "/allthedocs
    $request = $_SERVER['REQUEST_URI'];
    error_log($request);
    $link_from_request = "";

    $str = file_get_contents('links.json');
    $links = json_decode($str, true);

    $matching_link = $links[$link_from_request];

    if ($matching_link) {
        // redirect to matching_link
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CCK Link shortener</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>CCK Links directory</h1>

    <?php 
        if (!$matching_link) {
            echo "<div>Your link $matching_link</div>";
        }
    ?>

    <h2>Cleaning</h2>
    <div class="cleaning section">
    </div>

    <h2>Signal Chats</h2>
    <div class="signal section">
        <div>/cleaning <span class="text">Cleaning</span></div>
        /transportrequests 
    </div>
</body>

</html>