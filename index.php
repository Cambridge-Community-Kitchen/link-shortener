<?php
    // get request, e.g. "/allthedocs
    $request = $_SERVER['REQUEST_URI'];
    // error_log($request);
    $link_from_request = "";

    $links_json = file_get_contents('links.json');
    $links_obj = json_decode($links_json, true);
    $all_links_flat = array_merge([], ...array_values($links_obj));

    $matching_link = null;

    if ($request != "/") {
        $matching_link = array_find($all_links_flat, function(array $link_ref) use($request) {
            return "/" . $link_ref["shorturl"] == $request;
        });
    
        if (gettype($matching_link) != "NULL") {
            $actual_link = $matching_link["link"];
            header("Location: $actual_link");
            die();
        }
    }

    function slugify($text, string $divider = '-')
    {
      // replace non letter or digits by divider
      $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

      // transliterate
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

      // remove unwanted characters
      $text = preg_replace('~[^-\w]+~', '', $text);

      // trim
      $text = trim($text, $divider);

      // remove duplicate divider
      $text = preg_replace('~-+~', $divider, $text);

      // lowercase
      $text = strtolower($text);

      if (empty($text)) {
        return 'n-a';
      }

      return $text;
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
        if ($matching_link == null && $request != "/") {
            echo "<div>\"$request\" doesn't match any known links, search the directory below for the desired shortened url</div>";
        }
    ?> 


    <?php foreach($links_obj as $section=>$section_link_objs): ?>
        <h2><?= $section ?></h2>
        <div class="section <?= slugify($section) ?>">
            <?php foreach($section_link_objs as $key=>$link_obj): ?>
                <?php if(($link_obj["hide_from_index_page"] ?? false) != true): ?>
                    <div><a class="shorturl" href="<?=$link_obj["link"]?>">/<?= $link_obj["shorturl"] ?></a> <span><?= $link_obj["name"]?></span></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</body>

</html>