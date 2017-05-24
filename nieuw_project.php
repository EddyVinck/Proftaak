<?php
include("inc/functions.php");
?>
<!DOCTYPE html>
<head>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="css/footer.css"  media="screen,projection"/>
    <link rel="stylesheet" href="font-awesome-4.7.0\css\font-awesome.min.css">
    <link rel="stylesheet" rel="stylesheet" href="css/imgur.css" media="screen,projection"/>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        .jumbotron h1, .jumbotron p {
            padding-left: 60px;
            padding-right: 60px;
        }

        .col-md {
            margin: 0 auto;
            max-width: 500px
        }
    </style>
</head>
<body >
  <?php createHeader();?>
  <main>
    <div class="jumbotron">
        <h1>imgur Upload API</h1>
        <p>Upload images to imgur via JavaScript</p>
    </div>

    <div class="col-md">
        <div class="dropzone"></div>
    </div>
    <script type="text/javascript" src="js/upload.js"></script>
    <script type="text/javascript">
        var feedback = function (res) {
            if (res.success === true) {
                document.querySelector('.status').classList.add('bg-success');
                document.querySelector('.status').innerHTML = 'Image url: ' + res.data.link;
            }
        };

        new Imgur({
            clientid: 'cc86a8de0e7c459',
            callback: feedback
        });
    </script>
  </main>
  <?php createFooter();?>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/ajaxfunctions.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
</body>
<script>
  initSideNav();
</script>
</html>