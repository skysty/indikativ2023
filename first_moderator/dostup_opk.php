<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<head>
<?php include '../extensions/head_enbek.php'; ?>
</head>
<body>
	<div class = "upper_header">
		<img src = "../img/login_logo.png" style = "width: 200px; float:left;">
		<p style = "font-size: 24px; text-align: center; color: #0094de; font-weight: bold;">АХМЕТ ЯСАУИ УНИВЕРСИТЕТІ</p>
		<p style = "font-size: 24px; text-align: center; color: red;font-weight: bold;">ІШКІ КӘСІБИ РЕЙТИНГІ</p>
		<div style = "font-size: 18px; text-align: center; color: #0094de;font-weight: bold;">		
<?php
$result= mysqli_query($connection,"SELECT text_jildar FROM jildar WHERE id_jildar= '1'");
while($row = mysqli_fetch_array($result)){
echo $row['text_jildar'];
 }
?>
</div></br>	</div>
	<div class = "header">
	<div id = "menu_nav">
		<nav id="primary_nav_wrap">
			<ul>
			  <li><a href="index.php">Негізгі</a>
				<ul>
				  <li><a href="index.php">Негізгі бет</a></li>				 
				</ul>
			  </li>
			  
			  <li><a href="#">Орындау</a>
				<ul>
				  <li><a href="engbek_jukteu.php">ОПҚ/ҒҚ</a></li>
				</ul>
			  </li>
			  <li><a href="#">Басқа</a>
				<ul>
				  <li><a href="baska_okitushyny_koru.php">ОПҚ/ҒҚ</a></li>
				</ul>
			  </li>			  
			  <li><a href="#">Сенім жәшігі</a>
				<ul>
				  <li><a href="shagym_tusiru.php">Шағым түсіру</a></li>
				  <li><a href="shagymdar.php">Шағымдарды көру</a></li>
				</ul>
			  </li>
			  <li><a href="../logout.php">Шығу</a></li>
			</ul>
		</nav>
	</div>
	</div>
	<div class = "content">
		<div class = "content_wrapper" style = "margin-top: 5px;">
			<div class = "inner_conten" style = "width: 1000px; margin: 0 auto;">
				<h2 style = "text-align: center;">Еңбек енгізуге рұқсат беру(ОПҚ үшін)</h2>			
				<?php
					$tutor_id=$_SESSION['tutor'];
					$query = mysqli_query($connection,"SELECT cafedras.cafedraID, cafedras.FacultyID, tutors.TutorID
					FROM cafedras
					INNER JOIN faculties ON faculties.FacultyID = cafedras.FacultyID
					INNER JOIN tutors ON tutors.CafedraID = cafedras.cafedraID
					WHERE Login = '$tutor_id'") or die(mysqli_error());
					$tutor = mysqli_fetch_array($query);
					$query = mysqli_query($connection,"SELECT tutors.typeID FROM tutors WHERE  Login = '$_SESSION[tutor]'") or die(mysqli_error($connection));
					$tutor1 = mysqli_fetch_array($query);
					$tt=$tutor1['typeID'];
					function load_korsetkish(){
                        global  $tt;
						global $connection;
						$output = '';
						$sql = "SELECT * FROM korsetkishter k
						WHERE k.bolimderID='1' ";
						$result = mysqli_query($connection,$sql) or die(mysqli_error($connection));
						
						while($row = mysqli_fetch_array($result)){			
							$output .= '<option value = "'.$row["kod_korsetkish"].'">' . $row["korsetkish_ati"] . '</option>';				
						}
						return $output;
					}
					
					function load_faculty(){
						global $connection;
						$output = '';
						$sql = "SELECT * FROM faculties";
						$result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
						
						while($row = mysqli_fetch_array($result)){			
							$output .= '<option value = "'.$row["FacultyID"].'">' . $row["facultyNameKZ"] . '</option>';				
						}
						return $output;
					}
                    
				?>
						<script>
							$(document).ready(function(){
								
								$("#faculty1").change(function(){
										var FacultyID = $(this).val();
										$.ajax({
										    url:"get_cafedra.php",
											method:"POST",
											data:{FacultyID:FacultyID},
											dataType:"text",
											success:function(data){
											    $("#cafedra").html(data);
											}
										});
								});
							    $("#cafedra").change(function(){
									var cafedraID = $(this).val();
									$.ajax({
										url:"get_tutor.php",
										method:"POST",
										data:{cafedraID:cafedraID},
										dataType:"text",
										success:function(data){
											$("#tutor").html(data);
										}
									});
								});
								$("#korsetkish").change(function(){
									var kod_korsetkish = $("#korsetkish option:selected").text();
									$.ajax({
										method:"POST",
										data:{kod_korsetkish:kod_korsetkish},
										dataType:"text",
										success:function(data){
											$("#tolyk_korset").text(kod_korsetkish);
										}
									});
								});
								
								$("#select_sany").change(function(){
									var select_sany = $("#select_sany option:selected").text();
									var select_sanyID = $("#select_sany option:selected").val();
									$.ajax({
										method:"POST",
										data:{select_sany:select_sany},
										dataType:"text",
										success:function(data){
											$("#label_sany").text(select_sany);							
										}
									});
								});
							});
						</script>
				<div class = "select_box">
					<form method = "post" action = "load_dostup.php" style = "margin-top: 10px;" enctype = "multipart/form-data">
						Көрсеткіштер
						<select name = "korsetkish" id = "korsetkish">
							<option>---</option>
								<?php echo load_korsetkish(); ?>
						</select><br /><br />
						<span>Көрсеткіштің толық атауы</span><br />
						<textarea rows="8" cols="109" name = "tolyk_korset" id = "tolyk_korset" style = "font-size: 18px; font-family: Tahoma; margin-top: 8px; border-radius:4px;"></textarea><br /><br />
						Факультет немесе ҒЗО
						<select name = "faculty" id = "faculty1">
							<option>Факультетті немесе ҒЗО-ны таңдаңыз</option>
								<?php echo load_faculty(); ?>
						</select><br /><br />
						Кафедра немесе ҒЗИ
						<select name = "cafedra" id = "cafedra">
							<option>Кафедраны немесе ҒЗИ-ды таңдаңыз</option>
						</select><br /><br />
                        Оқытушы немесе ғылыми қызметкер
						<select name = "tutor" id = "tutor">
							<option>Оқытушы немесе ғылыми қызметкерді таңдаңыз</option>
						</select><br /><br />
						<br /><hr />
						<input type = "hidden" name = "tutor_id" value = "<?php echo $tutor['TutorID'];?>"/>
						<input type = "hidden" name = "save_date" value = "<?php date_default_timezone_set("Asia/Dhaka"); echo date("d/m/Y H:i:s");?>"/>						
						<!--<br>Деректер қоры жабылды! 01.06.2020 00:00<br/>-->
                                                <input type = "submit" name = "dpopk" value = "Рұқсат беру"/>
					</form>
				</div>
				<div class = "works">
					<style>
						table {
							border-collapse: collapse;
							border:1px black solid;
							width: 100%;
							font-size: 12px;
						}

						th, td {
							text-align: left;
							padding: 6px;
							border:1px black solid;
						}

						th {
							background-color: #003366;
							color: white;
						}
					</style>

						<table>
							<thead>
							<tr>
								<th>№</th>
								<th>Кафедра/ҒЗИ</th>
								<th>Аты жөні</th>
								<th>Көрсеткіш</th>
							</tr>
							</thead>
							<tbody>
					<?php
						$query = mysqli_query($connection,"SELECT tutors.TutorID FROM tutors WHERE  Login = '$_SESSION[tutor]'") or die(mysqli_error($connection));
						$tutor2 = mysqli_fetch_array($query);
						
						$tutor_id = $tutor2['TutorID'];
						
						$sql = "SELECT d.Id, c.cafedraNameKZ, t.lastname, t.firstname, k.korsetkish_ati FROM dostupkorset d 
						join korsetkishter k on k.kod_korsetkish=d.korsetkishID
						join tutors t  on t.TutorID = d.tutorID
						join cafedras c on c.cafedraID = d.cafedraID
						WHERE d.moderator_id = '$tutor_id' and d.dostup=0 ORDER BY d.Id DESC";
						
						$result = mysqli_query($connection,$sql) or die(mysqli_error($connection));
						
						$i = 1;
						
						while($row = mysqli_fetch_array($result)){
							echo "<tr>";
                            echo "<td>".$i."</td>";
                            echo "<td>".$row["cafedraNameKZ"]."</td>";
                            echo "<td>".$row["lastname"]." ".$row["firstname"]."</td>";
                            echo "<td>".$row["korsetkish_ati"]."</td>";
							echo "<td><button class='btn btn-danger delete-btn' data-korset=".$row['Id']." onClick='deleteKor(this)'>Өшіру</button></td>";	
                            echo "</tr>";
							$i++;
						}
						
					?>
					</tbody>
				  </table>
				</div>
			</div>
		</div>
	</div>
	<div class = "footer">
	</div>
</body>
<?php include '../extensions/scripts_dostup.php'; ?>
</html>