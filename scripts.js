/*global $*/
$(function () {
  $("#form").submit(function () {
    $("#error").css("visibility", "hidden");
    $("#warning").css("display", "none");
    var pgurl = $("#urltext").val();
    var result = (/(http[^\d]*[^\d\-])(?=-\d.*)?/).exec(pgurl);
    if (result === null) {
      $("#warning").css("display", "block");
      return false;
    } else {
      $("#progress").css("visibility", "visible");
      var lastpage = false;
      var loadstatus = true;
      $.ajax({
        url: "scrape.php",
        data: {
          pageurl: result[1]
        },
        async: false,
        success: function (data) {
          if (data.status === false)
          {
            $("#msg").text("Not Done");
          }
          else
          {
            
          }
        }
      });
      setTimeout(function () { alert("done");}, 3000);
  }
  });
});