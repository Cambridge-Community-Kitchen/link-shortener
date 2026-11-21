<?php

    // 1. get csv
    // 2. replace links.csv
    // 3. if /backups/links.csv is older than 24 hours, replace that file
    $allthedocs_readonly_sheet_csv_url = "https://docs.google.com/spreadsheets/d/e/2PACX-1vTHWNaPPmlZsXwsyFRxl8zlw9Hd0har2_EJ5OwK5D13epy_T1TJ2ZQr8zjoiFgWi2oJAQxwREBAMZ0t/pub?gid=1972701881&single=true&output=csv";

    $fp = fopen(__DIR__ . '/links.csv', 'wb');
    $ch = curl_init($allthedocs_readonly_sheet_csv_url);
    curl_setopt_array($ch, [CURLOPT_FILE => $fp, CURLOPT_FOLLOWLOCATION => true]);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    $backup_file = filemtime("backup_from_yesterday/links.csv");
    $file_age_seconds = time() - $last_modified;
    // 24 hours
    if ($file_age_seconds > 3600 * 24) { 
        copy("links.csv", "backup_from_yesterday/links.csv");
    }

?>