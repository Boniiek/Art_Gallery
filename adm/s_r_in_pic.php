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
  <a href="in_users.php">Search in users</a>
  <a class="active" href="in_pictures.php">Search in pictures</a>
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
$db_connection = pg_connect("host=localhost dbname=test user=postgres password=yo_password");
if ($_POST["p_name"] <> " ") {
	$pic_n = $_POST["p_name"];
}
else {
	$pic_n = 'pic_name';
}
if ($_POST["a_name"] <> 0 and $_POST["a_name"] <> " ") {
	$pic_a = $_POST["a_name"];
}
else {
	$pic_a = 'author_id';
}
if ($_POST["c_id"] <> 0 and $_POST["c_id"] <> "") {
	$pic_c = $_POST["c_id"];
}
else {
	$pic_c = 'category_id';
}
if ($_POST["g_id"] <> 0 and $_POST["g_id"] <> "") {
	$pic_g = $_POST["g_id"];
}
else {
	$pic_g = 'genre_id';
}
if ($_POST["year_t1"] <> "") {
	$pic_y1 = $_POST["year_t1"];
}
else {
	$pic_y1 = 'year_taken';
}
if ($_POST["year_t2"] <> "") {
	$pic_y2 = $_POST["year_t2"];
}
else {
	$pic_y2 = 'year_taken';
}
$sort_by = $_POST["sort_by"];
$result = pg_query($db_connection, "SELECT pic_id, pic_name, u_name FROM pictures 
INNER JOIN users ON pictures.author_id = users.user_id WHERE pic_name LIKE '%$pic_n%' AND
u_name LIKE '%$pic_a%' AND category_id = $pic_c AND genre_id = $pic_g AND year_taken BETWEEN $pic_y1 AND $pic_y2 ORDER BY $sort_by;");
$num_r = pg_num_rows($result);
$a = 0;
if ($num_r <> 0) {
	$result_1 = pg_query($db_connection, "SELECT COUNT(*) FROM pictures 
	INNER JOIN users ON pictures.author_id = users.user_id WHERE pic_name LIKE '%$pic_n%' AND
	u_name LIKE '%$pic_a%' AND category_id = $pic_c AND genre_id = $pic_g AND year_taken BETWEEN $pic_y1 AND $pic_y2;");
	$coun = pg_fetch_result($result_1, $a, 0);
	while($a < $coun) {
		$val[$a] = pg_fetch_result($result, $a, 1);
		$names[$a] = pg_fetch_result($result, $a, 2);
		$im[$a] = pg_fetch_result($result, $a, 0);
		$a++;
	}
}
else {
	setcookie('msg', 1, time()+1);
	$_COOKIE["msg"] = 1;
	echo "<script>window.location = 'in_pictures.php'</script>";
}
$a = 0;
?>
<script>
var i=0;
var arr = <?php echo json_encode($val); ?>;
var k = <?php echo json_encode($im); ?>;
var coun = <?php echo $coun; ?>;
var a_name = <?php echo json_encode($names); ?>;
while (i<coun) {
  
  document.write('<div class="grid-item" id="' + k[i] + '"><form action="adm_pic_view.php" method="post"><input type="hidden" id="sel_pic_id" name="sel_pic_id" value="' + k[i] + '"><button type="submit" class="expand" id="' + k[i] + '"><img src="../images/' + k[i] + '.jpg" height="190px" width="90%" border="1px" alt="" /><div class="overlay"><p class="ncol">' + arr[i] + ' ' + 'by ' + a_name[i] + '</p></div></button></form></div>');
  i++;
}
</script>
</div>
</body>
</html>