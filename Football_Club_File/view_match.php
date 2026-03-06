<?php

# 해당 경기에서 구단 선수들의 출전 명단과 골, 어시, 평점을 확인할 수 있는 코드

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");

// Match_Code를 전달받음
$match_code = $_POST["Match_Code"];

// 제목에 경기 정보를 출력하기 위한 코드
$info = "SELECT Match_Day, Match_Team FROM Match_Info WHERE Match_Code = $match_code"; // Match_Info 테이블의 Match_Day, Match_Team 속성 값을 $match_code에 맞게 가져온다
$ret_info = mysqli_query($con, $info); // 쿼리 실행
$row_info = mysqli_fetch_assoc($ret_info);  // 연관 배열을 리턴
echo "<h1>".$row_info['Match_Day']."일 ".$row_info['Match_Team']."전</h1>";

echo "<TABLE BORDER=1>";
echo "<h2> 출전 선수 </h2>";
echo "<TR>";

// 경기에 출전한 선수들만 JOIN
$sql = "SELECT p.Player_Code, p.Name, p.Birth, p.Country, p.Position, p.Back_Number,    # 별명 설정
ri.Goal, ri.Assist, ri.Grade
FROM Player AS p  # Player 테이블로 부터
INNER JOIN Result_Info AS ri ON p.Player_Code = ri.Player_Code  # Result_Info와 Player_Code를 Player_Code로 내부 조인
WHERE ri.Match_Code=$match_code AND ri.Cap=TRUE";       // match_code에 해당하고 출전한 선수만 가져온다

$ret = mysqli_query($con, $sql); // 쿼리 실행

echo "<TH>선수 코드</TH> <TH>이름</TH> <TH>생년월일</TH> <TH>국적</TH><TH>포지션</TH> <TH>등번호</TH>  <TH>골</TH><TH>어시스트</TH> <TH>평점</TH>";
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
   echo "<TD>", $row['Goal'], "</TD>";
   echo "<TD>", $row['Assist'], "</TD>";
   echo "<TD>", number_format($row['Grade'], 1), "</TD>";  // 평균 평점 출력 (소수점 1번째 자리까지 출력)
   echo "</TR>";
}

mysqli_close($con);

echo "</TABLE><br><br>";
?>
<html>
   <head>
      <link rel="stylesheet" href="info_style.css">
   </head>
        <FORM action = "Match_Info.php" method="get">
                <button type = "submit" style="width:90pt; height:45pt"> 뒤로가기</button>
        </FORM>
</html>