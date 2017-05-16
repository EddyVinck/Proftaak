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
    console.log(newID);
    $("#collegeTable > tbody").append(
        '<tr id="newRow">' +
            '<td>'+
                '<div class="row">' +
                '<form method="POST"  id="newButtonDiv">' +
                '<div  class="input-field beheer-inputs col s2">' +
                    '<input ' +
                    'id="newInput" ' +
                    'type="text" class="validate">' +
                '<label id="newLabel" class="active" ' +
                    'data-error="Het is hetzelfde" ' +
                    'data-success=""' +
                    'for="newInput"> </label>' +
                '</div>' +
            '</td><td>'+
                '<a class=" waves-effect waves-light btn"></a>'+
            '</td><td id="newTd">'+
                // '<input class="filled-in" type="checkbox" id="select"/>'+
                // '<label for="select"></label>'+
                '<a onclick="saveNewRowAjax();"' +
                'class="btn-floating btn-medium waves-effect waves-light red">'+
                '<i class="material-icons">save</i></a>'+
        '</tr>'+
        '</form>' +
    '</div>');
}
$(document).ready(function(){

  $('.close_button').click(function(event) {
    $('input').closeChooser();
  });

  $('.set_color_button').click(function(event) {
    $('input').setColor('#cc3333');
  });

  $('.simple_color').simpleColor();

  $('.simple_color_color_code').simpleColor({ displayColorCode: true });

  $('.simple_color_custom_chooser_css').simpleColor({ chooserCSS: { 'background-color': 'black', 'opacity': '0.8' } });

  $('.simple_color_custom_display_css').simpleColor({ displayCSS: { 'border': '1px solid red' } });

  $('.simple_color_custom_cell_size').simpleColor({ cellWidth: 30, cellHeight: 10 });

  $('.simple_color_live_preview').simpleColor({ livePreview: true });

  $('.simple_color_callback').simpleColor({
    onSelect: function(hex, element) {
      alert("You selected #" + hex + " for input #" + element.attr('class'));
    }
  });

  $('.simple_color_mouse_enter').simpleColor({
    onCellEnter: function(hex, element) {
      console.log("You just entered #" + hex + " for input #" + element.attr('class'));
    }
  });
  $('.colorpicker').simpleColor({
    boxHeight: 40,
    cellWidth: 20,
    cellHeight: 20,
    displayColorCode: true,
    onSelect: function(hex, element) {
    }
  });

});
