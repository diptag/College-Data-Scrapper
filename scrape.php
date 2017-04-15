<?php
    //enable session
    session_start();
    
    // Send content type header
    header ("Content-type: application/json");
    
    //check if url is not empty, else return not loaded
    if (empty($_GET["pageurl"]) && empty($_GET["scrapeid"]))
    {
        print(json_encode(array("status" => false), JSON_PRETTY_PRINT));
        exit();    
    }
    
    //get the raw html from shiksha.com using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $_GET["pageurl"]);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:32.0) Gecko/20100101 Firefox/32.0");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ret = curl_exec($ch);
    curl_close($ch);
    
    // check if html page is recieved else return not loaded
    if ($ret === false)
    {
        print(json_encode(array("status" => false), JSON_PRETTY_PRINT));
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
        $colleges[$m] = $matches_1[1];
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
    
    /*
    * save scraped data in database
    */
    
    // connect to database
    $dbh = new PDO("mysql:host=127.0.0.1;dbname=project_1", "dadiptanshu", "v1kCjsvLYytrBTGV");
    
    // get scrape id from databse if scrape id given through GET request is 0 for currnet scrape
    if ($_GET["scrapeid"] == 0)
    {
        $lastscrape = $dbh->query("SELECT MAX(scrape_id) AS lastscrape FROM colleges");
        $temp = $lastscrape->fetch(PDO::FETCH_ASSOC);
        if ($temp["lastscrape"] === NULL)
            $nextscrape = 1;
        else
            $nextscrape = $temp["lastscrape"] + 1;
    }
    else 
    {
        $nextscrape = $_GET["scrapeid"];
    }
    
    // update session variable
    $_SESSION["scrape_id"] = $nextscrape;
    
    // prepare insert statement for inserting data into table college
    $stmt = $dbh->prepare("INSERT INTO colleges (scrape_id, college, location, reviews) VALUES (".$nextscrape.", :college, :location, :reviews)");
    
    // insert college name, location and reviews of each collegein database
    $m = 0;
    foreach ($colleges as $clg)
    {
        $stmt->bindParam(":college", $clg);
        $stmt->bindParam(":location", $location[$m]);
        $stmt->bindParam(":reviews", $reviews[$m]);
        $stmt->execute();
        $m++;
    }
    
    // get entry id of first college enetered into the database
    $entry = $dbh->query("SELECT MAX(entry_id) AS entry FROM colleges WHERE college = '".$colleges[0]."'");
    $tmp = $entry->fetch(PDO::FETCH_ASSOC);
    $nextid = $tmp["entry"];
    
    // prepare insert statement for inserting data into table facilities
    $stmt = $dbh->prepare("INSERT INTO facilities (entry_id, facility) VALUES (:id, :facility)");
    
    // insert facilities into table facilities
    $m = 0;
    foreach ($facilities as $facility)
    {
        foreach ($facility as $fty)
        {
            $id = $nextid + $m;
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":facility", $fty);
            $stmt->execute();
        }
        $m++;
    }
    
    // close connection
    $dbh = null;
    
    // Check if it is the last page and send json data
    if (preg_match("/next linkpagination\"><a data-page=\"\d\" href = (.*)><i/", $ret, $next))
    {
        print(json_encode(array("status" => true, "lastpage" => false, "nextpage" => $next[1], "scrapeid" => $nextscrape), JSON_PRETTY_PRINT));
    }
    else
    {
        // extract city name to display in heading on result page
        preg_match("/b-tech-colleges-([^\d]*[^\d\-])(?=-\d.*)?/", $ret, $city);
        $_SESSION["city"] = $city[1];
        print(json_encode(array("status" => true, "lastpage" => true), JSON_PRETTY_PRINT));
    }
?>
