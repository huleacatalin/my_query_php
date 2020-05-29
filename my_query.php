<?php
$mysqli = new mysqli();
$connected = @$mysqli->real_connect(@$_POST['server'], @$_POST['username'], @$_POST['password'], @$_POST['database']);

if($connected) {
	$mysqli->multi_query(@$_POST['query']);
}
?>
<html>
<head>
<title>my_query.php - MySQL query</title>

<style>
table {
	margin-bottom: 20px;
}

#query {
	width: 100%; 
	height: 300px;
}
</style>
</head>

<body onload="document.getElementById('query').select(); ">
<h1>my_query.php</h1>

<?php
if($connected) {
	if(count($mysqli->error_list) > 0) {
		?>
		<table style="color: red; " border="1">
		<tr>
		<?php
		foreach($mysqli->error_list[0] as $key => $value) {
			?>
			<td><?php echo htmlspecialchars($key); ?></td>
			<?php
		}
		?>
		</tr>
		<?php
		for($i = 0; $i < count($mysqli->error_list); $i++) {
			?>
			<tr>
			<?php
			foreach($mysqli->error_list[$i] as $key => $value) {
				?>
				<td><?php echo htmlspecialchars($value); ?></td>
				<?php
			}
			?>
			</tr>
			<?php
		}
		?>
		</table>
		<?php
	}
	
	do {
		if($res = $mysqli->store_result()) {
			?>
			<table border="1">
			<?php
			$first = true;
			while($row = $res->fetch_assoc()) {
				if($first) {
					?>
					<tr>
					<?php
					foreach($row as $key => $value) {
						?>
						<th><?php echo htmlspecialchars($key); ?></th>
						<?php
					}
					$first = false;
					?>
					<tr>
					<?php
				}
				?>
				<tr>
					<?php
					foreach($row as $key => $value) {
						?>
						<td><?php echo htmlspecialchars($value); ?></td>
						<?php
					}
					?>
				</tr>
				<?php
			}
			?>
			</table>
			<?php
			$res->free();
		}
	}
	while($mysqli->more_results() && $mysqli->next_result());
}
else {
	?>
	<p>Not connected to the database server.</p>
	<?php
}
?>

<form method="post">
	<p>
		<label>server: <input type="text" name="server" value="<?php echo htmlspecialchars(@$_POST['server']); ?>"></label>
		<label>username: <input type="text" name="username" value="<?php echo htmlspecialchars(@$_POST['username']); ?>"></label>
		<label>password: <input type="password" name="password" value="<?php echo htmlspecialchars(@$_POST['password']); ?>"></label>
		<label>database: <input type="text" name="database" value="<?php echo htmlspecialchars(@$_POST['database']); ?>"></label>
	</p>
	<label>
		<p>MySQL query: </p>
		<p><textarea id="query" name="query"><?php echo htmlspecialchars(@$_POST['query']); ?></textarea></p>
	<label>
	<input type="submit">
</form>

</body>
</html>
<?php
$mysqli->close();
?>