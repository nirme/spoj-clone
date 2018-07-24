<?php
    function deleteGroup($id) {
        $database = connectDatabase();

        if ($database == false) {
			return "<p>Nie udało się połączyć z bazą danych. Spróbuj później.</p>";
		}

        @ $result = $database->query("select id 
									  from groups
									  where id='$id'");
        
        if ($result == false) {
			return "<p>Nie udało się wykonać zapytania. Spróbuj później.</p>";
		}

        if (!$result->num_rows) {
			return "<p>Grupa o podanym id nie istnieje.</p>";
		}

        @ $result = $database->query("delete 
									  from groups
									  where id='$id'");
        
        if ($result == false) {
			return "<p>Nie udało się wykonać zapytania. Spróbuj później.</p>";
		}

        return "<p>Grupa została poprawnie usunięta</p>";
    }
?>
