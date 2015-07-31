<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors','On');
if(!isset($_SESSION['username'])){
	header("Location: board.php");
}
try{
	$dbname = dirname($_SERVER["SCRIPT_FILENAME"]) . "/mydb.sqlite";
	$dbh = new PDO("sqlite:$dbname");
	$dbh->beginTransaction();
	if(isset($_POST['ta']))
		$ta = trim($_POST['ta']);
	if(isset($_POST['hidden'])){
		$follow = $_POST['hidden'];
	}
	else{
		$follow = "";
	};

	if(isset($ta) && $ta != ""  && isset($_POST['postb'])){
		$pid = uniqid();
		$dt = date("Y.m.d H.i.s");
		$stm = $dbh->prepare('insert into posts (id, postedby, follows, datetime, message) values("'.$pid.'","'.$_SESSION['username'].'","'.$follow.'","'.$dt.'","'.$_POST['ta'].'")');
		$stm->execute();
		header("Location: messages.php");
	}
	else{
		$message = "Please enter the text";
	}
	$dbh->commit();
}
catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>
<html>
	<head><title>New Message Board</title></head>
		<body>
		<form name = "newpostf" action = "newpost.php" method = "POST">
			<fieldset>
				<legend>Create a New Post:</legend>
				<?php if(isset($message) && $message != NULL){ ?>
				<div>
					<?php echo $message; ?>
				</div>
				<?php } ?>
				<textarea id = "taid" name = "ta" rows = "7" cols = "50"></textarea><br><br>
				<input type = "submit" name = "postb" value = "Post">
				<input type = "hidden" name = "hidden" value = "<?php echo $follow?>">
			</fieldset>
		</form>
	</body>
</html>
