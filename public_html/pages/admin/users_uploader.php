<h3>Utwórz wielu użytkowników</h3>

<?php
$ok=1;
$ext=strrchr($_FILES["uploadedfile"]["name"], ".");
$database = connectDatabase();
if (!$database)	{	include('php/database_fail.php');	}
else
{
	if ($_FILES["uploadedfile"]["size"] > 524288)
	{
		echo "<p>Plik jest zbyt duży.</p><br />"; 
		$ok=0;
	}
	else if ($ext != ".xls" || strlen($ext) > 4)
	{
		echo "<p>To nie jest plik xls.</p><br />"; 
		$ok=0;
	}
	if ($ok && $_SESSION["users_add"] != 2)
	{
		$target_path = "../uploads/";
		$target_path = $target_path.$_FILES["uploadedfile"]["name"]; 
		if (move_uploaded_file($_FILES["uploadedfile"]["tmp_name"], $target_path))
		{
		
		$mess1 = "Twoje konto na serwisie spoj-clone jest już aktywne. Dane do pierwszego logowania:\n\nLogin: ";
		$mess2 = " (numer twojego indeksu)\nHasło: ";
		$mess3 = "\n\nNie zapomnij o stworzeniu własnego loginu po pierwszym logowaniu.\n\n--------------------\n".
					"Życzymi miłego rozwiązywania\nspoj-clone development team\n";
		$headers = 'From: Spoj-Clone_admin@213.184.8.82'."\r\n".'Reply-To: Spoj-Clone_admin@213.184.8.82'."\r\n";
		
			$_SESSION['users_add'] = 2;
			exec('sh -c "'."../studentList '../uploads/".$_FILES["uploadedfile"]["name"]."'".'"', $output, $ret);
			
			$group_id = 0;
			if ($_POST['group_ex'] != '0')
				{	$group_id = $_POST['group_ex'];	}
			else if (isset($_POST['group_new']))
			{
				$result = $database->query('SELECT id FROM groups WHERE name = "'.substr($_POST['group_new'], 0, 30).'";');
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					$group_id = $row['id'];
				}
				else
				{
					$database->query('INSERT groups (name) VALUES ("'.substr($_POST['group_new'], 0, 30).'");');
					$group_id = $database->insert_id;
				}
			}

			if ($ret % 100 == 0)
			{
				$size = count($output);
				if ($ret != 0)
				{
					echo '<table cellpadding="0" cellspacing="0" id="users_added">'."\n";
					echo '<tr><th colspan="4">Wybranych użytkownikow nie można było dodać:</th></tr>'."\n";
					$ii = 0;
					while ($ii < $size)
					{
						if ($output[$ii+4] == "1")
						{
							echo '<tr><td id="usr_index">'.$output[$ii].
								'</td><td id="usr_name">'.$output[$ii+1].
								'</td><td id="usr_surname">'.$output[$ii+2].
								'</td><td id="usr_mail">'.$output[$ii+3].'</td></tr>'."\n";
							echo '<tr><td id="err_msg" colspan="4">'.$output[$ii+5].'</td></tr>'."\n";
							if ($group_id > 0 && $output[$ii+6] != 0)
								{	$database->query('insert projekt5.user_to_group (group_id, user_id) values ('.$group_id.', '.$output[$ii+6].');');	}
						}
						$ii+=7;
					}
					echo '</table><br/><br/>'."\n";
				}
				if ($ret != 200)
				{
					echo '<table cellpadding="0" cellspacing="0" id="users_added">'."\n";
					echo '<tr><th colspan="4">Użytkownicy dodani:</th></tr>'."\n";
					$ii = 0;
					while ($ii < $size)
					{
						if ($output[$ii+4] == "0")
						{
							echo '<tr><td id="usr_ok_index">'.$output[$ii].
								'</td><td id="usr_ok_name">'.$output[$ii+1].
								'</td><td id="usr_ok_surname">'.$output[$ii+2].
								'</td><td id="usr_ok_mail">'.$output[$ii+3].'</td></tr>'."\n";
							mail($output[$ii+3], 'Witamy na spoj-clone [Dominatrix 2000]!!!', wordwrap($mess1.$output[$ii].$mess2.$output[$ii+5].$mess3, 70), $headers);
							if ($group_id > 0)
								{	$database->query('insert projekt5.user_to_group (group_id, user_id) values ('.$group_id.', '.$output[$ii+6].');');	}
						}
						$ii+=7;
					}
					echo '</table>'."\n";
				}
			}
		}
		else
		{
			echo "<p>Wystąpił bład podczas wczytywania pliku!</p><br />";
		}
	}
?>
<br/><hr/><br/><center>
<a id="topper" href="index.php?category=admin/users">&#8592; Wróć</a>
<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>
</center>
<?php
}