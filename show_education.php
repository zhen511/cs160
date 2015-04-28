<html>
	<head>
		<title>Education</title>
		<style type="text/css">
			table{
				border: 1px solid green;
			}
			th, td {
				border: 1px solid green;
				
			}
			.th1{ width: 50px;}
	
		</style>
		<?php

			include("dbconnect.php"); 
		?>
	</head>
	<body>
		<h2>Education</h2>

		<table>
			<tr>
				<th class="th1">id</th>
				<th>title</th>
				<th>description</th>
				<th class="th4">lesson_link</th>
				<th>lesson_image</th>
				<th>category</th>
				<th>student_grades</th>
				<th>author</th>
				<th>content_type</th>
				<th>time_scraped</th>
				
			</tr>
			<?php
				$sql = "select * from education";
				$result = mysqli_query($conn, $sql);
				if ($result)
				{

					while (list($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9, $col10) = mysqli_fetch_array($result))
					{
						
						print "<tr><td>";
						print $col1;
						print "</td>";
						print "<td>";
						print $col2;
						print "</td>";
						print "<td>";
						print $col3;
						print "</td>";
						print "<td>";
						print $col4;
						print "</td>";
						print "<td>";
						print $col5;
						print "</td>";
						print "<td>";
						print $col6;
						print "</td>";
						print "<td>";
						print $col7;
						print "</td>";
						print "<td>";
						print $col8;
						print "</td>";
						print "<td>";
						print $col9;
						print "</td>";
						print "<td>";
						print $col10;
						print "</td></tr>\n";
						
					}

		/*
		//another way to retrieve data
		{
				while ($row = mysqli_fetch_array($result))
				{
				print "<b>Name:</b>";
				print $row["name"];
				print "<br>\n";
				print "<b>Location:</b>";
				print $row["location"];
				print "<br>\n";
				print "<b>Email:</b>";
				print $row["email"];
				print "<br>\n";
				print "<b>URL:</b>";
				print $row["url"];
				print "<br>\n";
				print "<b>Comments:</b>";
				print $row["comments"];
				print "<br>\n";
				print "<br>\n";
				print "<br>\n";
				}
		*/
				mysqli_free_result($result);
				}
			?>
			
		</table>
	</body>
</html>


