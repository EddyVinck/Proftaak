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
    <div class="section">
       
      <div class="row">
          <div class="col s12">
            <ul class="collapsible popout" data-collapsible="accordion">                
                <li>
                    <div class="card-panel teal">
                        <div class="row valign-wrapper " style="margin-bottom: 0">
                            <div class="col s2 truncate">Projectnaam</div>
                            <div class="col s2">Projectstarter</div>
                            <div class="col s3">Opleiding</div>
                            <div class="col s2">Status</div>    
                            <div class="col s1"></div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="collapsible-header">
                        <div class="row valign-wrapper" style="margin-bottom: 0">
                            <div class="col s2 truncate">Bacon maken</div>
                            <div class="col s2 truncate">Jackie Chan</div>
                            <div class="col s3 truncate">Particuliere Beveiliging</div>
                            <div class="col s2 truncate">Klaar!</div>    
                            <div class="col s1 truncate"><a href="#!" class="secondary-content"><i class="material-icons">send</i></a></div>                        
                        </div>
                    </div>
                    <div class="collapsible-body">
                        <div class="row valign-wrapper">
                            <div class="col s4 center">
                                <img class="img-responsive" width="80%"  src="https://baconmockup.com/248/165/">
                            </div>
                            <div class="col s8">
                                <span>
                                    Spicy jalapeno bacon ipsum dolor amet turkey bresaola swine ham turducken cupim. 
                                    Ribeye kielbasa leberkas, biltong tri-tip rump jowl jerky. Flank sausage cow 
                                    picanha doner, cupim frankfurter kielbasa t-bone. Corned beef frankfurter boudin 
                                    burgdoggen cupim leberkas. Hamburger pig shankle sausage, pancetta salami turkey 
                                    drumstick. Chicken short ribs cupim, pig tail alcatra meatball pork loin ham t-bone 
                                    doner shankle sausage landjaeger biltong. Short ribs tail beef ribs picanha kielbasa 
                                    pastrami.
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <!--<div class="col s4">Ga naar dit project</div>-->
                            <div class="col s5">
                            <a class="waves-effect waves-light btn-flat"><i class="material-icons right">send</i>Ga naar dit project</a>
                            </div>
                            <!--<div class="col s6">
                                <button class="btn waves-effect waves-light" type="submit" name="action">Ga naar dit project
                                    <i class="material-icons right">send</i>
                                </button>
                            </div>-->
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header">
                        <div class="row valign-wrapper" style="margin-bottom: 0">
                            <div class="col s2 truncate">Een lange projectnaam</div>
                            <div class="col s2 truncate">John Doe</div>
                            <div class="col s3 truncate">Juridisch-administratieve beroepen</div>
                            <div class="col s2 truncate">Net gestart</div>    
                            <div class="col s1 truncate"><a href="#!" class="secondary-content"><i class="material-icons">send</i></a></div>                        
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
                <!--<li>
                    <div class="collapsible-header"><i class="material-icons">whatshot</i>Project Naam</div>
                    <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                </li>-->
            </ul>
          </div>
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