<?php
$db_connection = pg_connect("host=localhost dbname=test user=postgres password=yo_password");
if (isset($_COOKIE["sadm_login"])) {
}
else {
	echo "<script>window.location = 'adm_page.php'</script>";
}
if (isset($_POST["sadm_status"])) {
	if ($_POST["sadm_status"] == 0) {
		setcookie('sadm_login', 0, time()+60*30);
		$_COOKIE["sadm_login"] = 0;
	}
	// Delete adm
	if ($_POST["sadm_status"] == 1) {
		$d_to_d = array('adm_id' => $_POST["sel_adm_id"]);
		pg_delete($db_connection, 'administrators', $d_to_d);
		setcookie('msg', 1, time()+1);
		$_COOKIE["msg"] = 1;
		echo "<script>window.location = 'sadm_main.php'</script>";
	}
    // Add new adm
	if ($_POST["sadm_status"] == 2) {
		$adm_name = $_POST["name"];
		$adm_psw = password_hash($_POST["psw"], PASSWORD_DEFAULT);
		$result = pg_query($db_connection, "SELECT adm_name FROM administrators WHERE adm_name = '$adm_name'");
		$num_r = pg_num_rows($result);
		if ($num_r == 0) {
			$d_to_u = array(
				  'adm_name' => $adm_name,
				  'adm_password' => $adm_psw);
			pg_insert($db_connection, 'administrators', $d_to_u);
			setcookie('msg', 2, time()+1);
			$_COOKIE["msg"] = 2;
			echo "<script>window.location = 'sadm_main.php'</script>";
		}
		else {
			setcookie('msg', 3, time()+1);
		    $_COOKIE["msg"] = 3;
			echo "<script>window.location = 'sadm_main.php'</script>";
		}
	
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../g_styles.css">
</head>
<body>
<a style="text-decoration:none" href="sadm_main.php" ><h2 class="example" align="center">Art Gallery</h2></a>
<div id="navbar">
  <a class="active" href="sadm_main.php">All Users</a>
  <div class="navbar">
  <div class="log_in_and_reg">
  <table><td><a>superadm</a></td>
  <td><form action="adm_page.php" method="post"><input type="hidden" id="sadm_status" name="sadm_status" value=0>
  <button type="submit">Log out</button></form></td>
  <td><button onclick="document.getElementById('id01').style.display='block'">Add User</button></td></table>
  </div>
</div>
</div>
<?php
if (isset($_COOKIE["msg"])) {
	if ($_COOKIE["msg"] == 1) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>Admin deleted</p></div>";
	}
	if ($_COOKIE["msg"] == 2) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>Admin created</p></div>";
	}
	if ($_COOKIE["msg"] == 3) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>Admin with this name already exist</p></div>";
	}
}
?>
<div id="id01" class="modal">
  
  <form class="modal-content animate" action="sadm_main.php" method="post">
    <div class="imgcontainer">
      <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
    </div>

    <div class="container">
      <label for="name"><b>Login</b></label>
      <input type="text" placeholder="Enter login" name="name" required>

      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="psw" required>
      <input type="hidden" id="sadm_status" name="sadm_status" value="2">
      <button type="submit">Register</button>
    </div>
  </form>
</div>
<div class="grid-container1">
<?php
$db_connection = pg_connect("host=localhost dbname=test user=postgres password=yo_password");
$result = pg_query($db_connection, "SELECT adm_id, adm_name FROM administrators;");
$num_r = pg_num_rows($result);
$a = 0;
if ($num_r <> 0) {
	$result_1 = pg_query($db_connection, "SELECT COUNT(*) FROM administrators;");
	$coun = pg_fetch_result($result_1, $a, 0);
	while($a < $coun) {
		$id[$a] = pg_fetch_result($result, $a, 0);
		$name[$a] = pg_fetch_result($result, $a, 1);
		$a++;
	}
}
$a = 0;
?>
<script>
var i=0;
var adm_id = <?php echo json_encode($id); ?>;
var adm_name = <?php echo json_encode($name); ?>;
var coun = <?php echo $coun; ?>;
while (i<coun) {
  
  document.write('<div class="grid-item" id="' + adm_id[i] + '"><table><td><p class="ncol">' + adm_name[i] + '</p></td><td><form action="sadm_main.php" method="post"><input type="hidden" id="sadm_status" name="sadm_status" value=1><input type="hidden" id="sel_adm_id" name="sel_adm_id" value="' + adm_id[i] + '"><button type="submit" style="text-align: start" id="' + adm_id[i] + '">Delete</button></form></td></table></div>');
  i++;
}
</script>
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