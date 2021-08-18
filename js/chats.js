function clearActive(){
    var td = document.getElementsByClassName("active");
    for (i = 0; i<td.length; i++){
    td[i].className = "";
    }
}
function selectChat(event, chatid){
    var eventType = event.target.id;
    clearActive();
    event.target.className = "active";
    switch (eventType){
      case "add":
          document.getElementById("iframe").src = "./createchat.php";
          break;
      case "chat":
          document.getElementById("iframe").src = "./chat.php?chatid="+chatid;
          break;
    }
}