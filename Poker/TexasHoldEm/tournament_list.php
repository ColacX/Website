<p>
tournament_list
</p>

<?php

$connection = mysql_connect("localhost","root","");

if(!$connection)
	die('could not connect: ' . mysql_error());

mysql_select_db("poker_database", $connection);

$result = mysql_query("select * from tournament_list");
mysql_close($connection);

?>
	<table>
		<?php
		
		while($row = mysql_fetch_array($result))
		{
			printf("
			<tr>
				<td>%d</td>
				<td>%s</td>
				<td>%s</td>
			</tr>
			", $row['id'], $row['name'], "<a href = 'tournament_view.php'>open</a>");					
		}
		
		?>
	</table>