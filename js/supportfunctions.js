			function myFunction(id)
			{
			$sameuser= false;
			var r=window.top.confirm("Are you sure you want to delete this record?");
			if (r==true)
			  {
			  	window.location.assign("delete.php?id=" + id);
			  }
			}
   function search(){
       var searchterm = document.getElementById("myInput").value
       window.location.assign("search.php?term=" + searchterm)
   }
		function dropIt() {
		    toggleClass(document.getElementById('requests'), "hide");
		}

		function foldIt() {
		    toggleClass(document.getElementById('system'), "hide");
		}
   
     function downIt(){
         toggleClass(document.getElementById('messages'), "hide");
     }

		function setHeight() {
		    var el = document.getElementById('navigation-dropdown');
		    el.style.height = el.clientHeight + "px";
		}
		function loaded(){

		}
		var toggleClass = function (el, className) {
		    if (el) {
		        if (el.className.indexOf(className) != -1) {
		            el.className = el.className.replace(className, '');
		        } else {
		            el.className += ' ' + className;
		        }
		    }
		}
var newpost = document.getElementsByClassName("postBut");
var i;

for (i = 0; i < newpost.length; i++) {
    newpost[i].addEventListener("click", function() {
        var parentform = this.parentElement;
        var sidebar = parentform.parentElement;
        if(parentform.classList.contains("postactive")){
          parentform.classList.toggle("postactive");
          sidebar.classList.toggle("magicopen");
        } else{
          sidebar.classList.toggle("magicopen");
          parentform.classList.toggle("postactive");
        }
    });
}

var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
        this.classList.toggle("accordactive");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
}


function comments(postID, group, isgroup) {
    console.log(postID);
    console.log(group);
    var container = document.getElementById(postID + " " + group);
    if(group == 0){
        group = "";
    }
    if (container.style.height === "150pt") {
        container.innerHTML = "";
        container.style.height = "0pt";
    } else {
        container.innerHTML = "<iframe src=\"comments.php?postid="+postID+"&group="+group+"&isgroup="+isgroup+"\"style=\"width:100%; height:150pt;\" sandbox=\"allow-top-navigation allow-same-origin allow-scripts allow-popups allow-forms\" frameborder=\"0\"></iframe>";
        container.style.height = "150pt";
    }
}

function reply(commentid, name, date){
    document.makeComment.comment.innerHTML = "[@" + name + "] ";
    document.makeComment.replyid.value = commentid;
    document.makeComment.comment.focus();
}

