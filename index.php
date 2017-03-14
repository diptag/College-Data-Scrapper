<?php
    //check if url is not empty
    if (!isset($_POST["url"]))
    {
        exit();    
    }

    //get the raw html from shiksha.com using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $_POST["url"]);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:32.0) Gecko/20100101 Firefox/32.0");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ret = curl_exec($ch);
    curl_close($ch);
    
    // check if html page is recieved 
    if ($ret === false)
    {
        exit();
    }

    // scrape data from the raw html
?>
