/*global $*/

// execute the code if the page is fully loaded
$(function () {
  
  // execute the following code when form is submitted
  $("#form").submit(function () {

    // validate url entered by the user
    var pgurl = $("#urltext").val();
    var result = (/(http:\/\/www\.shiksha\.com\/b-tech\/colleges\/b-tech-colleges-[^\d]*[^\d\-])(?=-\d.*)?/).exec(pgurl);
    
    // display error if user entered invalid shiksha.com url
    if (result === null) 
    {
      $("#warning").css("display", "block");
      return false;
    } 
    else 
    {
      // display loading gif
      $("#progress").css("visibility", "visible");
      
      // declare status, lastpage and nextpage variables
      var lastpage = false;
      var loadstatus = true;
      var nextpage = result[1];
      
      // use while loop to send multiple ajax requests to scrape.php till last page is not reached 
      while ((lastpage === false) && (loadstatus === true))
      {
        $.ajax({
          url: "scrape.php",
          data: {
            pageurl: nextpage
          },
          async: false,
          success: function (data) {
            if (data.status === false)
            {
              // upadate loadstatus
              loadstatus = false;
            }
            else
            {
              // upadate lastpage and nextpage
              if (data.lastpage === false)
              {
                lastpage = false;
                nextpage = data.nextpage;
              }
              else
              {
                lastpage = true;
              }
            }
          }
        });
      }
      return true;
    }
  });
});