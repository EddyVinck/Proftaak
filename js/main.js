var elemArray =[
    document.getElementById("home"),
    document.getElementById("login_as_school"),
    document.getElementById("login_as_leraar"),
    document.getElementById("login_as_student")
];
function loginFade(array_val){
    for(var x = 0;x < elemArray.length;x++){
        elemArray[x].className = "card hide";
    }
    elemArray[array_val].className = "card";
}
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