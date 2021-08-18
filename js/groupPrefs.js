function clearActive(){
    var td = document.getElementsByClassName("active");
    for (i = 0; i<td.length; i++){
    td[i].className = "";
    }
}
function selectIFrameTarget(event, groupid){
    var eventType = event.target.id;
    clearActive();
    event.target.className = "active";
    switch (eventType){
      case "general":
          document.getElementById("iframe").src = "./grpGeneralSettings.php?groupid="+groupid;
          break;
      case "mreq":
          document.getElementById("iframe").src = "./grpMessageRequests.php?groupid="+groupid;
          break;
      case "delgrp":
          document.getElementById("iframe").src = "./grpDelete.php?groupid="+groupid;
          break;
      case "mems":
          document.getElementById("iframe").src = "./grpMembers.php?groupid="+groupid;
          break;
      case "general":
          document.getElementById("iframe").src = "./grpGeneralSettings.php?groupid="+groupid;
          break;
    }
}