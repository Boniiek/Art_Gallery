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
<a style="text-decoration:none" href="adm_main.php" ><h2 class="example" align="center">Art Gallery</h2></a>
<div id="navbar">
  <a href="adm_main.php">Home</a>
  <a class="active" href="in_users.php">Search in users</a>
  <a href="in_pictures.php">Search in pictures</a>
  <div class="navbar">
  <div class="log_in_and_reg">
  <?php
  if ($_COOKIE["adm_login"] == 0) {
	  echo "<script>window.location = 'adm_page.php'</script>";
  }
  else {
	  $u_na = $_COOKIE["adm_name"];
	  echo "<table><td><a>$u_na</a></td><td><form action='adm_page.php' method='post'><input type='hidden' id='adm_status' name='adm_status' value='0'><button type='submit'>Log out</button></form></td></table>";
  }
  ?>
  </div>
</div>
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

<div class="grid-container">
<?php
$sel_user_id = $_POST["sel_user_id"];
$db_connection = pg_connect("host=localhost dbname=test user=postgres password=yo_password");
$result = pg_query($db_connection, "SELECT u_name, u_email, u_date, author, date_created, u_status FROM users WHERE user_id = $sel_user_id");
$u_name = pg_fetch_result($result, 0, 0);
$u_email = pg_fetch_result($result, 0, 1);
$u_date = pg_fetch_result($result, 0, 2);
$author = pg_fetch_result($result, 0, 3);
$date_created = pg_fetch_result($result, 0, 4);
$u_status = pg_fetch_result($result, 0, 5);
if ($u_status == 0)
$u_st = 'active';
else
$u_st = 'banned';
echo "<table><tr><td><p class='ncol' align='center'>$u_name</p></td></tr></table>";
echo "<table><tr><td><p class='ncol'>Email: $u_email</p></td></tr><hr>";
echo "<tr><td><p class='ncol'>Birth date: $u_date</p></td></tr>";
echo "<tr><td><p class='ncol'>Author: $author<br>
<br>Date created: $date_created<br>
<br>Status: $u_st</p></td></tr></table>";
echo "<form action='change_user_info.php' method='post'><input type='hidden' id='user_id' name='user_id' value='$sel_user_id'><button type='submit'>Change user info</button></form>";
?>
<br><table></table>
<button onclick="document.getElementById('id02').style.display='block'">
<?php 
if ($u_status == 0)
echo "Ban user";
else
echo "Unban user";
?>
</button>
<br><table></table>
<button onclick="document.getElementById('id01').style.display='block'">Delete user</button>
</div>
<div id="id01" class="modal">
  
  <form class="modal-content animate" action="adm_main.php" method="post">
    <div class="imgcontainer">
      <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
    </div>

    <div class="container">
      <p class="ncol" align="center"><b>Are you sure?</b></p>
	  <?php $sel_user_id = $_POST["sel_user_id"];
	  echo"<input type='hidden' id='user_id' name='user_id' value='$sel_user_id'>"; ?>
	  <input type="hidden" id="user_status" name="user_status" value="1">
      <button type="submit">Yes</button>
    </div>
  </form>
</div>
<div id="id02" class="modal">
  
  <form class="modal-content animate" action="adm_main.php" method="post">
    <div class="imgcontainer">
      <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">&times;</span>
    </div>

    <div class="container">
      <p class="ncol" align="center"><b>Are you sure?</b></p>
	  <?php $sel_user_id = $_POST["sel_user_id"];
	  echo"<input type='hidden' id='user_id' name='user_id' value='$sel_user_id'>";
	  if ($u_status == 0) {
		  echo "<input type='hidden' id='user_status' name='user_status' value='3'>";
		  }
	  else {
		  echo "<input type='hidden' id='user_status' name='user_status' value='4'>";
		  }	
	  ?>
      <button type="submit">Yes</button>
    </div>
  </form>
</div>
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
<script>
// Get the modal
var modal = document.getElementById('id02');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
</body>
</html>