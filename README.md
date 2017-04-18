project_1

This first page of the application asks for shiksha.com URL for engineering colleges detatils based on city and scrapes the college 
names, location, facilities and number of reviews of all colleges given on shiksha.com for the particular city. It scrapes the 
college data by sending multiple ajax requests to a php script which scrapes and store the data in database, each time sending URL 
for the page to scrapped and gets back the scrape status and and the URL of the next page to be scrapped. If the page scrapped is 
the last page for the given city, it sends a key-value pair 'lastpage => true' indicating that it was the last page. Then the page 
redirects the user to the results page. A common scrapeid is provided to all the data obtained from the scrapping of all the pages of
city to differentiate between data of same city scrapped again.

The files for the application are as follows: 
1)index.html - The home page of the application with a form which asks the user for the URL.
2)styles.css - The stylesheet for all the pages of the website.
3)jquery-min - jQuery file.
4)scripts.js - The file containing javascript code for the application. On clicking the submit button on the form it first validates 
    the URL and then obtains URL for the first page of the city from the given URL and starts sending ajax requests for scrapping 
    college data.
5)loading.gif - The Loading animation shown when data is being scrapped.
6)scrape.php - The PHP file which scrapes the college data from the page whose URL it receives through ajax requests.
7)result.php - The PHP file which displays results of the scrapping by getting data form the database for the particular scrape.
8)project_1.sql - File created using mysqldump.

How to run the project on IDE
1)First check the database credentials used in config.php and change them if required.
2)Start the server using 'apache50 start ~/workspace/project_1'.
3)Start the mysql server using 'mysql50 start'
4)If the project is run on different user's IDE, first use 'username50' and 'password50' to get username and password of the user.
    The in a new tab go to https://ide50-{username}.cs50.io/phpmyadmin and use the username and password. After opening phpmyadmin 
    go to SQL tab paste the commands in 'project_1.sql' in it and click Go. This will setup the database for the project.
5)Now, just go to https://ide50-{username}.cs50.io to visit the website.