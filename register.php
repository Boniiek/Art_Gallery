<?php
if (isset($_COOKIE["lang"])) {
}
else {
	setcookie('lang', 0, time()+60*30);
	$_COOKIE["lang"] = 0;
}
?>
<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
<link rel="stylesheet" href="g_styles.css">
</head>
<body>
<a style="text-decoration:none" href="index.php" ><h2 class="example" align="center"><span lang="en">Art Gallery</span><span lang="ru">Галерея Творчества</span></h2></a>
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
<form action="index.php" method="post">
  <div class="container">
    <h1><span lang="en">Register</span><span lang="ru">Регистрация</span></h1>
    <p><span lang="en">Please fill in this form to create an account.</span><span lang="ru">Пожалуйста заполните форму ниже для того, чтобы создать аккаунт.</span></p>
    <hr>

    <label for="uname"><b><span lang="en">Name</span><span lang="ru">Имя</span></b></label>
    <input type="text" placeholder="Enter Name" name="name" id="name" maxlength="30" required>
	
    <label for="uemail"><b><span lang="en">Email: </span><span lang="ru">Адрес электронной почты</span></b></label>
    <input type="email" placeholder="Enter Email" name="email" id="email" maxlength="50" required><br><hr>

    <label for="psw"><b><span lang="en">Password</span><span lang="ru">Пароль</span></b></label>
    <input type="password" placeholder="Enter Password" name="psw" id="psw" maxlength="30" required>
	<label for="udate"><b><span lang="en">Birth date:</span><span lang="ru">Дата рождения</span></b></label>
    <input type="date" placeholder="Enter Date" name="date" id="date" min="1922-01-01" max="2004-01-01" required >
	<br><hr>
	<label for="cbox"><b><span lang="en">Author?</span><span lang="ru">Автор</span></b></label>
	<input type="hidden" name="author" value="0" />
    <input type="checkbox" placeholder="author" name="author" id="author" value="1">
	  
    <hr>
    <input type="hidden" id="u_status" name="u_status" value="1">

    <button type="submit" class="registerbtn"><span lang="en">Register</span><span lang="ru">Зарегистрироваться</span></button>
  </div>
  
  <div class="container signin">
    <p><span lang="en">Already have an account? </span><span lang="ru">Уже имете аккаунт? </span><a href="index.php"><span lang="en">Sign in</span><span lang="ru">Войти</span></a>.</p>
  </div>
</form>

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
