<?php
    require "utility/slugify.php";

    // 1. check last-modified on links.csv
    // 2. if it's older than an hour, regenerate csv
    $links_filepath = "links.csv";
    $last_modified = filemtime($links_filepath);
    $file_age_seconds = time() - $last_modified;
    if ($file_age_seconds > 3600) { // one hour
        include "refetch_allthedocs.php";
    }

    $fp = fopen("links.csv", "rb");
    $headers = fgetcsv($fp, 0, ',', '"', "\\");
    $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);

    // group rows by column "Categories"
    $grouped = [];
    while (($row = fgetcsv($fp, 0, ',', '"', '\\')) !== false) {
        if ($row === [null]) continue;
        $assoc = array_combine($headers, $row);
        $grouped[$assoc['Category']][] = $assoc;
    }
    // $categories = array_keys($grouped);
    $categories = [
        "recipients",
        "rotas",
        "volunteers",
        "cleaning",
        "delivery",
        "delivery co-ord",
        "cooking",
        "cooking (TL)",
        "eat-in Tuesdays",
        "admin/general",
        "admin/recruitment",
        "stock/organisation",
        "coding"
    ];

    // make map for shorturls, to handle instant redirects
    $shorturl_to_fullurl = [];
    rewind($fp);
    fgetcsv($fp, 0, ',', '"', '\\'); // skip header row
    while (($row = fgetcsv($fp, 0, ',', '"', '\\')) !== false) {
        if ($row === [null]) continue;
        $assoc = array_combine($headers, $row);
        $shorturl_to_fullurl[$assoc['shorturl']] = $assoc['full link'];
    }
    fclose($fp);



    // get request, e.g. "/allthedocs
    $request = $_SERVER['REQUEST_URI'];
    $request_shorturl = substr($request, 1); // converts "/dotd" to "dotd"
    $shorturl_is_valid = array_key_exists($request_shorturl, $shorturl_to_fullurl);
    if ($shorturl_is_valid) {
        $full_url = $shorturl_to_fullurl[$request_shorturl];
        header("Location: $full_url");
        die();
    }

    // no redirect; now we build read-only index page
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
        if (!$shorturl_is_valid && $request != "/") {
            echo "<div>\"$request\" doesn't match any known links, search the directory below for the desired \"short url\", e.g. \"link.cckitchen.uk/eating-with-cck\"</div>";
        }
    ?> 


    <?php foreach($categories as $category): ?>
        <h2><?= $category ?></h2>
        <div class="section <?= slugify($category) ?>">
            <?php foreach($grouped[$category] as $row): ?>
                <?php if($row["Hidden from link.cckitchen.uk?"] == "" && $row["Active?"] != "No"): ?>
                    <!-- <div><a class="shorturl" href="<?=$row["full link"]?>">/<?= $row["shorturl"] ?></a> <span><?= $row["Name"]?></span></div> -->
                    <div class="row">
                        <a href="<?=$row["full link"]?>"><?= $row["Name"]?></a>
                        <?php if($row["Description"]): ?>
                            <p class="Description"><?= $row["Description"] ?></p>
                        <?php endif; ?>
                        <p class="shorturl" data-url="<?= $row["shorturl"] ?>">/<?= $row["shorturl"] ?> <span class="copy-icon">Copy<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-copy preview-icon"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg></span></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <script>
        function copy_shorturl(e) {
            debugger;
            const clicked_element = e.currentTarget;
            const shorturlParentElement = clicked_element.closest(".shorturl");
            const url = "link.cckitchen.uk/" + shorturlParentElement.dataset.url;
            navigator.clipboard.writeText(url);
            const oldHTML = clicked_element.innerHTML;
            clicked_element.innerHTML = "copied! ✅";
            setTimeout(() => {
                clicked_element.innerHTML = oldHTML;
            }, 3_000);
        }
        document.querySelectorAll(".shorturl .copy-icon").forEach(el => el.addEventListener("click", (e) => copy_shorturl(e)));
    </script>
</body>

</html>