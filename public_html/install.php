<?php
$settingsFile = "../settings.xml";
if (!file_exists($settingsFile)) {
	header('Content-Type: text/html; charset=utf-8');
	require ('libs/Smarty.class.php');
	$smarty = new Smarty;
	
	$all = isset($_POST["user"]) && isset($_POST["pw"]) && isset($_POST["blogname"]) && isset($_POST["tagline"])
		&& isset($_POST["dbhost"]) && isset($_POST["dbname"])
		&& isset($_POST["dbuser"]) && isset($_POST["dbpw"])
		&& isset($_POST["dbprefix"]);
		
	$oneOrMore = isset($_POST["user"]) || isset($_POST["pw"]) || isset($_POST["blogname"]) || isset($_POST["tagline"])
		|| isset($_POST["dbhost"]) || isset($_POST["dbname"])
		|| isset($_POST["dbuser"]) || isset($_POST["dbpw"])
		|| isset($_POST["dbprefix"]);
	
	if ($all) {
			require 'settings.class.php';
			$settings = new settings($settingsFile, $_POST["blogname"], $_POST["tagline"], $_POST["dbhost"], $_POST["dbuser"], $_POST["dbpw"], $_POST["dbname"], $_POST["dbprefix"]);
			
			// I don't see the point in cleaning input at this stage.
			// If an attacker is able to use this script he can make hell without exploiting injections.
			
			$user = $_POST["user"];
			$pw = $_POST["pw"];
			$blogname = $_POST["blogname"];
			$dbhost = $_POST["dbhost"];
			$dbname = $_POST["dbname"];
			$dbuser = $_POST["dbuser"];
			$dbpw = $_POST["dbpw"];
			$dbprefix = $_POST["dbprefix"];
			
			$createUsers = 
			"CREATE TABLE " . $dbprefix . "users (" .
         		"id INT NOT NULL AUTO_INCREMENT PRIMARY KEY," .
         		"username VARCHAR(100)," .
         		"email VARCHAR(200)," .
         		"firstname VARCHAR(100)," .
         		"lastname VARCHAR(100)," .
         		"password VARCHAR(100)," . //this is supposed to be a hashed value
         		"salt VARCHAR(100)," . //this is supposed to be a hashed value
         		"validationkey VARCHAR(100)," .
         		"session_cookie VARCHAR(100)," .
         		"usermode TINYINT," . // -1 = not validated, 0 = disabeled, 1 = active
         		"userlevel TINYINT" .
       		");";
			
			$createPosts = 
			"CREATE TABLE " . $dbprefix . "posts (" .
         		"id INT NOT NULL AUTO_INCREMENT PRIMARY KEY," .
         		"title VARCHAR(100)," .
         		"url_id VARCHAR(100)," .
         		"time BIGINT(12)," .
         		"author_id INT," .
         		"content TEXT," .
         		// FOREIGN KEY for author_id -> kc_users(id)
         		"INDEX usr_id (author_id)," .
                "FOREIGN KEY (author_id) REFERENCES " . $dbprefix . "users(id)" .
                "ON DELETE CASCADE" .
       		");";
			
			$createPages = 
			"CREATE TABLE " . $dbprefix . "pages (" .
         		"id INT NOT NULL AUTO_INCREMENT PRIMARY KEY," .
         		"title VARCHAR(100)," .
         		"url_id VARCHAR(100)," .
         		"time BIGINT(12)," .
         		"author_id INT," .
         		"content TEXT," .
         		// FOREIGN KEY for author_id -> kc_users(id)
         		"INDEX usr_id (author_id)," .
                "FOREIGN KEY (author_id) REFERENCES " . $dbprefix . "users(id)" .
                "ON DELETE CASCADE" .
       		");";
			
			$createComments = 
			"CREATE TABLE " . $dbprefix . "comments (" .
         		"id INT NOT NULL AUTO_INCREMENT PRIMARY KEY," .
         		"post_id INT," .
         		"page_id INT," .
         		"time BIGINT(12)," .
         		"author_id INT," .
         		"content TEXT," .
         		// FOREIGN KEY for author_id -> kc_users(id)
         		"INDEX usr_id (author_id)," .
                "FOREIGN KEY (author_id) REFERENCES " . $dbprefix . "users(id)" .
                "ON DELETE CASCADE," .
                // FOREIGN KEY for post_id -> kc_users(id)
         		"INDEX pst_id (post_id)," .
                "FOREIGN KEY (post_id) REFERENCES " . $dbprefix . "posts(id)" .
                "ON DELETE CASCADE," .
                // FOREIGN KEY for page_id -> kc_users(id)
         		"INDEX pge_id (page_id)," .
                "FOREIGN KEY (page_id) REFERENCES " . $dbprefix . "pages(id)" .
                "ON DELETE CASCADE" .
       		");";
       		
			
       		$db = $settings -> getDatabase();
       		
			$db -> exec($createUsers);
			$db -> exec($createPosts);
			$db -> exec($createPages);
			$db -> exec($createComments);
			
			//TODO: Add the user to the database!
			
			$smarty->assign("message","<pre>$createUsers\n$createPosts\n$createPages\n$createComments</pre>");

	} else 	if ($oneOrMore) {
			$smarty->assign("message","Fill ALL fields (and get a html5 compadible browser)!");

	}else {
			$smarty->assign("message","Fill all fields!");
	}

	$smarty -> display('install.tpl');

} else {
	exit ;
}
?>