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
    //Scrape and seperate out html containig data for each college 
    preg_match_all("/<h2 class=\"tuple-clg-heading\">[\s\S]+?\n<section class=\"tuple-bottom\">/", $ret, $matches);
    
    //scrape data for each college from their respective scraped html
    $m = 0;     //index for arrays storing college information
    foreach ($matches[0] as $match)
    {
        // scrape college names and location
        preg_match("/<h2 class=\"tuple-clg-heading\"><a.*_blank\">(.*)<\/a>\n<p>\| (.*)<\/p><\/h2>/", $match, $matches_1);
        $clg[$m] = $matches_1[1];
        $location[$m] = $matches_1[2];
    
        // scrape part of html containing facilities for the college
        preg_match("/<ul class=\"facility-icons\">[\s\S]+<section class=\"tpl-curse-dtls\">/", $match, $matches_2);
    
        //scrape facilities form html obtained
        preg_match_all("/<h3>(.*)<\/h3>/", $matches_2[0], $matches_3);
        $facilities[$m] = $matches_3[1];
        
        //scrape reviews for the college
        if (preg_match("/<span><b>(.*)<\/b><a target=\"_blank\" type=\"reviews\"/", $match, $matches_4))
            $reviews[$m] = $matches_4[1];
        else
            $reviews[$m] = 0;
        
        //upadate index
        $m += 1;
    }

    $n = 0;
    print($m."\n");
    foreach ($clg as $col)
    {
        print($col.", ".$location[$n]."\n");
        foreach($facilities[$n] as $facility)
            print($facility.", ");
        print("\nReviews: ".$reviews[$n]."\n");
        $n += 1;
    }
?>
