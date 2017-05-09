function loginFade(button_clicked){
    var elem;
    document.getElementById("test").className = "card hide"
    if (button_clicked == 0)
    {
         elem = document.getElementById("login_as_school");
    }else if (button_clicked == 1)
    {
        elem = document.getElementById("login_as_leraar");
    }else if (button_clicked == 2)
    {
        elem = document.getElementById("login_as_student");
    }
    elem.className = "card"
}
  