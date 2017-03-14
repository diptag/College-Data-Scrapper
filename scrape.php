<?php
    //check if url is not empty
    /*if (!isset($_POST["url"]))
    {
        exit();    
    }
    */
    //get the raw html from shiksha.com using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://www.shiksha.com/b-tech/colleges/b-tech-colleges-bangalore");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:32.0) Gecko/20100101 Firefox/32.0");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ret = curl_exec($ch);
    curl_close($ch);
    
    // check if html page is recieved 
    if ($ret === false)
    {
        exit();
    }

    /*
     *  scrape data from the raw html
     */
    // scrape college names and location
    preg_match_all("/<h2 class=\"tuple-clg-heading\"><a.*_blank\">(.*)<\/a>\n<p>\| (.*)<\/p><\/h2>/", $ret, $matches);
    $clg = $matches[1];
    $location = $matches[2];

    $n = 0;
    foreach ($clg as $col)
    {
        print($col.", ".$location[$n]."\n");
        $n += 1;
    }
?>
