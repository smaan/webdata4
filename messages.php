<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors','On');
if(!isset($_SESSION['username'])){
	header("Location: board.php");
}
if(isset($_POST['logout']) && $_POST['logout'] == "Logout"){
	session_unset();
	header("Location: board.php");
}
try{
	$dbname = dirname($_SERVER["SCRIPT_FILENAME"]) . "/mydb.sqlite";
	$dbh = new PDO("sqlite:$dbname");
	$dbh->beginTransaction();
	$stmt = $dbh->prepare('select * from posts, users where username = "'.$_SESSION['username'].'" order by posts.datetime');
	$stmt->execute();
	$dbh->commit();
}
catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>
<html>
	<head><title>Welcome</title></head>
	<body>

		<fieldset><legend>Welcome!</legend>
			<form name="somepage" method="POST" action="messages.php">
				<input type = "button" onclick = "window.location.replace('newpost.php');" value = "New Post">
				<input type = "submit" name="logout" value="Logout">
			</form>
			<div>
			<?php
			try{
				print "<pre>";
				while ($row = $stmt->fetch()) {
				//	print_r($row);
					echo $row['message'] . "<br/>";
					echo "Message ID: " . $row['id'];
					if (isset($row['follows']) && $row['follows'] != ""){
						echo "<br/>Parent Message ID: " . $row['follows'];
					}
					echo "<br/>" . "Posted by - " . $row['postedby'] . ", ";
					echo $row['fullname'];
					echo " on " . $row['datetime'];
				?>
				<br>
				<form action = "newpost.php" method = "POST">
					<input type = "submit" name = "reply" value = "Reply"><br>
					<input type = "hidden" name = "hidden" value = "<?php echo $row['id']?>">
				</form>
			<?php
				}
			print "</pre>";
			}
			catch (PDOException $e) {
			  print "Error!: " . $e->getMessage() . "<br/>";
			  die();
			}
			?>
			</div>
		</fieldset>
	</body>
</html>