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
<form action="adm_main.php" method="post">
  <div class="container">
  <h3>Image info</h3>
  <hr>
  <label for="p_name"><b>Name</b></label>
  <input type="text" placeholder="Enter Name" name="p_name" id="p_name">
  <hr>
  <table>
  <td>
  <label for="c_id"><b>Category: </b></label>
  </td>
  <td>
  <div class="custom-select" style="width:230px;">
  <select id="c_id" name="c_id">
    <option value="0">Select category</option>
    <option value="1">Photo</option>
    <option value="2">Painting</option>
    <option value="3">Drawing</option>
  </select>
  </div>
  </td>
  </table>
  <hr>
  <table>
  <td>
  <label for="g_id"><b>Genre: </b></label>
  </td>
  <td>
  <div class="custom-select" style="width:200px;">
  <select id="g_id" name="g_id">
    <option value="0">Select genre</option>
    <option value="1">People</option>
    <option value="2">Nature</option>
    <option value="3">Animals</option>
	<option value="4">Characters</option>
    <option value="5">History</option>
    <option value="6">Architecture</option>
	<option value="7">Other</option>
  </select>
  </div>
  </td>
  </table>
  <hr>
  <label for="year_t"><b>Year:</b></label>
  <input type="text" placeholder="Enter Year" name="year_t" id="year_t">
  <hr>
  <?php $img_id = $_POST["img_id"];
  echo "<input type='hidden' id='img_id' name='img_id' value='$img_id'>"; ?>
  <input type="hidden" id="img_status" name="img_status" value="2">
  <button type="submit" class="registerbtn">Save changes</button>
  </div>
  </form>
<script>
var x, i, j, l, ll, selElmnt, a, b, c;
/*look for any elements with the class "custom-select":*/
x = document.getElementsByClassName("custom-select");
l = x.length;
for (i = 0; i < l; i++) {
  selElmnt = x[i].getElementsByTagName("select")[0];
  ll = selElmnt.length;
  /*for each element, create a new DIV that will act as the selected item:*/
  a = document.createElement("DIV");
  a.setAttribute("class", "select-selected");
  a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
  x[i].appendChild(a);
  /*for each element, create a new DIV that will contain the option list:*/
  b = document.createElement("DIV");
  b.setAttribute("class", "select-items select-hide");
  for (j = 1; j < ll; j++) {
    /*for each option in the original select element,
    create a new DIV that will act as an option item:*/
    c = document.createElement("DIV");
    c.innerHTML = selElmnt.options[j].innerHTML;
    c.addEventListener("click", function(e) {
        /*when an item is clicked, update the original select box,
        and the selected item:*/
        var y, i, k, s, h, sl, yl;
        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
        sl = s.length;
        h = this.parentNode.previousSibling;
        for (i = 0; i < sl; i++) {
          if (s.options[i].innerHTML == this.innerHTML) {
            s.selectedIndex = i;
            h.innerHTML = this.innerHTML;
            y = this.parentNode.getElementsByClassName("same-as-selected");
            yl = y.length;
            for (k = 0; k < yl; k++) {
              y[k].removeAttribute("class");
            }
            this.setAttribute("class", "same-as-selected");
            break;
          }
        }
        h.click();
    });
    b.appendChild(c);
  }
  x[i].appendChild(b);
  a.addEventListener("click", function(e) {
      /*when the select box is clicked, close any other select boxes,
      and open/close the current select box:*/
      e.stopPropagation();
      closeAllSelect(this);
      this.nextSibling.classList.toggle("select-hide");
      this.classList.toggle("select-arrow-active");
    });
}
function closeAllSelect(elmnt) {
  /*a function that will close all select boxes in the document,
  except the current select box:*/
  var x, y, i, xl, yl, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  xl = x.length;
  yl = y.length;
  for (i = 0; i < yl; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < xl; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}
/*if the user clicks anywhere outside the select box,
then close all select boxes:*/
document.addEventListener("click", closeAllSelect);
</script>
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
</body>
</html>