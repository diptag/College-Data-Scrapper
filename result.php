<?php
  // enable session
  session_start();
  
  // connect to database
  $dbh = new PDO("mysql:host=127.0.0.1;dbname=project_1", "dadiptanshu", "v1kCjsvLYytrBTGV");
  
  // extract college data from the college
  $result = $dbh->query("SELECT * FROM colleges WHERE scrape_id = ".$_SESSION["scrape_id"]);
  $colleges = $result->fetchAll(PDO::FETCH_ASSOC);
  
  // extract city from locaion of colleges following the given regular expression
  $n = 0;
  while (!(preg_match("/.*, (.*)/", $colleges[$n]["location"], $city)))
    $n++;
  
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
<title>Engineering College Finder</title>
</head>

<body>
<div id="wrapper">
  <header>
        	<h1>ENGINEERING COLLEGE FINDER</h1>
  </header>
  <div id="main">
    <center><h2>Engineering Colleges in "<?= $city[1] ?>"</h2></center>
    <p class="back"><a href="index.html">Go Back</a></p>
    <table>
    	<tr>
          <th>S. No.</th>
          <th>College/Institute</th>
          <th>Location</th>
          <th>Facilities</th>
          <th>Reviews</th>
    	</tr>
    	<?php
    	  // print college data
    	  $n = 1;
    	  foreach ($colleges as $college)
    	  {
    	 ?>
    	 <tr>
    	   <td><?= $n ?></td>
    	   <td><?= $college["college"] ?></td>
    	   <td><?= $college["location"] ?></td>
    	   <td>
    	     <ul>
    	     <?php
    	      // extract facilities for each college from table facilities
    	      $result = $dbh->query("SELECT * FROM facilities WHERE entry_id = ".$college["entry_id"]);
    	     
    	      // print facilities
    	      while ($facility = $result->fetch(PDO::FETCH_ASSOC))
    	      {
    	     ?>
    	       <li><?= $facility["facility"] ?></li>
    	     <?php
    	      }
    	     ?>
    	     </ul>
    	   </td>
    	   <td><?= $college["reviews"] ?></td>
    	</tr>
    	<?php
    	    $n++;
    	  }
    	?>
    </table>
    <p class="back"><a href="index.html">Go Back</a></p>
  </div>
  <footer>
   	<span id="footer-text">Created by Diptanshu Agarwal &copy;</span>
  </footer>
</div>
</body>
</html>