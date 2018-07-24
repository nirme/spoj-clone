	<table id="style" cellpadding="0" cellspacing="0"><tr>
	<td>&nbsp;style:&nbsp;</td>
	<?php if ($_SESSION['style'] != "new.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new.css"/>
	<input id="style-niebieski" type="submit" value="niebieski"/></form></td>
	<?php } ?>

	<?php if ($_SESSION['style'] != "new-eozyna.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new-eozyna.css"/>
	<input id="style-eozyna" type="submit" value="eozyna"/></form></td>
	<?php } ?>

	<?php if ($_SESSION['style'] != "new-morskazielen.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new-morskazielen.css"/>
	<input id="style-morskazielen" type="submit" value="morska zieleń"/></form></td>
	<?php } ?>

	<?php if ($_SESSION['style'] != "new-turkusowy.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new-turkusowy.css"/>
	<input id="style-turkusowy" type="submit" value="turkusowy"/></form></td>
	<?php } ?>

	<?php if ($_SESSION['style'] != "new-stalowy.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new-stalowy.css"/>
	<input id="style-stalowy" type="submit" value="stalowy"/></form></td>
	<?php } ?>

	<?php if ($_SESSION['style'] != "new-rubinowy.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new-rubinowy.css"/>
	<input id="style-rubinowy" type="submit" value="rubinowy"/></form></td>
	<?php } ?>

	<?php if ($_SESSION['style'] != "new-zloty.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new-zloty.css"/>
	<input id="style-zloty" type="submit" value="złoty"/></form></td>
	<?php } ?>

	<?php if ($_SESSION['style'] != "new-srebrny.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new-srebrny.css"/>
	<input id="style-srebrny" type="submit" value="srebrny"/></form></td>
	<?php } ?>

	<?php if ($_SESSION['style'] != "new-lazurowy.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new-lazurowy.css"/>
	<input id="style-lazurowy" type="submit" value="lazurowy"/></form></td>
	<?php } ?>

	<?php if ($_SESSION['style'] != "new-patynowy.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new-patynowy.css"/>
	<input id="style-patynowy" type="submit" value="patynowy"/></form></td>
	<?php } ?>

	<?php if ($_SESSION['style'] != "new-ametystowy.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new-ametystowy.css"/>
	<input id="style-ametystowy" type="submit" value="ametystowy"/></form></td>
	<?php } ?>

	<?php if ($_SESSION['style'] != "new-ugier.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new-ugier.css"/>
	<input id="style-ugier" type="submit" value="ugier"/></form></td>
	<?php } ?>

	<?php if ($_SESSION['style'] != "new-biskupi.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new-biskupi.css"/>
	<input id="style-biskupi" type="submit" value="biskupi"/></form></td>
	<?php } ?>

	<?php if ($_SESSION['style'] != "new-siarkowy.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new-siarkowy.css"/>
	<input id="style-siarkowy" type="submit" value="siarkowy"/></form></td>
	<?php } ?>

	<?php if ($_SESSION['style'] != "new-mysi.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new-mysi.css"/>
	<input id="style-mysi" type="submit" value="mysi"/></form></td>
	<?php } ?>

	<?php if ($_SESSION['style'] != "new-limonkowy.css")	{ ?>
	<td><form action="index.php?category=<?php echo $category; ?>" method="post">
	<input type="hidden" name="style" value="new-limonkowy.css"/>
	<input id="style-limonkowy" type="submit" value="limonkowy"/></form></td>
	<?php } ?>

	</tr></table>
