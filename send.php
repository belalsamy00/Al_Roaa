<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
</head>
<body>
    
    <script>
        function send() {
            $.get("https://alroaaacademy.site/Test", function(Data) {
            
        }).done(function(Data) {
          var Data = JSON.parse(Data)
          let content = Data
          console.log(content)
        }).fail(function(Data, textStatus, xhr) {
            console.log("error", Data.status);
            console.log("STATUS: "+xhr);
        });
        }
        setInterval(send, 600000);
        send();
    </script>
</body>
</html>