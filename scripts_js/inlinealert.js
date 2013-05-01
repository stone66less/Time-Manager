
var alertBox =  document.getElementById("alertLayer").style

function hideAlert(){
alertBox.visibility = "hidden";}

function makeAlert(aMessage){
var aTitle = "Errors Detected";
document.getElementById("alertLayer").innerHTML = "<table border=0 width=100% height=100%>" +
"<tr height=5><td colspan=4 class=alertTitle>" + " " + aTitle + "</td></tr>" +
"<tr height=5><td width=5></td></tr>" +
"<tr><td width=5></td><td width=20 align=left><img src='images/messagebox_warning.png'></td><td align=center class=alertMessage>" + aMessage + "<BR></td><td width=5></td></tr>" + 
"<tr height=5><td width=5></td></tr>" +
"<tr><td width=5></td><td colspan=4 align=center><input type=button value='Close' onClick='hideAlert()' class=okButton><BR></td><td width=5></td></tr>" +
"<tr height=5><td width=5></td></tr></table>";
thisText = aMessage.length;
if (aTitle.length > aMessage.length){ thisText = aTitle.length; }

aWidth = (thisText * 5) + 80;
aHeight = 100;
if (aWidth < 150){ aWidth = 200; }
if (aWidth > 350){ aWidth = 350; }
if (thisText > 60) { aHeight = 120; }
if (thisText > 120){ aHeight = 160; }
if (thisText > 180){ aHeight = 200; }
if (thisText > 240){ aHeight = 240; }
if (thisText > 300){ aHeight = 280; }
if (thisText > 360){ aHeight = 320; }
if (thisText > 420){ aHeight = 360; }
if (thisText > 490){ aHeight = 400; }
if (thisText > 550){ aHeight = 440; }
if (thisText > 610){ aHeight = 480; }

alertBox.width = aWidth + "px";
alertBox.height = aHeight + "px";
alertBox.left = (document.body.clientWidth - aWidth)/2;
alertBox.top = (document.body.clientHeight - aHeight)/2;

alertBox.visibility = "visible";
}
