var counter = 0;
function saveNewRowAjax(colorCount,rowCount){
    var newValue = [];
    var newColors = [];
    var errorCount = 0;
    var tempColVal;
    for(var colorRow=0;colorRow <= rowCount;colorRow++){
        tempColVal = document.getElementsByClassName("newColorPicker"+colorRow)[0].value;
        newColors[colorRow] = getColorNameOrKey("hash",tempColVal.replace("#",""))
    }
    for(var row = 0; row <= rowCount;row++){
        newValue[row] = document.getElementById("newInput" + row).value
        if (newValue[row] == ""){
            $("#newLabel" + row).attr('data-error','De tekst kan niet leeg zijn');
            document.getElementById("newInput" + row).classList.remove("valid");
            document.getElementById("newInput" + row).classList.add("invalid");
            errorCount++;
        } 
    }
    if (errorCount > 0){
        for(var row =0; row <= rowCount;row++){
            $("#newLabel" + row).attr('data-error','Alle teksten moeten gevuld zijn');
            document.getElementById("newInput" + row).classList.remove("valid");
            document.getElementById("newInput" + row).classList.add("invalid");
            errorCount++;
        }
    }
    else {
        var send = $.ajax({
            url: "addNewCollege.ajax.php",
            type: "POST",
            dataType: "json",
            data: {"naam":newValue,"colors":newColors},
            error: function(xhr, text, error) {
                console.warn(xhr.responseText);
                    console.log(text);
                    console.log(error);
            }          //I do change this `main_array` when using the above stringify!
        });
        send.done(function(msg) {
            counter = 0;
            for(var row= msg.length - 1;row > -1;row--){
                
                $("#newLabel"+ counter).attr("data-success","Nieuw college toegevoegd"); //zet het "gelukt bericht"
                document.getElementById("newInput"+ counter).classList.remove("invalid");//
                document.getElementById("newInput"+ counter).classList.add("valid");//
                $("#newRow"+ counter).attr("id",msg[row]['id']);//veranderd het Id van verschillende elementen zodat er geen dubbele id's komen
                $("#newInput"+ counter).attr("id","input"+ msg[row]['id']);//
                $("#newLabel"+ counter).attr("id","lbl"+ msg[row]['id']);//

                document.getElementById('newButtonDiv'+ counter).innerHTML += //voegt de edit button toe aan het form
                '<a onclick="editCollegeAjax('+msg[row]['id']+');"'+//
                'class="btn-floating btn-medium waves-effect waves-light red">'+//
                '<i class="material-icons">edit</i></a>'; //
                $("#newButtonDiv"+ counter).attr("id","newbutton"+msg[row]['id']);
                
                $("#input"+msg[row]['id']).attr("value",newValue[counter]);//geeft de value mee aan de inputbox zodat deze niet leeg is
                
                document.getElementById('newTd'+ counter).innerHTML =
                '<input class="filled-in" type="checkbox" id="select'+msg[row]['id']+'"/>'+
                '<label for="select'+msg[row]['id']+'"></label>'; //zorgt ervoor dat de checkbox wordt gemaakt.
                $("#newTd"+counter).attr("id","td"+msg[row]['id']);
                
                counter++;
            }
        })
        resetCounts();
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