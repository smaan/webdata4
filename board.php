<?php
//sukhbir singh 1001081404
//http://omega.uta.edu/~sxs1404/project4/board.php
error_reporting(E_ALL);
ini_set('display_errors','On');
session_start();
if(isset($_SESSION['username'])){
	header("Location: messages.php");
}
try{
	$dbname = dirname($_SERVER["SCRIPT_FILENAME"]) . "/mydb.sqlite";
	$dbh = new PDO("sqlite:$dbname");
	$dbh->beginTransaction();
	if(isset($_GET['status']) && $_GET['status'] == 'fail')
		$message = "User Name or Password is not correct!";
	if(isset($_POST['username'])){
		$stm = $dbh->prepare('select username, password from users where username="'.$_POST['username'].'" AND password = "'.md5($_POST['password']).'"');
		$stm->execute();
		if(sizeof($stm->fetch()) > 1){
			$_SESSION['username'] = $_POST['username'];
			header("Location: messages.php");
		}
		else{
			header("Location: board.php?status=fail");
		}
	}
	$dbh->commit();
}
catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>
<html>
	<head><title>Message Board</title></head>
	<body>
		<form name = "loginf" action = "board.php" method = "POST">
			<fieldset><legend>Enter your credentials:</legend>
				<table>
					<?php if(isset($message) && $message != NULL){?>
					<tr>
						<td colspan="2"><?php echo $message;?></td>
					</tr>
					<?php }?>
					<tr>
						<td width = "10%">User Name</td>
						<td><input type = "text" name = "username" value = ""></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input type = "password" name = "password" value = ""></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input type = "submit" name="login" value = "Login">
							<input type = "button" onclick="window.location.replace('register.php');" name="register" value = "Register">
						</td>
					</tr>
				</table>
			</fieldset>
		</form>
	</body>
</html>
