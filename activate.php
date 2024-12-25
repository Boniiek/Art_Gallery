<?php
$db_connection = pg_connect("host=localhost dbname=test user=postgres password=yo_password");
//registration
$user_email = $_GET["email"];
$act_code = $_GET["act_code"];
$result = pg_query($db_connection, "SELECT act_code, act_exp FROM tmp_users WHERE u_email='$user_email'");
$num_r = pg_num_rows($result);
if ($num_r <> 0) {
	$act_code_r = pg_fetch_result($result, 0, 0);
	$act_exp = pg_fetch_result($result, 0, 1);
	$c_date = date('Y-m-d H:i:s', time());
	if ($act_code == $act_code_r and $c_date < $act_exp) {
		$result = pg_query($db_connection, "SELECT u_name, u_password, u_date, author FROM tmp_users WHERE u_email='$user_email'");
		$u_name = pg_fetch_result($result, 0, 0);
		$u_password = pg_fetch_result($result, 0, 1);
		$u_date = pg_fetch_result($result, 0, 2);
		$author = pg_fetch_result($result, 0, 3);
		setcookie('login', 1, time()+60*30);
		$_COOKIE["login"] = 1;
		setcookie('uname', $u_name, time()+60*30);
		$_COOKIE["uname"] = $u_name;
		setcookie('author', $author, time()+60*30);
		$_COOKIE["author"] = $author;
		$tmp = array(
		'u_name' => $u_name,
		'u_email' => $user_email,
		'u_password' => $u_password,
		'u_date' => $u_date,
		'author' => $author
		);
		pg_insert($db_connection, 'users', $tmp);
		$result = pg_query($db_connection, "SELECT user_id FROM users WHERE u_email='$user_email'");
		$user_id = pg_fetch_result($result, 0, 0);
		setcookie('u_id', $user_id, time()+60*30);
		$_COOKIE["u_id"] = $user_id;
		$d_to_d = array('u_email' => $user_email);
		pg_delete($db_connection, 'tmp_users', $d_to_d);
		echo "<script>window.location = 'index.php'</script>";
	}
	else {
		$d_to_d = array('u_email' => $user_email);
		pg_delete($db_connection, 'tmp_users', $d_to_d);
		setcookie('msg', 3, time()+1);
	    $_COOKIE["msg"] = 3;
		echo "<script>window.location = 'index.php'</script>";
	}
}
else {
	setcookie('msg', 4, time()+1);
	$_COOKIE["msg"] = 4;
	echo "<script>window.location = 'index.php'</script>";
}
?>