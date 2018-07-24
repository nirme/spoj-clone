<h3>Dodaj grupę</h3>
<?php
if ($_SESSION['isAdmin']) {
if (isset($_POST['addgroup'])) {
			$group = clearVariable($_POST['group']);
			$changeStatus;
			
			if (empty($group)) {
				// zwrócenie błędu jeżeli któreś pole jest puste
				$changeStatus = "<p>Musisz wpisać nazwę grupy.</p>";
			} else {
				include('php/addgroup.php');
				$changeStatus = addGroup($group);
			}
			
			echo $changeStatus;	
		}
?>
<form action="?category=admin/groups" method="post"><p>Nazwa grupy: <input type="text" maxlength="30" size="16" name="group" /> <input type="submit" name="addgroup" value="Dodaj" /></p></form>

<hr />
<hr />
<h3>Istniejące grupy</h3>

<?php
    if ($action == 'delete') {
        if (!empty($_GET['id'])) {
		        $id = clearVariable($_GET['id']);
                $deleteStatus;

                include('php/deletegroup.php');
		        $deleteStatus = deleteGroup($id);

                echo $deleteStatus;        
        }
    }
?>

<table cellpadding="0" cellspacing="2">
<tr>
<th>Id</th>
<th>Nazwa grupy</th>
<th>Ilość użyt.</th>
</tr>
<?php		
		// wywołanie funkcji łączącej się z bazą
		$database = connectDatabase();
		
		// zwrócenie błędu jeśli nie dostano zasobu bazy
		if ($database == false) {
			return "<p>Nie udało się połączyć z bazą danych. Spróbuj później.</p>";
		}

		@ $result = $database->query("select id, name 
									  from groups");
		$numberRows = $result->num_rows;

		for ($i = 0; $i < $numberRows; $i++) {
			$row = $result->fetch_assoc();

            $groupId = $row['id'];

            @ $result2 = $database->query("select count(group_id)
                                           from user_to_group
                                           where group_id = $groupId");
            if($result2) {
                $row2 = $result2->fetch_array();
                $usersNumber = $row2[0];
            } else {
                $usersNumber = 0;
            }

			echo "<tr>\n";
			echo "<td id=\"num\">" . $groupId . "</td>\n";
			echo "<td id=\"txt\"><a href=\"?category=admin/showgroup&id=" . $groupId ."\">" . $row['name'] . "</a></td>\n";
			echo "<td id=\"num\">" . $usersNumber . "</td>\n";
            echo "<td><a href=\"?category=admin/groups&action=delete&id=" . $groupId . "\">usuń</a></td>\n";
			echo "</tr>\n";
		}

$database->close();
?>
</table>

<?php
} else {
?>
<p>Nie masz uprawnien do przeglądania tej strony</p>
<?php } ?>

