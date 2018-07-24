<h1>Wyślij rozwiązanie</h1>
<center><br/>
<?php
$database = connectDatabase();

if (!$database)
	{	include('php/database_fail.php');	}
else
{
	$ok_flag = false;
	if (isset($_POST['solution_button']))
	{
		if (!$_SESSION['solution'])
		{
			echo "Odświerzanie jest zablokowane na tej stronie...<br/>\n";
			echo "<a href=\"index.php?category=sendSolution\">Wróć na poprednią stronę</a> jeśli chcesz spróbować jescze raz.<br/>\n";
		}
		else if (!empty($_POST['solution_text']))
		{
			$database->query('INSERT solutions (user_id, task_id, make_date, solution, lang_id) values 
			('.$_SESSION['userId'].', '.$_POST['task_id'].', NOW(), "'.addslashes($_POST['solution_text']).
			'", '.$_POST['lang_id'].');');
			echo "Rozwiązanie zostało wysłane.<br/>\n";
			$solution_id = mysqli_insert_id($database);

			$result = $database->query('SELECT compile_string, file_format FROM languages WHERE id = '.$_POST['lang_id'].' ;');
			$row = $result->fetch_array();

			$solFileName = "../solutions/test_sol_"."$solution_id".substr($row['file_format'], 0, strpos($row['file_format'], "\n"));
			$exeFileName = "../solutions/test_sol_"."$solution_id";
			$solutionFile = fopen($solFileName, 'w');
			fwrite($solutionFile, $_POST['solution_text']);
			fclose($solutionFile);
			exec(preg_replace('/%2/', $exeFileName, preg_replace('/%1/', $solFileName, $row['compile_string'])), $output, $return);
			if ($return)
				{	$database->query('UPDATE solutions SET error = "COMPILATION_ERROR", error_str = "'.addslashes($output).'" WHERE id = '."$solution_id".' ;');	}
			else
				{	$database->query("UPDATE solutions SET error = 'WAIT_FOR_RUN' WHERE id = "."$solution_id"." ;");	}
			unlink($solFileName);
			unlink($exeFileName);
			$ok_flag = true;
		}
		else if ($_FILES["solution_file"]["error"] == UPLOAD_ERR_OK)
		{
			$ext = '/'.strrchr( $_FILES["solution_file"]["name"], '.').'/i';
			@ $result = $database->query("SELECT file_format FROM languages WHERE id=".$_POST['lang_id'].";");
			$row = $result->fetch_assoc();
			$correct_ext = $row['file_format'].".txt";
			if ($_FILES["solution_file"]["size"] > 10240)
			{
				echo "Plik jest za duży.<br/>\n";
				echo "<a href=\"index.php?category=sendSolution\">Wróć na poprednią stronę</a> jeśli chcesz spróbować jescze raz.<br/>\n";
			}
			else if (!preg_match($ext, $correct_ext))
			{
				echo "Wybrany plik nie jest plikiem źródłowym wybranego języka, ani nawet plikiem tekstowym.<br/>\n";
				echo "<a href=\"index.php?category=sendSolution\">Wróć na poprednią stronę</a> jeśli chcesz spróbować jescze raz.<br/>\n";
			}
			else
			{
				$database->query('INSERT solutions (user_id, task_id, make_date, solution, lang_id) values
				('.$_SESSION['userId'].', '.$_POST['task_id'].', NOW(), \''.
				addslashes(file_get_contents($_FILES["solution_file"]["tmp_name"])).'\', '.$_POST['lang_id'].');');
				echo "Plik z rozwiązaniem został wysłany.<br/>\n";
				$solution_id = mysqli_insert_id($database);

				$result = $database->query('SELECT compile_string, file_format FROM languages WHERE id = '.$_POST['lang_id'].' ;');
				$row = $result->fetch_array();

				$solFileName = "../solutions/test_sol_"."$solution_id".substr($row['file_format'], 0, strpos($row['file_format'], "\n"));
				$exeFileName = "../solutions/test_sol_"."$solution_id";
				$solutionFile = fopen($solFileName, 'w');

				fwrite($solutionFile, file_get_contents($_FILES["solution_file"]["tmp_name"]));
				fclose($solutionFile);
				exec(preg_replace('/%2/', $exeFileName, preg_replace('/%1/', $solFileName, $row['compile_string'])), $output, $return);
				if ($return)
					{	$database->query('UPDATE solutions SET error = "COMPILATION_ERROR", error_str = "'.addslashes($output).'" WHERE id = '."$solution_id".' ;');	}
				else
					{	$database->query("UPDATE solutions SET error = 'WAIT_FOR_RUN' WHERE id = "."$solution_id"." ;");	}
				unlink($solFileName);
				unlink($exeFileName);
				$ok_flag = true;
			}
		}
		else if($_FILES["solution_file"]["error"] == UPLOAD_ERR_INI_SIZE)
		{
			echo "Plik jest za duży.<br/>\n";
			echo "<a href=\"index.php?category=sendSolution\">Wróć na poprednią stronę</a> jeśli chcesz spróbować jescze raz.<br/>\n";
		}
		else if($_FILES["solution_file"]["error"] == UPLOAD_ERR_FORM_SIZE)
		{
			echo "Plik jest za duży.<br/>\n";
			echo "<a href=\"index.php?category=sendSolution\">Wróć na poprednią stronę</a> jeśli chcesz spróbować jescze raz.<br/>\n";
		}
		else if($_FILES["solution_file"]["error"] == UPLOAD_ERR_PARTIAL)
		{
			echo "Plik nie został całkowicie wysłany.<br/>\n";
			echo "<a href=\"index.php?category=sendSolution\">Wróć na poprednią stronę</a> jeśli chcesz spróbować jescze raz.<br/>\n";
		}
		else if($_FILES["solution_file"]["error"] == UPLOAD_ERR_NO_TMP_DIR)
		{
			echo "Value: 6; Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.<br/>\n";
			echo "<a href=\"index.php?category=sendSolution\">Wróć na poprednią stronę</a> jeśli chcesz spróbować jescze raz.<br/>\n";
		}
		else if($_FILES["solution_file"]["error"] == UPLOAD_ERR_CANT_WRITE)
		{
			echo "Value: 7; Failed to write file to disk. Introduced in PHP 5.1.0.<br/>\n";
			echo "<a href=\"index.php?category=sendSolution\">Wróć na poprednią stronę</a> jeśli chcesz spróbować jescze raz.<br/>\n";
		}
		else if($_FILES["solution_file"]["error"] == UPLOAD_ERR_EXTENSION)
		{
			echo "Value: 8; A PHP extension stopped the file upload. PHP does not provide a way to ascertain which
					extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help. 
					Introduced in PHP 5.2.0.<br/>\n";
			echo "<a href=\"index.php?category=sendSolution\">Wróć na poprednią stronę</a> jeśli chcesz spróbować jescze raz.<br/>\n";
		}
		else
		{
			echo "Nie wpisałeś treści rozwiązania, ani nie podałeś żadnego pliku.<br/>\n
			<a href=\"index.php?category=sendSolution\">Wróć na poprednią stronę</a> jeśli chcesz spróbować jescze raz.<br/>\n";
		}
		$_SESSION['solution'] = false;
		$database->close();
	}
	else
	{
		echo "Odświerzanie jest zablokowane na tej stronie...<br/>\n";
		echo "<a href=\"index.php?category=sendSolution\">Wróć na poprednią stronę</a> jeśli chcesz spróbować jescze raz.<br/>\n";
		$database->close();
	}
?>
<br/><br/>
<?php if (!$ok_flag)	{ ?>
<a id="topper" href="javascript: history.go(-1)">&#8592; Cofnij</a>
<?php } ?>
<a id="topper" href="?category=tasks">&#8593; Wróć do zadań &#8593;</a>
<a id="topper" href="?category=user/mysolutions">Moje rozwiązania &#8594;</a>


</center>
<?php	}	?>
