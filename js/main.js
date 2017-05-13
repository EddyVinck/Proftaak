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
function getSelect_Ajax(str,table,id,elemName) {
    if (str == "") {
        document.getElementById(elemName).innerHTML = "";
        return;
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
                document.getElementById(elemName).innerHTML = this.responseText; 
                console.log(this.responseText);
                //responsetext komt terug vanuit t PHP bestand
                initializeSelectElements(); //functie die nodig is om de Select's te herladen
            }
        };
        xmlhttp.open("GET","getKlas.php?q="+ str+"&tableName=" + table + "&idName="+ id,true); //q= get variabele die  in php bestand wordt gebruikt
        //de 'str' variabele wordt meegegeven vanuit HTML onchange.
        xmlhttp.send();
    }
}
function loginFade2(array_val){
    for(var x = 0; x < elemArray.length; x++){
        console.log(elemArray[x]);
        elemArray[x].classList.add("hide");
    }
    elemArray[array_val].classList.remove("hide");
}