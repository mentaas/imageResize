<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resized Image</title>
</head>
<body>

</body>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

<script type="text/javascript">
    $(document).ready(function () {
       $.ajax({
          url:'/api/resizeImages/image/{{$imgUrl}}',
           type: 'GET',
           dataType: 'JSON',
           success: function (data) {
               console.log(data);
               showImage(data.image_content, data.image_type)
           },
           error: function (data) {
              console.log(data);
               showError();
          }
       });
    });

    function showImage(image_content, image_type) {
        var x = document.createElement("IMG");
        x.setAttribute("src", 'data:'+ image_type + ';base64,' + image_content);
        x.setAttribute("width", "auto");
        x.setAttribute("height", "auto");
        x.setAttribute("alt", "Resized Image");
        document.body.appendChild(x);
    }

    function showError(){
        var x = document.createElement("p");
        x.style.cssText = "color: blcak; font-size: 20px;";
        x.innerText = "Error 404 :(";
        document.body.appendChild(x);
    }
</script>
</html>