<?php

	// plik: clearVariable.php
	// funkcja usuwa z przesłanej zmiennej  wszystkie niepożądane znaki
	
	function clearVariable($source) {
		$source = trim($source); // usuwanie białych znaków z końca i początku
		$source = strip_tags($source); // usuwanie tagów HTML i PHP
		
		// zwrócenie poprawionego stringu
		$result = $source;
		return $result;
	}

?>
