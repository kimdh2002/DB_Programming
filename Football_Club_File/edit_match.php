<?php

# 해당 경기에 무슨 선수들이 출전했는지 체크박스를 통해 알고, 그 명단을 넘기는 코드
$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");

$match_code = $_POST['Match_Code'];    // match_code를 받아옴

// 해당 경기의 결과를 가져온다
$check = "SELECT Match_Result FROM Match_Info WHERE Match_Code = $match_code";
$ret_check = mysqli_query($con, $check);  // 쿼리 실행

// 해당 경기의 결과가 예정이 아닌 이미 입력되었다면 더이상 입력할 수 없음
if($ret_check) {
   $row_check = mysqli_fetch_assoc($ret_check); // 연관 배열 리턴
   $match_result = $row_check['Match_Result'];  // 해당 행의 결과를 저장

   // 만약 결과가 입력되서 값이 예정이 아니라면
   if ($match_result != "예정") {
      echo "<script> alert('해당 경기의 결과는 이미 입력되었습니다.')
      location.href='Match_Info.php' </script>";   // Match_Info.php로 이동
      exit();  // 종료
   }
} else {
   echo "경기 조회 실패!!!"."<BR>";
   echo "실패 원인 : ".mysqli_error($con);
}

$sql = "SELECT * FROM Player";   // Player 테이블의 모든 속성 값 SELECT

// 쿼리 실행
$ret = mysqli_query($con, $sql);   
if($ret) {
   $count = mysqli_num_rows($ret);
}
else {
   echo "선수단 데이터 검색 실패!!!"."<br>";
   echo "실패 원인 :".mysqli_error($con);
   exit();
}

// 제목에 경기 정보를 출력하기 위한 코드
$info = "SELECT Match_Day, Match_Team FROM Match_Info WHERE Match_Code = $match_code";
$ret_info = mysqli_query($con, $info); // 쿼리 실행
$row_info = mysqli_fetch_assoc($ret_info);  // 연관 배열을 리턴

// 선수단 출력 코드
echo "<h1>".$row_info['Match_Day']."일 ".$row_info['Match_Team']."전 출전 여부 체크</h1>";
echo "<TABLE BORDER=1>";
echo "<TR>";
echo "<TH>선수 코드</TH> <TH>이름</TH> <TH>생년월일</TH> <TH>국적</TH> <TH>포지션</TH> <TH>등번호</TH> <TH>출전 여부</TH>";
echo "</TR>";
while($row = mysqli_fetch_array($ret)) {

   // 코드의 고유한 식별자가 될 수 있게 고유한 코드를 앞에 같이 출력
   $plc = sprintf('PLC%04d', $row['Player_Code']);
   echo "<TR>";
   echo "<TD>", $plc, "</TD>";
   echo "<TD>", $row['Name'], "</TD>";
   echo "<TD>", $row['Birth'], "</TD>";
   echo "<TD>", $row['Country'], "</TD>";
   echo "<TD>", $row['Position'], "</TD>";
   echo "<TD>", $row['Back_Number'], "</TD>";

   $player_code = $row['Player_Code']; // 선수 코드를 저장
   // 체크된 선수들의 정보를 배열로 POST 전달
   echo "<form method = 'post' action= 'add_stat.php'>";
   echo "<TD><input type= 'CheckBox'  name = 'Players[]' value = '$player_code'> </TD>";
   echo "</TR>";
}
echo "</TABLE><br>"; 
// form을 지금 닫음으로써 체크된 선수들의 정보를 넘김
echo "<input type = 'hidden' name = 'Match_Code' value = '$match_code'>";
echo "<input type= 'submit'  value= '선수 스탯 입력'></form>";

mysqli_close($con);
?>

<html>
   <head>
      <link rel="stylesheet" href="info_style.css">
   </head>

   <FORM action = "stat_ranking.php" method="get">
         <button type = "submit" style="width:90pt; height:45pt"> 스탯별 TOP5 보러가기</button>
   </FORM>
</html>