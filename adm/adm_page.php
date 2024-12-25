<?php
$db_connection = pg_connect("host=localhost dbname=test user=postgres password=yo_password");
if (isset($_COOKIE["adm_login"])) {
		if ($_COOKIE["adm_login"] == 1) {
			$adm_id = $_COOKIE["adm_id"];
			$result = pg_query($db_connection, "SELECT * FROM administrators WHERE adm_id='$adm_id'");
			$num_r = pg_num_rows($result);
		    if ($num_r == 0) {
				setcookie('adm_login', 0, time()+60*30);
				$_COOKIE["adm_login"] = 0;
				echo "<script>window.location = 'adm_page.php'</script>";
			}
		}
	}
else {
	setcookie('adm_login', 0, time()+60*30);
	$_COOKIE["adm_login"] = 0;
	echo "<script>window.location = 'adm_page.php'</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../g_styles.css">
</head>
<body>
<a style="text-decoration:none" href="adm_page.php" ><h2 class="example" align="center">Art Gallery</h2></a>
<?php
if (isset($_COOKIE["msg"])) {
	if ($_COOKIE["msg"] == 1) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>Admin with this name already exists</p></div>";
	}
	if ($_COOKIE["msg"] == 2) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>Wrong login or password</p></div>";
	}
	if ($_COOKIE["msg"] == 3) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>Image deleted</p></div>";
	}
	if ($_COOKIE["msg"] == 4) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>Changed image info</p></div>";
	}
	if ($_COOKIE["msg"] == 5) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>Deleted user</p></div>";
	}
	if ($_COOKIE["msg"] == 6) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>Could not delete user</p></div>";
	}
	if ($_COOKIE["msg"] == 7) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>Changed user info</p></div>";
	}
	if ($_COOKIE["msg"] == 8) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>Banned user</p></div>";
	}
	if ($_COOKIE["msg"] == 9) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>Unbanned user</p></div>";
	}
}
?>
<table align="center" width="30%"><tr><td><button onclick="document.getElementById('id01').style.display='block'">Log in</button></td></tr></table>
<div id="id01" class="modal">
  
  <form class="modal-content animate" action="adm_main.php" method="post">
    <div class="imgcontainer">
      <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
    </div>

    <div class="container">
      <label for="name"><b>Login</b></label>
      <input type="text" placeholder="Enter login" name="name" required>

      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="psw" required>
      <input type="hidden" id="adm_status" name="adm_status" value="2">
      <button type="submit">Log in</button>
    </div>
  </form>
</div>

<div id="id02" class="modal">
  
  <form class="modal-content animate" action="adm_main.php" method="post">
    <div class="imgcontainer">
      <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">&times;</span>
    </div>

    <div class="container">
      <label for="name"><b>Login</b></label>
    <input type="text" placeholder="Enter login" name="name" id="name" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" id="psw" required>
      <input type="hidden" id="adm_status" name="adm_status" value="1">
      <button type="submit">Register</button>
    </div>
  </form>
</div>

<script>
window.onscroll = function() {myFunction()};

var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;

function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}
</script>

<script>
// Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
</body>
</html>