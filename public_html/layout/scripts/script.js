function accountType(type)
{
var input = document.getElementById("ni");
if (type == 'a')
{
document.getElementById("title").innerHTML="Login: ";
input.removeAttribute("onKeyDown", "");
input.setAttribute("maxlength", "16");
}
else if (type == 'u')
{
document.getElementById("title").innerHTML="Numer indeksu: ";
input.setAttribute("onKeyDown", "javascript:return dFilter (event.keyCode, this, '##########');");
input.setAttribute("maxlength", "10");
}
}
