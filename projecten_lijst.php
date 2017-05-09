<?php
?>
<!DOCTYPE html>
<head>
	<head>
      <!--Import Google Icon Font-->
      <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
</head>
<body>
  <div class="container">
      <div class="row">
          <div class="col s12">
            <ul class="collapsible popout" data-collapsible="accordion">
                <li>                    
                    <div class="collapsible-header" height="50">
                        <i class="material-icons"><img class="" width="40" style="margin-right: 20px" src="https://baconmockup.com/300/200/"></i>
                        Bacon bakken
                    </div>
                    <div class="collapsible-body">
                        <span>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                            Reprehenderit ab exercitationem delectus provident perferendis vero illum ut fugit, ullam
                            eum rerum libero est quia perspiciatis quam quis nam explicabo cumque.
                        </span>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header">
                        <!--<i class="material-icons"><img class="" width="40" padding="0 30" src="https://baconmockup.com/300/200/"></i>
                        Bacon Eten-->
                        <div class="row valign-wrapper" style="margin-bottom: 0">
                            <!--<div class="col s1"></div>-->
                            <!--<div class="col s2 valign-wrapper">
                                <img class="responsive-img valign" width="50" src="https://baconmockup.com/300/200/">
                            </div>-->
                            <div class="col s2 truncate">Projectnaamssssssssssssssssssssssssssssssssssssssss</div>
                            <div class="col s2">Projectstarter</div>
                            <div class="col s2">Opleiding</div>
                            <div class="col s2">Bezig</div>    
                            <div class="col s1"><a href="#!" class="secondary-content"><i class="material-icons">send</i></a></div>            
                            
                        </div>
                    </div>
                    <div class="collapsible-body">
                        <span>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                            Reprehenderit ab exercitationem delectus provident perferendis vero illum ut fugit, ullam
                            eum rerum libero est quia perspiciatis quam quis nam explicabo cumque.
                        </span>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header"><i class="material-icons">whatshot</i>Project Naam</div>
                    <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                </li>
            </ul>
          </div>
      </div>
  </div>
  <div style="width:100vw;height:200vh;"></div>
  <a href="#" data-activates="slide-out" class="button-collapse show-on-large"><i class="material-icons">menu</i></a>
  <script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="js/materialize.min.js"></script>
</body>
<script>
  $('.button-collapse').sideNav({
      menuWidth: 300, // Default is 300
      edge: 'left', // Choose the horizontal origin
      closeOnClick: true, // Closes side-nav on <a> clicks, useful for Angular/Meteor
      draggable: true // Choose whether you can drag to open on touch screens
    }
  );
  $(document).ready(function(){
    $('.collapsible').collapsible();
  });
</script>
</html>