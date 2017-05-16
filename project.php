<?php
?>
<!DOCTYPE html>
<head>
	<head>
      <!--Import Google Icon Font-->
      <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
      <link type="text/css" rel="stylesheet" href="css/footer.css"  media="screen,projection"/>
      <link rel="stylesheet" href="font-awesome-4.7.0\css\font-awesome.min.css">
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
</head>
<body >
<header>    
    <nav class="top-nav teal">
        <div class="nav-wrapper">
            <div class="container">
        <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
            <div class="col s12" style="padding: 0 .75rem;">                
                <a href="index.php" class="brand-logo">Logo</a>        
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="#" class=" waves-effect"><i class="small material-icons left">home</i>Mijn College</a></li>
                <li><a href="#" class=" waves-effect"><i class="small material-icons left">view_module</i>Colleges</a></li>
                <li><a href="#" class=" waves-effect"><i class="small material-icons left">message</i>Priveberichten</a></li>
                <li><a href="#" class=" waves-effect"><i class="small material-icons left">exit_to_app</i> Log uit </a></li>
            </ul>
            </div>       
            <!--<a href="#" class="brand-logo">Logo</a>-->
            </div>        
        </div>        
    </nav>    
</header>
<sidenav>
    <ul id="slide-out" class="side-nav">
        <li><div class="userView">
            <div class="background">
                <img src="images/office.jpg">
            </div>
                <a href="#!user"><img class="circle" src="images/yuna.jpg"></a>
                <a href="#!name"><span class="white-text name">John Doe</span></a>
                <a href="#!email"><span class="white-text email">jdandturk@gmail.com</span></a>
            </div>
        </li>
        <li><a href="#"><i class="small material-icons left">home</i>Mijn College</a></li>
        <li><a href="#"><i class="small material-icons left">view_module</i>Colleges</a></li>
        <li><a href="#"><i class="small material-icons left">message</i>Priveberichten</a></li>
        <li><a href="#"><i class="small material-icons left">exit_to_app</i> Log uit </a></li>
        <li><a href="#!">Second Link</a></li>
        <li><div class="divider"></div></li>
        <li><a class="subheader">Subheader</a></li>
        <li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
  </ul>
</sidenav>
<main>
            <!--Work in progress
    deze pagina is mobile-first ontworpen 
    en nog niet geschikt voor desktop gebruik-->
  <div class="container">
      <div class="section">
        <div class="row">
            <div class="col s12 m8 center-on-small center-on-small-only">
                <h3>Projectnaam</h3>
            </div>
            <div class="col s12 m4">                      
                <div class="col s12 center-on-small-only hide-on-med-and-up">
                    <i class="material-icons medium">cancel</i><h5>Gestaakt</h5>
                    <!--<i class="material-icons medium">build</i><h4>Bezig</h4>-->
                    <!--<i class="material-icons medium">check_circle</i><h4>Klaar!</h4>-->
                </div>
            </div>
        </div>
      </div>
      <div class="row">
          <div class="col s12">
            <div class="slider">
                <ul class="slides">
                    <li>
                        <img src="http://lorempixel.com/580/250/nature/1"> <!-- random image -->
                        <div class="caption center-align">
                        <h3>This is our big Tagline!</h3>
                        <h5 class="light grey-text text-lighten-3">Here's our small slogan.</h5>
                        </div>
                    </li>
                    <li>
                        <img src="https://baconmockup.com/999/749/"> <!-- random image -->
                        <div class="caption left-align">
                        <h3>Left Aligned Caption</h3>
                        <h5 class="light grey-text text-lighten-3">Here's our small slogan.</h5>
                        </div>
                    </li>
                    <li>
                        <img src="http://lorempixel.com/580/250/nature/3"> <!-- random image -->
                        <div class="caption right-align">
                        <h3>Right Aligned Caption</h3>
                        <h5 class="light grey-text text-lighten-3">Here's our small slogan.</h5>
                        </div>
                    </li>
                    <li>
                        <img src="http://lorempixel.com/580/250/nature/4"> <!-- random image -->
                        <div class="caption center-align">
                        <h3>This is our big Tagline!</h3>
                        <h5 class="light grey-text text-lighten-3">Here's our small slogan.</h5>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
      </div>
      <!--end of slider-->
      <div class="section">
        <div class="row">
            <div class="col s12 center">
                <h5>Beschrijving</h5>
            </div>
        </div>      
        <div class="row">
            <div class="col s12">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. 
                    Tempore natus, similique illo nisi voluptatum unde cum. 
                    Assumenda laudantium alias, ex, nostrum quibusdam consectetur 
                    amet nemo mollitia consequatur, id unde illum.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <table>
                    <tbody>
                        <!--deze dingen ook in php afgekort moeten worden-->
                        <!--want als je bv de opleiding heel lang maakt is-->
                        <!--de hele layout verpest omdat truncate niet goed werkt-->
                        <tr>
                            <td>Projectlid 1:</td>
                            <td class="right-align truncate">Jackie Chan</td>                                                        
                        </tr>
                        <tr>
                            <td>Projectlid 2:</td>
                            <td class="right-align truncate">Bruce Lee</td>                                                        
                        </tr>
                        <tr>
                            <td>Projectlid 3:</td>
                            <td class="right-align truncate">Foe Yong Hai</td>                                                        
                        </tr>
                        <tr>
                            <td>Opleiding:</td>
                            <td class="right-align truncate">Particuliere Beveiliging</td>                                           
                        </tr>                                                                      
                    </tbody>
                </table>
            </div>
        </div>
      </div>
      <div class="section">
        <div class="row">
            <div class="col s12 center">
                <h5>Wat hebben we nodig?</h5>
            </div>
        </div>      
        <div class="row">
            <div class="col s12">
                <p>
                    Wij hebben een website nodig en nog een hoop andere dingen. 
                    Vandaar dat wij ICT'ers nodig hebben.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <table>
                    <thead>
                        <th>Opleiding(en) nodig:</th>
                    </thead>
                    <tbody>
                        <!--deze dingen ook in php afgekort moeten worden-->
                        <!--want als je bv de opleiding heel lang maakt is-->
                        <!--de hele layout verpest omdat truncate niet goed werkt-->
                        <tr>
                            <td>Opleiding nodig 1</td>                                                     
                        </tr>
                        <tr>
                            <td>Opleiding nodig 2</td>                                                     
                        </tr>
                        <tr>
                            <td>Opleiding nodig 3</td>                                                     
                        </tr>                                                                                             
                    </tbody>
                </table>
            </div>
        </div>
      </div>      
  </div>
</main>
<footer class="page-footer teal">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
            <h5 class="white-text">Footer Content</h5>
            <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer content.</p>
            </div>
            <div class="col l4 offset-l2 s12">
            <h5 class="white-text">Links</h5>
            <ul>
                <li><a class="grey-text text-lighten-3" href="#!">Link 1</a></li>
                <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
                <li><a class="grey-text text-lighten-3" href="#!">Link 3</a></li>
                <li><a class="grey-text text-lighten-3" href="#!">Link 4</a></li>
            </ul>
            </div>
        </div>
        </div>
        <div class="footer-copyright">
        <div class="container">
        &copy 2014 Copyright Text
        <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
        </div>
    </div>
</footer>
  <script type="text/javascript" src="js/main.js"></script>
  <script type="text/javascript" src="js/ajaxfunctions.js"></script>
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
  $(document).ready(function(){
      $('.slider').slider();
    }); 
</script>
</html>