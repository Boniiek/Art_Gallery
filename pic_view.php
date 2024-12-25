<?php
$db_connection = pg_connect("host=localhost dbname=test user=postgres password=yo_password");
setcookie('pic_tmp_id', 0, time()+60*30);
if (isset($_POST["u_status"])) {
	//log out
	if ($_POST["u_status"] == 0) {
		setcookie('login', $_POST["u_status"], time()+60*30);
		$_COOKIE["login"] = $_POST["u_status"];
		if (isset($_COOKIE["uname"])) {
			unset($_COOKIE["uname"]); 
			setcookie("uname", null, time() - 3600, '/');
		}
		if (isset($_COOKIE["author"])) {
			unset($_COOKIE["author"]); 
			setcookie("author", null, time() - 3600, '/');
		}
		if (isset($_COOKIE["u_id"])) {
			unset($_COOKIE["u_id"]); 
			setcookie("u_id", null, time() - 3600, '/');
		}
	}
	//log in
	if ($_POST["u_status"] == 2) {
		$user_email = $_POST["email"];
		$user_password = $_POST["psw"];
		$result = pg_query($db_connection, "SELECT u_email, u_password, u_name, author, user_id, u_status FROM users WHERE u_email='$user_email'");
		$num_r = pg_num_rows($result);
		if ($num_r <> 0) {
			$user_password_r = pg_fetch_result($result, 0, 1);
			$user_status = pg_fetch_result($result, 0, 5);
			if(password_verify($user_password, $user_password_r) and $user_status == 0) {
				setcookie('login', 1, time()+60*30);
				$_COOKIE["login"] = 1;
				$uname = pg_fetch_result($result, 0, 2);
				setcookie('uname', $uname, time()+60*30);
				$_COOKIE["uname"] = $uname;
				$author = pg_fetch_result($result, 0, 3);
				setcookie('author', $author, time()+60*30);
				$_COOKIE["author"] = $author;
				$user_id = pg_fetch_result($result, 0, 4);
				setcookie('u_id', $user_id, time()+60*30);
				$_COOKIE["u_id"] = $user_id;
			}
			else {
				setcookie('msg', 2, time()+1);
				$_COOKIE["msg"] = 2;
			    echo "<script>window.location = 'index.php'</script>";
			}
		}
		else {
			setcookie('msg', 2, time()+1);
			$_COOKIE["msg"] = 2;
		    echo "<script>window.location = 'index.php'</script>";
		}
	}
}
else {
	if (isset($_COOKIE["login"])) {
		if ($_COOKIE["login"] == 1) {
			$u_id = $_COOKIE["u_id"];
			$result = pg_query($db_connection, "SELECT u_status FROM users WHERE user_id='$u_id'");
			$num_r = pg_num_rows($result);
		    if ($num_r <> 0) {
				$u_status = pg_fetch_result($result, 0, 0);
				if ($u_status == 1) {
					setcookie('login', 0, time()+60*30);
					$_COOKIE["login"] = 0;
				}
			}
			else {
				setcookie('login', 0, time()+60*30);
				$_COOKIE["login"] = 0;
			}
		}
	}
	else {
		setcookie('login', 0, time()+60*30);
		$_COOKIE["login"] = 0;
	}
}
if (isset($_COOKIE["lang"])) {
}
else {
	setcookie('lang', 0, time()+60*30);
	$_COOKIE["lang"] = 0;
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="g_styles.css">
</head>
<body>
<a style="text-decoration:none" href="index.php" ><h2 class="example" align="center"><span lang="en">Art Gallery</span><span lang="ru">Галерея Творчества</span></h2></a>
<div id="navbar">
  <a class="active" href="index.php"><span lang="en">Home</span><span lang="ru">Главная</span></a>
  <a href="paintings.php"><span lang="en">Paintings</span><span lang="ru">Картины</span></a>
  <a href="photos.php"><span lang="en">Photos</span><span lang="ru">Фотографии</span></a>
  <a href="drawings.php"><span lang="en">Drawings</span><span lang="ru">Рисунки</span></a>
  <a href="upload.php"><span lang="en">Upload</span><span lang="ru">Загрузка</span></a>
  <a href="search.php"><span lang="en">Search</span><span lang="ru">Поиск</span></a>
  <form>
    <a>
    <select id="lang-switch">
        <?php
        if ($_COOKIE["lang"] == 0) {
			echo "<option value='en' selected>English</option>
			<option value='ru'>Русский</option>";
		}
		if ($_COOKIE["lang"] == 1) {
			echo "<option value='en'>English</option>
			<option value='ru' selected>Русский</option>";
		}
		?>
    </select>
	</a>
</form>
  <div class="navbar">
  <div class="log_in_and_reg">
  <?php
  if ($_COOKIE["login"] == 0) {
	  echo "<table><td><button onclick=document.getElementById('id01').style.display='block' style=width:auto;><span lang='en'>Log in</span><span lang='ru'>Войти</span></button></td>
	  <td><button  onclick=window.location.href='register.php' style=width:auto;><span lang='en'>Register</span><span lang='ru'>Регистрация</span></button></td></table>";
  }
  else {
	  $u_na = $_COOKIE["uname"];
	  echo "<table><td><a>$u_na</a></td><td><form action='index.php' method='post'><input type='hidden' id='u_status' name='u_status' value='0'><button type='submit'><span lang='en'>Log out</span><span lang='ru'>Выйти</span></button></form></td>
	  <td><button  onclick=window.location.href='settings.php'><span lang='en'>Settings</span><span lang='ru'>Настройки</span></button></td></table>";
  }
  ?>
  </div>
</div>
</div>
<div id="id01" class="modal">
  
  <form class="modal-content animate" action="index.php" method="post">
    <div class="imgcontainer">
      <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
    </div>

    <div class="container">
      <label for="uemail"><b><span lang="en">Email</span><span lang="ru">Адрес электронной почты</span></b></label>
      <input type="text" placeholder="Enter Email" name="email" required>

      <label for="psw"><b><span lang="en">Password</span><span lang="ru">Пароль</span></b></label>
      <input type="password" placeholder="Enter Password" name="psw" required>
      <input type="hidden" id="u_status" name="u_status" value="2">
      <button type="submit"><span lang="en">Log in</span><span lang="ru">Войти</span></button>
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

<div class="grid-container">
<?php
$sel_pic_id = $_POST["sel_pic_id"];
$db_connection = pg_connect("host=localhost dbname=test user=postgres password=yo_password");
$result = pg_query($db_connection, "SELECT pic_name, u_name, category_name, genre_name, year_taken, date_posted FROM pictures
INNER JOIN genre ON pictures.genre_id = genre.genre_id INNER JOIN category ON pictures.category_id = category.category_id
INNER JOIN users ON pictures.author_id = users.user_id WHERE pic_id = $sel_pic_id");
$pic_name = pg_fetch_result($result, 0, 0);
$u_name = pg_fetch_result($result, 0, 1);
$category = pg_fetch_result($result, 0, 2);
$genre = pg_fetch_result($result, 0, 3);
$year_t = pg_fetch_result($result, 0, 4);
$date_posted = pg_fetch_result($result, 0, 5);
echo "<table><tr><td><img src='images/$sel_pic_id.jpg' height='350px' width='100%' border='1px' alt='' /></td></tr><tr><td><p class='ncol' align='center'>$pic_name</p></td></tr></table>";
echo "<table> <tr><td><p class='ncol'><span lang='en'>Author: </span><span lang='ru'>Автор: </span>$u_name</p></td></tr><hr>";
echo "<tr><td><p class='ncol'><span lang='en'>Category: </span><span lang='ru'>Категория: </span>$category</p></td></tr>";
echo "<tr><td><p class='ncol'><span lang='en'>Genre: </span><span lang='ru'>Жанр: </span>$genre</p></td></tr>";
echo "<tr><td><p class='ncol'><span lang='en'>Year: </span><span lang='ru'>Год: </span>$year_t</p></td></tr>";
echo "<tr><td><p class='ncol'><span lang='en'>Date posted: </span><span lang='ru'>Дата публикации: </span>$date_posted</p></td></tr></table>";
?>
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

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script>
$('[lang]').hide(); // hide all lang attributes on start.
let xm = document.cookie;
var y = xm.match(/lang=(\d+)/i)[1];
if (y == 0) {
	$('[lang="en"]').show();
}
if (y == 1) {
	$('[lang="ru"]').show();
}

$('#lang-switch').change(function () { // put onchange event when user select option from select
    var lang = $(this).val(); // decide which language to display using switch case
    switch (lang) {
        case 'en':
            $('[lang]').hide();
            $('[lang="en"]').show();
			 document.cookie = "lang=0";
        break;
        case 'ru':
            $('[lang]').hide();
            $('[lang="ru"]').show();
			 document.cookie = "lang=1";
        break;
        default:
		    $('[lang]').hide();
		    let xm = document.cookie;
		    var y = xm.match(/lang=(\d+)/i)[1];
			if (y == 0) {
                $('[lang="en"]').show();
			}
			if (y == 1) {
                $('[lang="ru"]').show();
			}
        }
});
</script>

</body>
</html>