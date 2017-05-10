<?php
include("inc/connectToDB.php");
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
        <div class="col s4 offset-s4 center-align ">
          <div class="card" id="test">
            <div class="card-content ">
              <span class="card-title">Log in als:</span>
               <div class="divider"></div>
              <p>
                <ul>
                  <li><a class="waves-effect waves-light btn" 
                  onclick="loginFade(0);Materialize.fadeInImage('#login_as_school',750);">button</a></li>
                  <li><a class="waves-effect waves-light btn" 
                  onclick="loginFade(1);Materialize.fadeInImage('#login_as_leraar',750);">button</a></li>
                  <li><a class="waves-effect waves-light btn" 
                  onclick="loginFade(2);Materialize.fadeInImage('#login_as_student',750);">button</a></li>
                </ul>
              </p>
            </div>
          </div>
        </div>
        <div class="col s4 offset-s4 center-align ">
          <div class="card hide" id="login_as_school">
            <div class="card-content ">
              <span class="card-title">Log in als:</span>
               <div class="divider"></div>
              <p>
                dit is om in te loggen als school
              </p>
            </div>
          </div>
        </div>
        <div class="col s4 offset-s4 center-align ">
          <div class="card hide" id="login_as_leraar">
            <div class="card-content ">
              <span class="card-title">Log in als:</span>
               <div class="divider"></div>
              <p>
                dit is om in te loggen leraar
              </p>
            </div>
          </div>
        </div>
        <div class="col s4 offset-s4 center-align ">
          <div class="card hide" id="login_as_student">
            <div class="card-content ">
              <span class="card-title">Log in als:</span>
               <div class="divider"></div>
              <p>
                dit is om in te loggen student
              </p>
            </div>
          </div>
        </div>
      </div>
  </div>
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="js/main.js"></script>

	<script type="text/javascript" src="js/materialize.js"></script>
</body>
</html>