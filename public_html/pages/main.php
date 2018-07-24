<h1>Aktualności</h1>
<?php
$database = connectDatabase();
if (!$database)
{	include('php/database_fail.php');	}
else
{
	$limitSize = 10;

	$result = $database->query("SELECT COUNT(id) AS 'pages' FROM news;");
	$row = $result->fetch_assoc();
	$page_count = intval($row['pages'] / $limitSize);
	if ($row['pages'] % $limitSize)
		{	$page_count = $page_count + 1;	}
		
	if (!empty($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $page_count)
	{
		$page = ' LIMIT '.$limitSize.' OFFSET '.(($_GET["page"]-1)*$limitSize);
		$actual_page = $_GET["page"];
	}
	else
	{
		$page = ' LIMIT '.$limitSize.' OFFSET 0';
		$actual_page = 1;
	}
	
	$result = $database->query("SELECT id, title, news_text, author_id, clock FROM news ORDER BY clock DESC ".$page.' ;');
	$numberRows = $result->num_rows;

	for ($i = 0; $i < $numberRows; $i++)
	{
		$row = $result->fetch_assoc();
		$author = $database->query('SELECT name, surname FROM users WHERE id='.$row['author_id'].' ;');
		$autName = $author->fetch_assoc();
		echo '<div id="new_news">'."\n".'<h1 id="title">'.$row['title']."</h1>\n";
		echo '<p id="date_and_author">Dodany '.$row['clock'].' przez '.$autName['name'].' '.$autName['surname']."</p>\n";
		echo '<p id="news_txt">'.$row['news_text']."</p>\n</div>\n";
	}
	echo "\n\n";

	echo '<table id="paginator"><tr>'."\n";
	if ($actual_page != 1)
		{	echo '<td><a href="index.php?category=main&page='.($actual_page-1).'">&#8592;</a></td>';	}
	else
		{	echo '<td><p>&#8592;</p></td>';	}
	echo "\n";
	
	for ($i=1; $i <= $page_count; $i++)
	{	
		if ($actual_page != $i)
			{	echo '<td><a href="index.php?category=main&page='.$i.'">'.$i.'</a></td>';	}
		else
			{	echo '<td><p><b>'.$i.'</b></p></td>';	}
		echo "\n";
	}

	if ($actual_page != $page_count)
		{	echo '<td><a href="index.php?category=main&page='.($actual_page+1).'">&#8594;</a></td>';	}
	else
		{	echo '<td><p>&#8594;</p></td>';	}
	echo '</tr></table>'."\n\n";
		
	$database->close();

}
?>
