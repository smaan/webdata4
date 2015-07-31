<?php
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
		$message = "User Name already exists!";
	if(isset($_POST['username']) && $_POST['username'] != NULL && isset($_POST['password']) && $_POST['password'] != NULL){
		$stm = $dbh->prepare('select username from users where username="'.$_POST['username'].'"');
		$stm->execute();
		if(sizeof($stm->fetch()) > 1){
			header("Location: register.php?status=fail");	
		}
		else{
			$dbh->exec('insert into users values("'.$_POST['username'].'","' . md5($_POST['password']) . '","'.$_POST['fullname'].'","'.$_POST['emailid'].'")');
			//or die(print_r($dbh->errorInfo(), true));
			header("Location: board.php");
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
	<head><title>New User Registration</title></head>
	<body>
		<form name = "detailsf" action = "register.php" method = "POST">
			<fieldset><legend>Enter your details:</legend>
				<table>
					<?php if(isset($message) && $message != NULL){?>
					<tr>
						<td colspan="2"><?php echo $message;?></td>
					</tr>
					<?php }?>
					<tr>
						<td width = "10%">User Name</td>
						<td><input type = "text" name = "username" value = "" required></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input type = "password" name = "password" value = "" required></td>
					</tr>
					<tr>
						<td>Full Name</td>
						<td><input type = "text" name = "fullname" value = ""></td>
					</tr>
					<tr>
						<td>Email Id</td>
						<td><input type = "email" name = "emailid" value = ""></td>
					</tr>
					<tr>
						<td></td>
						<td><input type = "submit" value = "Register"></td>
					</tr>
				</table>
			</fieldset>
		</form>
	</body>
</html>
