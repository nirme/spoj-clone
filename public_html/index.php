<?php

	// plik: index.php
	// głównym zadaniem pliku jest wczytywanie odpowiedniej strony z katalogu
	// "pages" i łączenie go z szablonem strony z katalogu "layout"
	
	include('php/clearvariable.php'); // funkcja usuwająca niepożądane znaki
	include('php/connectdatabase.php');	// funkcja łącząca się z bazą
	
	$pageName = "spojclone";		// nazwa strony
	$encoding = "UTF-8";			// kodowanie strony
	
	$mainPage = "main"; 			// główny plik
	$mainTitle = "Strona główna"; 	// tytuł głównego pliku
	$errorPage = "error";			// plik błędu
	$errorTitle = "Wystąpił błąd";	// tytuł pliku błędu
	
	session_start();	// wystartowanie sesji potrzebnej do uwierzytelniania
	$authStatus = "";	// zmienna błądu autoryzacji
	
	
	// jeżeli wcisnięty został przycisk "zaloguj"
	if (isset($_POST['authenticate'])) {
		$login = clearVariable($_POST['login']);	// pobranie loginu
		$password = clearVariable($_POST['password']);	// pobranie hasła
		
		if (empty($login) || empty($password)) {
			// zwrócenie błędu jeżeli login lub hasło są puste
			$authStatus = "Musisz wpisać login i hasło.";
		} else {
			// w innym wypadku wczytanie wywołanie funkcji logującej
			include('php/authenticate.php');
			$authStatus = authenticate($login, $password);
		}
	}
	
	if (!empty($_GET['action'])) {
		$action = clearVariable($_GET['action']);
		
		switch ($action) {
		case 'logout' :
			session_destroy();
			session_start();
			break;
		}
	}
	
//nauczcie sie phpa wykorzystywac chlopaki :|
	include('php/category_base.php');
	$category = clearVariable($_GET['category']);
	$title = $category_array[$category];
	if (!$title)
		{	$title = $errorTitle;	}
	if (empty($category))
		{	$category = 'main';	}
		
		
	if (!isset($_SESSION['style']))	{	$_SESSION['style'] = "new.css";	}
	if (isset($_POST['style']))	{	$_SESSION['style'] = $_POST['style'];	}

	// wczytanie nagłówka i menu
	include('layout/header.php');
	include('layout/menu.php');
	
	// jeśli niezalogowany i wciśnięty przycisk zaloguj
	include('php/loginform.php');
	if (!isset($_SESSION['userIndeks']) && isset($_POST['authenticate'])) {
		// to wyświetlenie formularzu logowania zamiast strony
		loginForm($category, $authStatus);
	} else {
        if (isset($_SESSION['userIndeks']) && !$_SESSION['userLogin']) {
            $category = 'user/setlogin';
        }
		// w przeciwnym wypadku załadowanie pliku z treścią jeśli istnieje
		if (file_exists('pages/' . $category . '.php')) {
		    include('pages/' . $category . '.php');	
		} else {
		    // jeśli nie, wczytana zostaje strona błędu
		    include('pages/' . $errorPage . '.php');
		}
        
	}
	
	// wczytanie sidebaru i stopki
	//include('layout/sidebar.php');
	include('layout/footer.php');
	
	
	//insert projekt5.users (name, surname, pass, mail, indeks, points) values ('nirme', '89', sha1('qwerty'), 'nirme@nirme.com', 123456, 5);
	
?>
