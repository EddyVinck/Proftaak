function saveNewRowAjax(colorelem){
    var newValue = document.getElementById("newInput").value;
    var newcolorpickerElement = document.getElementsByClassName(colorelem);
    var newcolorVal = newcolorpickerElement[0].value;
    newcolorVal = getColorNameOrKey("hash",newcolorVal.replace("#",""));
    
    if (newValue == ""){
        $("#newLabel").attr('data-error','De tekst kan niet leeg zijn');
        document.getElementById("newInput").classList.remove("valid");
        document.getElementById("newInput").classList.add("invalid");
    } else { 
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
                $("#newLabel").attr("data-success","Nieuw college toegevoegd"); //zet het "gelukt bericht"
                document.getElementById("newInput").classList.remove("invalid");//
                document.getElementById("newInput").classList.add("valid");//
                $("#newRow").attr("id",this.responseText);//veranderd het Id van verschillende elementen zodat er geen dubbele id's komen
                $("#newInput").attr("id","input"+ this.responseText);//
                $("#newLabel").attr("id","lbl"+ this.responseText);//
                document.getElementById('newButtonDiv').innerHTML += //voegt de edit button toe aan het form
                '<a onclick="editCollegeAjax('+this.responseText+');"'+//
                'class="btn-floating btn-medium waves-effect waves-light red">'+//
                '<i class="material-icons">edit</i></a>'; //
                $("#newButtonDiv").attr("id","newbutton"+this.responseText);
                $("#input"+this.responseText).attr("value",newValue);//geeft de value mee aan de inputbox zodat deze niet leeg is
                $("#newTd").html(
                '<input class="filled-in" type="checkbox" id="select'+this.responseText+'"/>'+
                '<label for="select'+this.responseText+'"></label>'); //zorgt ervoor dat de checkbox wordt gemaakt.
                
            }
        };
        xmlhttp.open("GET","addNewCollege.ajax.php?text=" + newValue + 
        "&color=" + newcolorVal,true);
        //de 'selectedOption' variabele wordt meegegeven vanuit een HTML onchange-event op het select element.
        xmlhttp.send();
    }
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
        xmlhttp.open("GET","getSelect.ajax.php?q="+ selectedOption+"&tableName=" + table + "&idName="+ id +"&nextSelect="+nextSelect,true); //q= get variabele die  in php bestand wordt gebruikt
        //de 'selectedOption' variabele wordt meegegeven vanuit een HTML onchange-event op het select element.
        xmlhttp.send();
    }
}
function editCollegeAjax(collegeIdNr){
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
        xmlhttp.open("GET","changeCollegeName.ajax.php?text=" + elemInp.value + 
        "&id=" + collegeIdNr,true);
        //de 'selectedOption' variabele wordt meegegeven vanuit een HTML onchange-event op het select element.
        xmlhttp.send();
    }
}