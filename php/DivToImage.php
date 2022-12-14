<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <a href="http://localhost/magazin/wp-json/newsletter-api/tag=babypflege&index=6&return=post-url">
                        <img style="width: 100%;" src="http://localhost/magazin/wp-json/newsletter-api/tag=babypflege&index=6&return=post-image"/>
                        <br/>
                        <img style="width: 100%;" src="http://localhost/magazin/wp-json/newsletter-api/tag=babypflege&index=6&return=post-title"/>
                    </a>
                </div>
            </div>
        </div>

        <script>
            $(document).ready( function() {
                
                html2canvas(document.body).then(function(canvas) {
                // Export the canvas to its data URI representation
                var base64image = canvas.toDataURL("image/png");

                    // Open the image in a new window
                    console.log(base64image);
                    var myWindow = window.open("", "Image");
                    myWindow.document.write("<img src='"+base64image+"''>");
                    myWindow.print();
                });
            });
            
        </script>
    </body>
</html>