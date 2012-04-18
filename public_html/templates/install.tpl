<!DOCTYPE html>
<html>
<head>
<title>Installing KC Blog</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
	<h1>Install:</h1>
	<div id="message">{$message}</div>
	<form action="install.php" method="post">
		<label for="user">Brukernavn:</label>
		<input type="text" name="user" required="required" /><br>
		<label for="pw">Passord:</label>
		<input type="password" name="pw" required="required" /><br>
		<label for="blogname">Bloggnavn:</label>
		<input type="text" name="blogname" required="required" /><br>
		<label for="dbhost">MySQL Host:</label>
		<input type="text" name="dbhost" required="required" value="localhost" /><br>
		<label for="dbname">MySQL Database Name:</label>
		<input type="text" name="dbname" required="required" /><br>
		<label for="dbuser">MySQL User:</label>
		<input type="text" name="dbuser" required="required" /><br>
		<label for="dbpw">MySQL Password:</label>
		<input type="text" name="dbpw" required="required" /><br>
		<label for="dbprefix">MySQL Prefix:</label>
		<input type="text" name="dbprefix" value="kc_"/><br>
		<input type="submit" value="Install" />
	</form>
</body>
</html>