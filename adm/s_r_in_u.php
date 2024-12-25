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
<div class="grid-container1">
<?php
$db_connection = pg_connect("host=localhost dbname=test user=postgres password=yo_password");
if ($_POST["author"] <> "_") {
	$author = $_POST["author"];
}
else {
	$author = 'author';
}
if ($_POST["name"] <> " ") {
	$u_name = $_POST["name"];
}
else {
	$u_name = 'u_name';
}
if ($_POST["date_1"] <> "") {
	$u_date1 = $_POST["date_1"];
}
else {
	$u_date1 = 'u_date';
}
if ($_POST["date_2"] <> "") {
	$u_date2 = $_POST["date_2"];
}
else {
	$u_date2 = 'u_date';
}
if ($_POST["status"] <> "_") {
	$u_status = $_POST["status"];
}
else {
	$u_status = 'u_status';
}
$sort_by = $_POST["sort_by"];
if ($_POST["date_1"] <> "" and $_POST["date_2"] <> "")
{
$result = pg_query($db_connection, "SELECT user_id, u_name, author FROM users WHERE u_name LIKE '%$u_name%' AND
author = $author AND u_date BETWEEN '$u_date1' AND '$u_date2' AND u_status = $u_status ORDER BY $sort_by;");
}
if ($_POST["date_1"] == "" and $_POST["date_2"] == "")
{
$result = pg_query($db_connection, "SELECT user_id, u_name, author FROM users WHERE u_name LIKE '%$u_name%' AND
author = $author AND u_date BETWEEN $u_date1 AND $u_date2 AND u_status = $u_status ORDER BY $sort_by;");
}
if ($_POST["date_1"] <> "" and $_POST["date_2"] == "")
{
$result = pg_query($db_connection, "SELECT user_id, u_name, author FROM users WHERE u_name LIKE '%$u_name%' AND
author = $author AND u_date BETWEEN '$u_date1' AND $u_date2 AND u_status = $u_status ORDER BY $sort_by;");
}
if ($_POST["date_1"] == "" and $_POST["date_2"] <> "")
{
$result = pg_query($db_connection, "SELECT user_id, u_name, author FROM users WHERE u_name LIKE '%$u_name%' AND
author = $author AND u_date BETWEEN $u_date1 AND '$u_date2' AND u_status = $u_status ORDER BY $sort_by;");
}
$num_r = pg_num_rows($result);
$a = 0;
if ($num_r <> 0) {
	if ($_POST["date_1"] <> "" and $_POST["date_2"] <> "") {
		$result_1 = pg_query($db_connection, "SELECT COUNT(*) FROM users WHERE u_name LIKE '%$u_name%' AND
		author = $author AND u_date BETWEEN '$u_date1' AND '$u_date2' AND u_status = $u_status ;");
	}
	if ($_POST["date_1"] == "" and $_POST["date_2"] == "") {
		$result_1 = pg_query($db_connection, "SELECT COUNT(*) FROM users WHERE u_name LIKE '%$u_name%' AND
		author = $author AND u_date BETWEEN $u_date1 AND $u_date2 AND u_status = $u_status ;");
	}
	if ($_POST["date_1"] <> "" and $_POST["date_2"] == "") {
		$result_1 = pg_query($db_connection, "SELECT COUNT(*) FROM users WHERE u_name LIKE '%$u_name%' AND
		author = $author AND u_date BETWEEN '$u_date1' AND $u_date2 AND u_status = $u_status ;");
	}
	if ($_POST["date_1"] == "" and $_POST["date_2"] <> "") {
		$result_1 = pg_query($db_connection, "SELECT COUNT(*) FROM users WHERE u_name LIKE '%$u_name%' AND
		author = $author AND u_date BETWEEN $u_date1 AND '$u_date2' AND u_status = $u_status ;");
	}
	$coun = pg_fetch_result($result_1, $a, 0);
	while($a < $coun) {
		$id[$a] = pg_fetch_result($result, $a, 0);
		$name[$a] = pg_fetch_result($result, $a, 1);
		$author[$a] = pg_fetch_result($result, $a, 2);
		$a++;
	}
}
else {
	setcookie('msg', 1, time()+1);
	$_COOKIE["msg"] = 1;
	echo "<script>window.location = 'in_users.php'</script>";
}
$a = 0;
?>
<script>
var i=0;
var u_id = <?php echo json_encode($id); ?>;
var u_name = <?php echo json_encode($name); ?>;
var coun = <?php echo $coun; ?>;
var author = <?php echo json_encode($author); ?>;
while (i<coun) {
  
  document.write('<div class="grid-item" id="' + u_id[i] + '"><form action="adm_user_view.php" method="post"><input type="hidden" id="sel_user_id" name="sel_user_id" value="' + u_id[i] + '"><button type="submit" class="expand" style="text-align: start" id="' + u_id[i] + '"><p class="ncol">' + u_name[i] + ' ' + ' - ' + author[i] + '</p></button></form></div>');
  i++;
}
</script>
</div>
</body>
</html>