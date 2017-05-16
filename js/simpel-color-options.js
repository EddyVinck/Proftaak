$(document).ready(function(){

  $('.close_button').click(function(event) {
    $('input').closeChooser();
  });

  $('.set_color_button').click(function(event) {
    $('input').setColor('#cc3333');
  });

  $('.colorpicker').simpleColor({
    boxHeight: 40,
    cellWidth: 20,
    cellHeight: 20,
    displayColorCode: true,
    onSelect: function(hex, element) {
        var name = getColorNameOrKey("hash",hex);
        console.log(name);
    }
  });

});