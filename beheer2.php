<?php
include("inc/functions.php");
$db =  ConnectToDatabase();
checkSession();
checkUserVerification();
$rol = $_SESSION['rol'];
if($rol != "sch" && $rol != "doc" && $rol != "adm"){
    header("location: unauthorized.php");
}

if(isset($_SESSION['college_id']))
{
    $collegeId = $_SESSION['college_id'];
    $pageColor = changePageColors($db, $collegeId);
}

$tabClass = "s3";
if ($rol == "doc") {
    $tabClass = "s6";
}
$delay = 1;
?>
<html lang="en" dir="ltr">
<head>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="css/materializeAddons.css"  media="screen,projection"/>
    <script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.simple-color.js"></script>
    <!-- <link type="text/css" rel="stylesheet" href = "css/school_beheer.css"/> -->
    <link type="text/css" rel="stylesheet" href = "css/footer.css"/>
    <link type="text/css" rel="stylesheet" href = "css/beheer2.css"/>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
    <!-- Mustache templates -->
    <template id="schoolselectTtemplate">
        {{#.}}
        <option value="{{id}}">{{naam}}</option>
        {{/.}}
    </template>
    <template id="docentTemplate">
        {{#.}}
        <div data-id="{{id}}" class="docent">
            <div class="docent-part">
                {{naam}}
            </div>
            <div class="docent-part">
                <select value="3" name="scholen" id="docentCollegeSelect{{id}}" class="docent-college-select">
                    {{#selects}}
                    <option {{selected}} value="{{id}}">{{naam}}</option>
                    {{/selects}}
                </select>
            </div>
            <div class="docent-part">
                <a href="#" class="btn waves-effect tooltipped {{verification}} js-docent-verificatie" data-id="{{id}}" data-rol={{rol}} data-position="bottom" data-delay="<?=$delay?>" data-tooltip="{{tooltip}}">
                    {{buttonText}}
                </a>
            </div>
        </div>
        {{/.}}
    </template>
    <template id="collegeTemplate">
        {{#.}}
        <div data-id="{{id}}" class="college">
            <div class="college-part">
                <div class="input-field">
                    <input value="{{naam}}" id="collegeName{{id}}" type="text" class="validate js-college-name">
                    <label data-error="alskjdasdlkjdas" class="active" for="collegeName{{id}}">Naam</label>
                </div>
            </div>
            <div class="college-part">
                <input class='colorpicker js-college-kleur' id="college-kleur{{id}}" value='{{kleur}}'/>
            </div>
            <div class="college-part">
                <a href="#" class="btn-floating btn-medium waves-effect waves-light red tooltipped js-delete-college" data-position="bottom" data-delay="<?=$delay?>" data-tooltip="Klik om deze rij te verwijderen">
                    <i class="material-icons">delete</i>
                </a>
            </div>
        </div>
        {{/.}}
    </template>
    <template id="klassenTemplate">
        {{#.}}
        <div data-id="{{id}}" class="klas">
            <div class="klas-part">
                <div class="input-field">
                    <input value="{{naam}}" id="klasName{{id}}" type="text" class="validate js-klas-name">
                    <label data-error="alskjdasdlkjdas" class="active" for="klasName{{id}}">Naam</label>
                </div>
            </div>
            <div class="klas-part">
                <select value="3" id="klasSelect{{id}}" class="klas-college-select">
                    {{#selects}}
                    <option {{selected}} value="{{id}}">{{naam}}</option>
                    {{/selects}}
                </select>
            </div>
            <div class="klas-part">{{aantal}}</div>
            <div class="klas-part">
                <a href="#" class="btn-floating btn-medium waves-effect waves-light red tooltipped js-delete-klas" data-position="bottom" data-delay="<?=$delay?>" data-tooltip="Klik om deze rij te verwijderen">
                    <i class="material-icons">delete</i>
                </a>
            </div>
        </div>
        {{/.}}
    </template>
    <template id="studentenTemplate">
        {{#.}}
        <div data-id="{{user_id}}" class="student">
            <div class="student-part">
                {{name}}
            </div>
            <div class="student-part">
                <select value="3" id="studentCollegeSelect{{user_id}}" class="student-college-select">
                    {{#colleges}}
                    <option {{selected}} value="{{id}}">{{naam}}</option>
                    {{/colleges}}
                </select>
            </div>
            <div class="student-part">
                <select value="3" id="studentKlasSelect{{user_id}}" class="student-klas-select">
                </select>
            </div>
            <div class="student-part">
                <a href="#" class="btn waves-effect tooltipped {{verification}} js-student-verificatie" data-id="{{user_id}}" data-rol={{rol}} data-position="bottom" data-delay="<?=$delay?>" data-tooltip="{{tooltip}}">
                    {{buttonText}}
                </a>
            </div>
        </div>
        {{/.}}
    </template>
    <template id="studentKlasSelectTemplate">
        {{#.}}
        <option value="{{id}}">{{naam}}</option>
        {{/.}}
    </template>
    <div id="modalDeleteKlas" data-delid="" class="modal">
        <div class="modal-content">
            <h4>Weet je zeker dat je deze klas wilt verwijderen?</h4>
            <p>De klas zal permanent verwijdert worden</p>
            <div class="js-modal-errors-klas">

            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-close btn waves-effect waves-light modal-button">Nee</button>
            <button class="js-delete-klas-confirm btn waves-effect waves-light modal-button">Ja</button>
        </div>
    </div>
    <!-- Modals -->
    <div id="modalDeleteCollege" data-delid="" class="modal">
        <div class="modal-content">
            <h4>Weet je zeker dat je dit college wilt verwijderen?</h4>
            <p>Het college zal permanent verwijdert worden</p>
            <div class="js-modal-errors">

            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-close btn waves-effect waves-light modal-button">Nee</button>
            <button class="js-delete-college-confirm btn waves-effect waves-light modal-button">Ja</button>
        </div>
    </div>
    <div id="modalNewCollege" class="modal">
        <div class="modal-content">
            <h4>Maak een nieuw college</h4>
            <div class="">
                <div class="input-field col s6">
                    <input id="new-college-naam" type="text" class="validate">
                    <label for="new-college-naam">Naam</label>
                </div>
                <input class='colorpicker2 js-college-new-kleur' id="new-college-kleur" value='#3f51b5'/>
            </div>
            <div class="js-modal-errors">

            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-close btn waves-effect waves-light modal-button">Annuleer</button>
            <button class="js-save-new-college btn waves-effect waves-light modal-button">Opslaan</button>
        </div>
    </div>
    <div id="modalNewKlas" class="modal">
        <div class="modal-content">
            <h4>Maak een nieuwe klas</h4>
            <div class="">
                <div class="input-field col s6">
                    <input id="new-klas-naam" type="text" class="validate">
                    <label for="new-klas-naam">Naam</label>
                </div>
                <select value="3" id="newKlasSelect" class="new-klas-college-select">
                </select>
            </div>
            <div class="js-modal-errors"></div>
        </div>
        <div class="modal-footer">
            <button class="modal-close btn waves-effect waves-light modal-button">Annuleer</button>
            <button class="js-save-new-klas btn waves-effect waves-light modal-button">Opslaan</button>
        </div>
    </div>
    <?php createHeader($pageColor);?>
    <main>
        <div class="container">
            <?php if ($rol == "adm"): ?>
                <div class="row">
                    <div class="card-content">
                        <select value="3" name="scholen" id="schoolSelect" class="schoolSelect">

                        </select>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col s12">
                    <ul class="tabs nooverflow">
                        <?php if ($rol == "adm" || $rol == "sch"): ?>
                            <li class="tab col <?=$tabClass?>"><a href="#colleges">Colleges</a></li>
                            <li class="tab col <?=$tabClass?>"><a href="#docenten">Docenten</a></li>
                        <?php endif; ?>
                        <?php if ($rol == "adm" || $rol == "sch" || $rol == "doc"): ?>
                            <li class="tab col <?=$tabClass?>"><a href="#klassen">Klassen</a></li>
                            <li class="tab col <?=$tabClass?>"><a class="active" href="#studenten">Studenten</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php if ($rol == "adm" || $rol == "sch"): ?>
                    <div id="colleges" class="col s12">
                        <div class="college college--header">
                            <div class="college-part">Naam</div>
                            <div class="college-part">Kleur</div>
                            <div class="college-part">Verwijder</div>
                        </div>
                        <div id="colleges-container">
                        </div>
                        <div class="">
                            <button class="btn-floating btn-large red tooltipped js-save-colleges"
                            data-position="bottom"
                            data-delay="<?=$delay?>"
                            data-tooltip="Sla alle rijen op">
                                <i class="material-icons">save</i>
                            </button>
                            <button class="btn-floating btn-large red tooltipped js-new-college"
                            data-position="bottom"
                            data-delay="<?=$delay?>"
                            data-tooltip="Maak een nieuwe college">
                                <i class="material-icons">add</i>
                            </button>
                        </div>
                    </div>
                    <div id="docenten" class="col s12">
                        <div class="docent docent--header">
                            <div class="docent-part">Naam</div>
                            <div class="docent-part">College</div>
                            <div class="docent-part">Verificatie</div>
                        </div>
                        <div id="docenten-container">

                        </div>
                        <button class="btn-floating btn-large red tooltipped js-save-docenten"
                        data-position="bottom"
                        data-delay="<?=$delay?>"
                        data-tooltip="Sla alle rijen op">
                            <i class="material-icons">save</i>
                        </button>
                    </div>
                <?php endif; ?>
                <?php if ($rol == "adm" || $rol == "sch" || $rol == "doc"): ?>
                    <div id="klassen" class="col s12">
                        <div class="docent docent--header">
                            <div class="docent-part">Naam</div>
                            <div class="docent-part">College</div>
                            <div class="docent-part">Aantal studenten</div>
                            <div class="docent-part">Verwijder</div>
                        </div>
                        <div id="klassen-container">

                        </div>
                        <button class="btn-floating btn-large red tooltipped js-save-klassen"
                        data-position="bottom"
                        data-delay="<?=$delay?>"
                        data-tooltip="Sla alle rijen op">
                            <i class="material-icons">save</i>
                        </button>
                        <button class="btn-floating btn-large red tooltipped js-new-klas"
                        data-position="bottom"
                        data-delay="<?=$delay?>"
                        data-tooltip="Maak een nieuwe klas">
                            <i class="material-icons">add</i>
                        </button>
                    </div>
                    <div id="studenten" class="col s12">
                        <div class="student student--header">
                            <div class="student-part">Naam</div>
                            <div class="student-part">College</div>
                            <div class="student-part">Klas</div>
                            <div class="student-part">Rol</div>
                        </div>
                        <div id="studenten-container">

                        </div>
                        <button class="btn-floating btn-large red tooltipped js-save-studenten"
                        data-position="bottom"
                        data-delay="<?=$delay?>"
                        data-tooltip="Sla alle rijen op">
                            <i class="material-icons">save</i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php createFooter($pageColor);?>
    <script type="text/javascript" src="js/ajaxfunctions.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/materialize.js"></script>
    <script type="text/javascript" src="js/mustache.min.js"></script>
    <script type="text/javascript" src="js/beheer2.js"></script>
    <script>

    initializeSelectElements();
    initSideNav();
    </script>
</body>
</html>
