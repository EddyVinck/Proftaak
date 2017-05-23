var colorCount = 0;
var newRowCount = 0;
var colorpickerCount = 0;
var elemArray =[
    document.getElementById("home"),
    document.getElementById("login_as_school"),
    document.getElementById("login_as_leraar"),
    document.getElementById("login_as_student")
];
function resetCounts(){
    colorCount = 0;
    newRowCount = 0;
} 
function initializeSelectElements(){
    $(document).ready(function() {
        $('select').material_select();
    });
}
function loginFade2(key){
    for(var i = 0; i < elemArray.length; i++){
        elemArray[i].classList.add("hide");
    }
    elemArray[key].classList.remove("hide");
}
function addTableRow(){
    var tbodyElement = document.getElementById("collegeTbody");
    var lastRow = tbodyElement.rows[ tbodyElement.rows.length - 1 ];
    var newID = parseInt( lastRow.id) + 1;
    $("#collegeTable > tbody").append(
        '<tr id="newRow'+newRowCount+'">' +
            '<td class="center" id="newButtonDiv'+newRowCount+'"></td>'+
            '<td class="center">'+
                '<div class="row">' +
                '<form method="POST">' +
                '<div  class="input-field beheer-inputs col s2">' +
                    '<input ' +
                    'id="newInput'+newRowCount+'" ' +
                    'type="text" class="validate">' +
                '<label id="newLabel'+newRowCount+'" class="active" ' +
                    'data-error="Het is hetzelfde" ' +
                    'data-success=""' +
                    'for="newInput'+newRowCount+'"> </label>' +
                '</div>' +
            '</td><td class="center">'+
                '<input class="newColorPicker'+colorpickerCount+'" value="#2196f3"/>'+
            '</td><td class="center" id="newTd'+newRowCount+'">'+
            '</td>'+
        '</tr>'+
        '</form>' +
    '</div>');
    $("#saveAllRows").attr("onclick",'saveNewRowAjax('+"'"+colorCount+"','"+newRowCount+"'"+');');
    newRowCount++;
    initSpecificColorPicker(colorpickerCount);
    colorCount++;
    colorpickerCount++;
}

function getColorNameOrKey(mode,str){
    var colorArray = {
        'name' :{
            'red':"f44336",'pink':"e91e63",'purple':"9c27b0",
            'deep-purple':"673ab7",'indigo':"3f51b5",'blue':"2196f3",
            'light-blue':"03a9f4",'cyan':"00bcd4",'teal':"009688",
            'green':"4caf50",'light-green':"8bc34a",'lime':"cddc39",
            'yellow':"ffeb3b",'amber':"ffc107",'orange':"ff9800",
            'deep-orange':"ff5722",'brown':"795548",'grey':"9e9e9e",
            'blue-grey':"607d8b"
        }, 'hash' :{
            "f44336":"red","e91e63":"pink","9c27b0":"purple",
            "673ab7":"deep-purple","3f51b5":"indigo","2196f3":"blue",
            "03a9f4":"light-blue","00bcd4":"cyan","009688":"teal",
            "4caf50":"green","8bc34a":"light-green","cddc39":"lime",
            "ffeb3b":"yellow","ffc107":"amber","ff9800":"orange",
            "ff5722":"deep-orange","795548":"brown","9e9e9e":"grey",
            "607d8b":"blue-grey"
        }
    };
    return colorArray[mode][str];
}
function getValue(elem,colorName)
{
    var hash = getColorNameOrKey("name",colorName);
}
$(document).ready(function() {
    $(window).load(function() {
        var elems = document.getElementsByClassName('colorpicker');
        for(var x = 0;x<elems.length;x++){
            elems[x].value = "#" + getColorNameOrKey("name",elems[x].value);
        }
        if (elems.length > 0){
            $('.colorpicker').simpleColor({
                boxHeight: 40,
                cellWidth: 20,
                cellHeight: 20,
                displayColorCode: true,
                onSelect: function(hex, element, id) {
                    var name = getColorNameOrKey("hash",hex);
                    //editCollegeAjax(collegeIdNr, mode = 0, color = "#000");
                },
            });
        }
    });
});
function initSpecificColorPicker(count){
    $('.newColorPicker' + count).simpleColor({
        boxHeight: 40,
        cellWidth: 20,
        cellHeight: 20,
        displayColorCode: true,
        onSelect: function(hex, element) {
            var name = getColorNameOrKey("hash",hex);
        }
    });
}

function extendableCollapsableOnSmallScreen() {
    screenWidth = $(window).width();
    if(screenWidth <= 600) {
        $("#collapsable").attr("data-collapsible","extendable");
    }
    else {
        $("#collapsable").attr("data-collapsible","accordion");
    }
}
function initSideNav(){
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
  extendableCollapsableOnSmallScreen();
}