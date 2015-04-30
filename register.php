<!-- Name: Cequan Zhen 4/27/2015-->

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Register a new user</title>
        <style type="text/css">
            body
            {
                width: 800px;
                margin:auto;
            }
            #myform
            {
                
                border: 1px solid #a0a0a0;
                width: 300px;
                padding:5px;
            }
            #errorMessage
            {
                padding: 10px;
                color: #FF0000;
            }
            .star
            {
                color: #FF0000;
            }
        </style>
        <?php
            // A function to validate the input is valid
            function isValid($text, $re)
            {
                if(strlen($text)>0)
                {
                    return preg_match($re,$text);
                }
                else
                {
                    return false;
                }
            }
            
            function isEmptyValues($array)
            {
                while(list($key,$value)=each($array))
                {
                    if(strlen($value)==0)
                        return true;
                }
                return false;
            }
        ?>
    </head>
    <body>
        <?php
            $isOK=true;
            $errorMessage="";
            $message="";
            $star1=$star2=$star3=$star4=$star5=$star6="";
            $array1['firstname']="";
            $array1['lastname']="";
            $array1['email']="";
            $array1['password']="";
            if(isset($_POST["submit"]))
            {
                $array1['firstname']=$_POST["textFirstName"];
                $array1['lastname']=$_POST["textLastName"];
                $array1['email']=$_POST["textEmail"];
                $array1['password']=$_POST["password"];
                
                // check if any of these fields are empty
                if(isEmptyValues($array1))
                    $errorMessage.="All fields are required. They must have valid values.<br >";
                
                /*if(!isValid($array1['username'],'/^[A-z]+$/'))
                {
                    $errorMessage.="Username must be only uppercase and lowercase characters.<br />";
                    $star1="*";
                    $isOK=false;
                }*/
                
                if(!isValid($array1['firstname'],'/.+/'))
                {
                    $errorMessage.="The First Name cannot be empty!!!<br />";
                    $star2="*";
                    $isOK=false;
                }
                
                if(!isValid($array1['lastname'],'/.+/'))
                {
                    $errorMessage.="The Last Name cannot be empty!!!<br />";
                    $star3="*";
                    $isOK=false;
                }
                
                
                /*if(!isValid($array1['phoneNumber'],'/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/'))
                {
                    $errorMessage.="Phone number must be a US phone number (ddd-ddd-dddd). <br />";
                    $star4="*";
                    $isOK=false;
                }*/
                
                if(!isValid($array1['email'],'/^[\w._\-|+]+@[\w_\-|]+[\w._\-|]*.[\w]+$/'))
                {
                    $errorMessage.="The email address is invalid!!!<br />";
                    $star1="*";
                    $isOK=false;
                }
                
                //if(!isValid($array1['password'],'/[0-9]{1,}/'))
				if(strlen($array1['password'])==0)
                {
                    $errorMessage.="Password must be at least 1 character long. <br />";
                    $star4="*";
                    $isOK=false;
                }
                
            }
            else
                $isOK=false;
            
            if($isOK)
            {	/*
                foreach($array1 as $key=>$value)
                    $message.="$key: $value<br />";
                $array1['firstname']=$array1['lastname']=$array1['email']=$array1['password']="";
                */
				$sql="insert into users values('" . $array1['email'] .
					"','" . $array1['firstname'] .
					"','" . $array1['lastname'] .
					"','" . $array1['password'] .
					"','Y')";
				$message.=$sql;
            }
        ?>
        <h2>Register a new user</h2>
        <div id="errorMessage" style="<?php echo strlen($errorMessage)>0 ? "border: 1px solid #a0a0a0;": "" ?>">
            <?php echo $errorMessage; ?>
        </div>
        <h3>Please sign up:</h3>
            <form id="myform" name ="myform" action="" method="POST"">
            <table id="SignUp"> 
                <?php echo
                '<tr><td><label for="textEmail">Email:</label></td>
                <td><input type="text" name="textEmail" id="textEmail" value="'.$array1['email'].'" />
                <span class="star">'.$star1.'</span><br /></td></tr>
                <tr><td><label for="textFirstName">First Name:</label></td>
                <td><input type="text" name="textFirstName" id="textFirstName" value="'.$array1['firstname'].'" />
                <span class="star">'.$star2.'</span><br /></td></tr>
                <tr><td><label for="textLastName">Last Name:</label></td>
                <td><input type="text" name="textLastName" id="textLastName" value="'.$array1['lastname'].'" />
                <span class="star">'.$star3.'</span><br /></td></tr>
                <tr><td><label for="password">Password:</label></td>
                <td><input type="password", name="password", id="password" value="'.$array1['password'].'" />
                <span class="star">'.$star4.'</span><br /></td></tr>
                <tr><td colspan="2" style="text-align:center;"><input type="submit" name="submit" value="Sign Up" /></td></tr>'
                ?>
            </table>
            </form>
			<div>If you have account, please <a href="login.php">Login</a>
			</div)
            <p><?php echo strlen($message)>0 ? $message : "" ; ?></p>
    </body>
</html>
