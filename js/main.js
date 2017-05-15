var elemArray =[
    document.getElementById("home"),
    document.getElementById("login_as_school"),
    document.getElementById("login_as_leraar"),
    document.getElementById("login_as_student")
];
function initializeSelectElements(){
    $(document).ready(function() {
        $('select').material_select();
    });
}
function getSelect_Ajax(selectedOption, table, id, elemName, nextSelect) {
    if (selectedOption == "") {
        // als er geen optie is geselecteerd en het select element 
        // nog steeds op de disabled optie staat
        document.getElementById(elemName).innerHTML = "";
        return;
    } else if (selectedOption == document.getElementById(elemName).value) {
        /*
        this code runs when the user has selected an item in the select element
        but decides to open the select element again only to select the same option yet again.
        This check prevents the follow-up select elements from resetting
        
        note: for reasons currently unknown this only works when follow-up select elements
        have been assigned a selected option by the user
        */
        console.log("user selected the same option in a select element");
        return;
    }    
    else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) { //eerlijk gezegt geen idee wat dit doet, maar t werkt
                document.getElementById(elemName).innerHTML = this.responseText; 
                console.log(this.responseText);
                //responsetext komt terug vanuit t PHP bestand
                initializeSelectElements(); //functie die nodig is om de Select's te herladen
            }
        };
        xmlhttp.open("GET","getKlas.php?q="+ selectedOption+"&tableName=" + table + "&idName="+ id +"&nextSelect="+nextSelect,true); //q= get variabele die  in php bestand wordt gebruikt
        //de 'selectedOption' variabele wordt meegegeven vanuit een HTML onchange-event op het select element.
        xmlhttp.send();
    }
}
function loginFade2(key){
    for(var i = 0; i < elemArray.length; i++){
        elemArray[i].classList.add("hide");
    }
    elemArray[key].classList.remove("hide");
}

function editCollegeAjax(collegeIdNr,originalText){
    var elemInp = document.getElementById("input" + collegeIdNr);
    var elemLbl = document.getElementById("lbl" + collegeIdNr);
    var inpVal = elemInp.value;
    if (inpVal == ""){
        $("#lbl" + collegeIdNr).attr('data-error','De tekst kan niet leeg zijn');
        elemInp.classList.remove("valid");
        elemInp.classList.add("invalid");
    }
    else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) { //eerlijk gezegt geen idee wat dit doet, maar t werkt
                //GELUKT
                $("#lbl" + collegeIdNr).attr('data-success','Het is veranderd');
                elemInp.classList.remove("invalid");
                elemInp.classList.add("valid");
                
                //responsetext komt terug vanuit t PHP bestand
                initializeSelectElements(); //functie die nodig is om de Select's te herladen
            }
        };
        xmlhttp.open("GET","changeCollegeName.php?text=" + elemInp.value + 
        "&id=" + collegeIdNr,true);
        //de 'selectedOption' variabele wordt meegegeven vanuit een HTML onchange-event op het select element.
        xmlhttp.send();
    }
}
function addTableRow(){
    $("#collegeTable > tbody").append(
        '<tr>' +
            '<td>'+
                '<div class="row">' +
                '<form method="POST">' +
                '<div  class="input-field beheer-inputs col s2">' +
                    '<input value="" ' +
                    'id="" ' +
                    'type="text" class="validate">' +
                '<label id="" class="active" ' +
                    'data-error="Het is hetzelfde" ' +
                    'data-success=""' +
                    'for=""> </label>' +
                '</div>' +
            '</td><td>'+
                '<a class=" waves-effect waves-light btn"></a>'+
            '</td><td>'+
            '<input class="filled-in" type="checkbox" id="select"/>'+
                '<label for="select"></label>'+
        '</tr>'+
        '</form>' +
    '</div>');
}