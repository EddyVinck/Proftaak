var elemarray =[
    document.getElementById("home"),
    document.getElementById("login_as_school"),
    document.getElementById("login_as_leraar"),
    document.getElementById("login_as_student")
];
function loginFade(array_val){
    for(var x = 0;x < elemarray.length;x++){
        elemarray[x].className = "card hide";
        console.log(elemarray[x].innerHTML);
    }
    console.log(elemarray);
    elemarray[array_val].className = "card";
}
  