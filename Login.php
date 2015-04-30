<!-- Name: Cequan Zhen -->
<?php
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: DENY");
?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Login</title>
        <style type="text/css">
            *
            {
                
                font-family: Helvetica, Arial, sans-serif;
            }
            #body
            {
                font-size:16px;
                width: 800px;
                margin: auto;
            }
            #message
            {
                color: #FF0000;
            }
            #newuser
			{
				position: relative;
				left: 200px;
				top: -40px;
			}
            #div1 {

              float: left;

              position: relative;

              left: 100px;

              width: 100px;

            }
        </style>
        <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"> -->
		<script src="jquery-1.11.2.min.js"></script>
		<script type="text/javascript">
            function buttonclick()
            {
                var color=$("#textUserName").val();
                $("h2").css("background-color",color);
            }
        </script>
        <?php
            // clases
            class dataField
            {
                public $id=-1;
                public $username="";
                public $firstname="";
                public $lastname="";
                public $phoneNumber="";
                public $email="";
                public $password="";
                public $bio="";
            
            }
            // Functions
            function connectMySql(){
                // Create connection
                $conn=mysqli_connect("localhost","cs174_72","Cr5LHBaW","cs174_72");
                // Check connection
                if (mysqli_connect_errno($conn))
                {
                    die("Failed to connect to MySQL: " . mysqli_connect_error());
                }
                return $conn;
            }
        ?>
    </head>
    <body>
    
        <h2>User Login</h2>
        <div id="div1"></div>
		<div id="newuser">
			<a href="register.php">New user, Sign up here</a>
		</div>
        <form id="myform" name ="myform" action="" method="POST"  AUTOCOMPLETE='OFF'>
            <table id="myTable">    
                <tr><td><label for="textUserName">Email:</label></td>
                <td><input type="text" name="textUserName" id="textUserName" size="40" />
                <span id="message" ></span><br /></td></tr>
                <tr><td><label for="textPassword">Password:</label></td>
                <td><input type="password" name="textPassword" id="textPassword" size="40"/><br /></td></tr>
                <tr><td><input type="submit" name="submit" value="Login"  /></td></tr>
                
            </table>
        </form>
        <div><button type="button" id="button" name="button" onclick="buttonclick();">Click Me!</button>
		
        <div id="submitted">
        <?php
            if(isset($_POST["submit"]) && isset($_POST["textUserName"]) && isset($_POST["textPassword"])) {
				// Create connection
				$mysqli=connectMySql();
				$data=new dataField();
				$data->username=$mysqli->real_escape_string(htmlspecialchars($_POST["textUserName"]));
				$data->password=$mysqli->real_escape_string(htmlspecialchars($_POST["textPassword"]));
				if(strlen($data->username)>0)
				{
					$sql="call Login(\"".$data->username."\", \"".$data->password."\")";
					$result=$mysqli->query($sql);
					if(!$result)
						die("Error: ".$mysqli->error());
					else
					{
						$row=$result->fetch_array();
					}
					if($row['username']==$data->username)
					{
						echo "<p>Login Success</p>";
						//redirection
						echo 
						'<form name="form2" action="p9_Account_Update.php" method="POST">
						<input id="usrname" type="hidden" value="'.$data->username.'" name="usrname" />
						<input id="pwd" type="hidden" value="'.$data->password.'" name="pwd" />
						</form>
						<script>document.form2.submit()</script>';
						exit();
					}
				}
				echo "<p>Login Error, please check your username and password. </p>";
			
		   
            }
        ?>
        </div>
    </body>
</html>
