<h3>Panel administracyjny</h3>

<p>
<?php
if ($_SESSION['isAdmin']) {
?>
<center><br/>Co chcesz dodać/edytować/usunąć??<br/><br/><br/>
<div id="admin_pan">
<table id="clear">
<tr><td><a href="index.php?category=admin/news">Aktualności</a></td>
<td><a href="index.php?category=admin/task">Zadania</a></td></tr>
<tr><td><a href="index.php?category=admin/users">Użytkownicy</a></td>
<td><a href="index.php?category=admin/taskIO">Dane do zadań</a></td></tr>
<tr><td><a href="index.php?category=admin/groups_wt">Grupy</a></td>
<td><a href="index.php?category=admin/langs">Języki programowania</a></td></tr>
</table>
</div></center>
<?php
} else {
?>
Nie masz uprawnien do przeglądania tej strony
<?php } ?>
</p>



