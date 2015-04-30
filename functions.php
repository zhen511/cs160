<!-- Name: Cequan Zhen 4/28/2015-->
<?php
	//include("dbconnect.php");
	
	function userLogin(){
	}
	
	function show_lesson_page($id){
		include("dbconnect.php");
		$sql = "select lesson_link from education where id=" . $id;
		//echo $sql;
		$result = mysqli_query($conn, $sql);
		if ($result)
		{
			$sql="update ed set hit_count=hit_count+1 where id=" . $id;
			$conn->query($sql);
			$row = mysqli_fetch_row($result);
			mysqli_free_result($result);
			echo '<!DOCTYPE html>
				<html>
				<head>
				<meta http-equiv="Refresh" content="0;url=' . $row[0] .'">
				</head>
				<body>
				</body>
				</html> ';
			//exit(header('Location: '.$row[0]));
			//http_redirect($row[0]);
			
			
		}
		
	}
	
	function search($str, $level, $type){
		include("dbconnect.php");
		$str=trim($str);
		$keys=explode(" ", $str);
		$where="where ";
		$counter=count($keys);
		for($i=0; $i<$counter; $i++){
			$where.="text like '%" . $keys[$i] . "%'";
			if($i<$counter-1)
				$where.= " and ";
		}
		if(strlen($level)>0){
			$where.=" and student_grades like '%" .$level . "%'";
		}
		
		if(strlen($type)>0){
			$where.=" and category like '%" .$type . "%'";
		}
		//echo $where;
		
		$sql="select * from education where id in ( select id from ed " . $where . ")";
		//echo $sql;
		$result = mysqli_query($conn, $sql);
		if($result){
			lesson_list($result);
			mysqli_free_result($result);
		}
	}

	function search_by_popular($str, $level, $type){
		include("dbconnect.php");
		$str=trim($str);
		$keys=explode(" ", $str);
		$where="where ";
		$counter=count($keys);
		for($i=0; $i<$counter; $i++){
			$where.="text like '%" . $keys[$i] . "%'";
			if($i<$counter-1)
				$where.= " and ";
		}
		if(strlen($level)>0){
			$where.=" and student_grades like '%" .$level . "%'";
		}
		
		if(strlen($type)>0){
			$where.=" and b_c like '%" .$type . "%'";
		}
		//echo $where;
		
		$sql="select `id`, `title`, `description`, `lesson_link`, `lesson_image`, `category`, `student_grades`, `author`, `content_type`, `time_scraped` from v_ed " . $where . " order by hit_count desc limit 20";
		//echo $sql;
		$result = mysqli_query($conn, $sql);
		if($result){
			lesson_list($result);
			mysqli_free_result($result);
		}
	}

	
	function lesson_list($result){
		
		if ($result)
		{
			while (list($id, $title, $description, $lesson_link, $lesson_image, $c, $s, $author, $time) = mysqli_fetch_array($result))
			{ 
				$link="show_lesson.php/?lesson_id=" .$id;
				?>
				<div>
					<div>
						<a href="<?php print $link; ?>">
							<h4 class="lesson_title"><?php print $title; ?></h3>
						</a>
						<table class="lesson_table" width='60%'><tr>
							<td class="td_top">
								<div class="lesson_link">
									<?php 
										if(strlen($lesson_link)>60)
											$lesson_link=substr($lesson_link, 0, 60) ."...";
										print $lesson_link; ?>
								</div>
								<p><?php print $description ?></p>
							</td>
							<td style="width:208px">
								<a href="<?php print $link; ?>">
									<img src="<?php print $lesson_image; ?>" alt="no picture" style="width:200px;height:120px">
								</a>
							</td>
							
						</tr></table>
					</div>
					<hr />
				</div>
				<?php
			}
		}
	}
	
	function show_list_box($name, $list, $value){
		$size=count($list);
		echo "<select id='" .$name ."' name='" .$name ."'>";
		for($i=0; $i<$size; $i++){
			if($list[$i]==$value)
				echo "<option value='" . $list[$i] . "' selected>" . $list[$i] . "</option>";
			else
				echo "<option value='" . $list[$i] . "'>" . $list[$i] . "</option>";
		}
		echo "</select>";

	}
	
?>