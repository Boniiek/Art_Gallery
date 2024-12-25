<?php
$db_connection = pg_connect("host=localhost dbname=test user=postgres password=yo_password");
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
	//registration
	if ($_POST["u_status"] == 1) {
		$user_email = $_POST["email"];
		$result = pg_query($db_connection, "SELECT * FROM users WHERE u_email='$user_email'");
		$num_r = pg_num_rows($result);
		if ($num_r == 0) {
			$app_url = 'http://localhost/project';
			$activation_code = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
			// create the activation link
			$activation_link = $app_url . "/activate.php?email=$user_email&act_code=$activation_code";
			$tmp = array(
			'u_name' => $_POST["name"],
			'u_email' => $_POST["email"],
			'u_password' => password_hash($_POST["psw"], PASSWORD_DEFAULT),
			'u_date' => $_POST["date"],
			'author' => $_POST["author"],
			'act_code' => $activation_code
			);
			pg_insert($db_connection, 'tmp_users', $tmp);
			$mymail = fopen("mymail.txt", "w") or die("Unable to open file!");
			$txt = "Subject: Activate account \r\n";
			fwrite($mymail, $txt);
			$txt = 'Hi, Please go to the following url to activate your account: ' . $activation_link;
			fwrite($mymail, $txt);
			fclose($mymail);
			setcookie('msg', 1, time()+1);
		    $_COOKIE["msg"] = 1;
			if (isset($_COOKIE["login"])) {
				
			}
			else {
				setcookie('login', 0, time()+60*30);
				$_COOKIE["login"] = 0;
			}
		}
		else {
			setcookie('msg', 4, time()+1);
			$_COOKIE["msg"] = 4;
			echo "<script>window.location = 'index.php'</script>";
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
if (isset($_POST["up_author"])) {
	if ($_POST["author"] == 1) {
		setcookie('author', 1, time()+60*30);
		$_COOKIE["author"] = 1;
		$d_to_u = array('author' => 1);
        $cond = array('user_id' => $_COOKIE["u_id"]);
		pg_update($db_connection, 'users', $d_to_u, $cond);
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
<?php
if (isset($_COOKIE["msg"])) {
	if ($_COOKIE["msg"] == 1) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>
		<span lang='en'>Please check mymail.txt file in project folder in order to complete registration process</span><span lang='ru'>Пожалуйста проверьте файл mymail.txt для того чтобы завершить регистрацию</span></p></div>";
	}
	if ($_COOKIE["msg"] == 2) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>
		<span lang='en'>Wrong email or password</span><span lang='ru'>Неправильный email или пароль</span></p></div>";
	}
	if ($_COOKIE["msg"] == 3) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>
		<span lang='en'>The activation code had expired or incorrect</span><span lang='ru'>Срок кода активации истек или неправильный</span></p></div>";
	}
	if ($_COOKIE["msg"] == 4) {
		echo "<div class='alert'><p class='ncol'><span class='closebtn' onclick=this.parentElement.style.display='none';>&times;</span>
		<span lang='en'>Wrong email</span><span lang='ru'>Неправильный email</span></p></div>";
	}
}
?>
<table width="95%" align="center">
<td align="left" width="70%">
<div class="slideshow-container">
<div class="mySlides fade">
<img src="images/1.jpg" height="300px" width="100%" border="1px" alt="">
</div>

<div class="mySlides fade">
  <img src="images/2.jpg" height="300px" width="100%" border="1px" alt="">
</div>

<div class="mySlides fade">
  <img src="images/3.jpg" height="300px" width="100%" border="1px" alt="">
</div>
<!--
<a class="prev" onclick="plusSlides(-1)">❮</a>
<a class="next" onclick="plusSlides(1)">❯</a>-->
<br>
<div style="text-align:center">
  <span class="dot" onclick="currentSlide(1)"></span> 
  <span class="dot" onclick="currentSlide(2)"></span> 
  <span class="dot" onclick="currentSlide(3)"></span> 
</div>
</div>
</td>
<td><p class="ncol"><span lang="en">Welcome to Art Gallery!
A place where you can find a lot of artwork from famous artists!</span><span lang="ru">Добро пожаловать в Галерею Творчества!
Здесь вы можете найти большое количество работ от известных художников!</span></p></td>
</table>
<script>
let slideIndex = 0;
showSlides();

function showSlides() {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}    
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
  setTimeout(showSlides, 5000); // Change image every 5 seconds
}
</script>

<div class="grid-container">
<?php
$db_connection = pg_connect("host=localhost dbname=test user=postgres password=yo_password");
if ($_COOKIE["login"] == 0) {
	$result = pg_query($db_connection, "SELECT pic_name, pic_id, u_name FROM pictures INNER JOIN users ON pictures.author_id = users.user_id ORDER BY pic_id LIMIT 12");
}
else {
	$result = pg_query($db_connection, "SELECT pic_name, pic_id, u_name FROM pictures INNER JOIN users ON pictures.author_id = users.user_id ORDER BY pic_id");
}
$result_1 = pg_query($db_connection, "SELECT COUNT(*) FROM pictures");
$coun = pg_fetch_result($result_1, 0, 0);
if ($coun > 12 and $_COOKIE["login"] == 0) {
	$coun = 12;
}
$a = 0;
while($a < $coun) {
$val[$a] = pg_fetch_result($result, $a, 0);
$names[$a] = pg_fetch_result($result, $a, 2);
$im[$a] = pg_fetch_result($result, $a, 1);
$a++;
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
  
  document.write('<div class="grid-item" id="' + k[i] + '"><form action="pic_view.php" method="post"><input type="hidden" id="sel_pic_id" name="sel_pic_id" value="' + k[i] + '"><button type="submit" class="expand" id="' + k[i] + '"><img src="images/' + k[i] + '.jpg" height="190px" width="90%" border="1px" alt="" /><div class="overlay"><p class="ncol">' + arr[i] + ' ' + 'by ' + a_name[i] + '</p></div></button></form></div>');
  i++;
}
</script>
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