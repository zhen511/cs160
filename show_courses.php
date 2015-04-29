<html>
	<head>
		<title>Access to each course</title>
	</head>
	<style type="text/css">
		body
		{
			border: 1px solid #888888;
			width: 800px;
			margin: auto;
			font-side:16px;
			font-family: sans-serif;
		}
		#div1 {margin: 10px}
		table
		{
		}
		td{ width: 250px;}
		
    </style>
	<?php
		include("dbconnect.php"); 
	?>

	<body>
		<div id="div1">
		<h2>Access to each course</h2>
			<div>
				<form action="" method="POST" id="myform">
					<label for="id">course id:</label>
					<input type="text" name="id" id="id" />
					<input type="submit" name="Search" value="Search" />
            
				</form>
			</div>
		<?php
			$id=0;
			if(isset($_POST["Search"]))
            {
                $id=intval($_POST["id"]);
			}
            if($id>0){
				$sql = "select title, description, lesson_link, lesson_image from education where id=".$id;
			}
			else{
				$sql = "select title, description, lesson_link, lesson_image from education";
			}
			$result = mysqli_query($conn, $sql);
			if ($result)
			{

				while (list($id, $title, $description, $lesson_link, $lesson_image) = mysqli_fetch_array($result))
				{ ?>
					<div>
						<h3><?php print $title; ?></h3>
						<table><tr>
							<td>
								<a href="<?php print $lesson_link; ?>">
									<img src="<?php print $lesson_image; ?>" alt="no picture" style="width:250px;height:200px">
								</a>
							</td>
							<td>
								<p><?php print $description ?></p>
							</td>
						</tr></table>
					</div>
					<hr />
					<?php
				}
			}
			mysqli_free_result($result);
		?>
	</div>
	</body>
</html>
