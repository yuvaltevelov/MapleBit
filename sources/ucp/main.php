<?php 
if(isset($_GET['page'])){
	$ucp = $_GET['page'];
}else{
	$ucp = "";
}
	if(isset($_SESSION['id'])){
		if($getcype == "ucp"){
			if($ucp == ""){
				echo "
<script>
$('#myTab a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
</script>
<legend>
Welcome Back, ".getInfo('accname', 'cype_session', 'accid')."</b>
</legend>
<ul id=\"myTab\" class=\"nav nav-tabs\">
	<li class=\"active\"><a href=\"#account\" data-toggle=\"tab\">Account</a></li>
	<li><a href=\"#community\" data-toggle=\"tab\">Community</a></li>
	<li><a href=\"?cype=ucp&amp;page=buynx\">Cash Shop</a></li>
	<li><a href=\"?cype=ucp&amp;page=ticket\">Tickets</a></li>
</ul>
 <div id=\"myTabContent\" class=\"tab-content\">
<div class=\"tab-pane fade in active\" id=\"account\">
	<br/>
	<a href=\"?cype=ucp&amp;page=accset\"><b>Account Settings</b></a><br/>
	<a href=\"?cype=ucp&amp;page=charfix\">Character Fixes</a><br/><br/>
</div>";
echo "
<div class=\"tab-pane fade\" id=\"community\">
    <br/>
	<a href=\"?cype=main&amp;page=mail\">Mail</a><br/>
	<a href=\"?cype=main&amp;page=ranking\">Rankings</a><br/>
	<a href=\"?cype=main&amp;page=members\">Members</a><br/>
	<a href=\"?cype=main&amp;page=guildlist\">Guild List</a><br/><br/>
</div></div>
";
if($_SESSION['pname'] == NULL){
echo "
<div class=\"alert alert-danger\">
  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
  <h4>Whoa there!</h4>
<a href=\"?cype=ucp&amp;page=profname\">You need set up a profile name &raquo;</a>
</div>";
}else{
echo "
<div class=\"well\"><a href=\"?cype=ucp&amp;page=profedit\">Edit Public Profile</a></div><br />";
}

			}elseif($ucp == "accset"){
				include('sources/ucp/account-settings.php');
			}elseif($ucp == "buynx"){
				include('sources/ucp/buynx.php');
			}elseif($ucp == "charfix"){
				include('sources/ucp/charfix.php');
			}elseif($ucp == "profedit"){
				include('sources/ucp/profile-edit.php');
			}elseif($ucp == "profname"){
				include('sources/ucp/profile-name.php');
			}elseif($ucp == "ticket"){
				include('sources/ucp/ticket.php');
			}elseif($ucp == "mail"){
				include('sources/ucp/mail.php');
			}
		}else{
			header("Location: ?cype=ucp");
		}
	}else{
			header("Location: ?cype=main");
	}
?>