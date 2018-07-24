<div id="left_bar">
<div id="navigation">
<h1>Menu:</h1>
	<a href="index.php">Home</a>
	<a href="index.php?category=about">O stronie</a>
	<a href="index.php?category=tutorial">Tutorial</a>
	<a href="index.php?category=account">Moje konto</a>
	<a href="index.php?category=tasks">Zadania</a>
	<a href="index.php?category=sendSolution">Wyślij rozwiązanie</a>
	<a href="index.php?category=user/mysolutions">Rozwiązania</a>
	<a href="index.php?category=ranking">Ranking</a>
	<a href="index.php?category=faq">FAQ</a>
	<a href="index.php?category=forum">Forum</a>
	<?php if ($_SESSION['isAdmin']) { ?>
	<a id="admin" href="index.php?category=admin/adminPanel">Administracja</a>
	<?php } ?>
</div>

</div>
<div id="site">
