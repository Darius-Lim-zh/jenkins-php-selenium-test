<?php 
	session_start();
    function isXSS($input) {
        $xss_patterns = [
            '/<script.*?>.*?<\\/script.*?>/is', // Script tags
            '/<.*?on.*?=.*?".*?".*?>/is',       // Event handlers (e.g., onload, onclick)
            '/<.*?javascript:.*?".*?>/is',      // javascript: in attributes
        ];
        foreach ($xss_patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        return false;
    }
    
    function isSQLInjection($input) {
        $sql_patterns = [
            '/\' OR \'1\'=\'1/', // Basic SQL injection pattern
            '/--/',              // SQL comment
            '/#/',               // SQL comment
            '/\/\*/',            // SQL comment
            '/UNION/',           // UNION keyword
            '/SELECT/',          // SELECT keyword
            '/INSERT/',          // INSERT keyword
            '/UPDATE/',          // UPDATE keyword
            '/DELETE/',          // DELETE keyword
            '/DROP/',            // DROP keyword
        ];
        foreach ($sql_patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        return false;
    }
    
    function isCommonPassword($password) {
        $passwordListFile = __DIR__ . '/10-million-password-list-top-1000.txt';
        if (file_exists($passwordListFile)) {
            $commonPasswords = file($passwordListFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (in_array($password, $commonPasswords)) {
                return true;
            }
        }
        return false;
    }
	
	if(isset($_POST['submit']))
	{
        $testing = "Testing to see where this appears";
		if((isset($_POST['email']) && $_POST['email'] !='') && (isset($_POST['password']) && $_POST['password'] !=''))
		{
			$email = trim($_POST['email']);
			$password = trim($_POST['password']);
			
			if($email == "user@example.com")
			{	
				if($password == "password1234")
				{
					$_SESSION['user_id'] = $email;
					
					header('location:dashboard.php');
					exit;
					
				}
			}
			$errorMsg = "Login failed";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login Page | PHP Login and logout example with session</title>
<link rel="stylesheet" href="style.css">
</head>

<body>
	
	<div class="container">
		<h1>PHP Login and Logout with Session</h1>
		<?php 
			if(isset($errorMsg))
			{
				echo "<div class='error-msg'>";
				echo $errorMsg;
				echo "</div>";
				unset($errorMsg);
			}
			if(isset($testing))
			{
				echo "<div class='error-msg'>";
				echo $testing;
				echo "</div>";
				unset($testing);
			}
			if(isset($_GET['logout']))
			{
				echo "<div class='success-msg'>";
				echo "You have successfully logout";
				echo "</div>";
			}
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
			<div class="field-container">
				<label>Email</label>
				<input type="email" name="email" required placeholder="Enter Your Email">
			</div>
			<div class="field-container">
				<label>Password</label>
				<input type="password" name="password" required placeholder="Enter Your Password">
			</div>
			<div class="field-container">
				<button type="submit" name="submit">Submit</button>
			</div>
			
		</form>
	</div>
</body>
</html>