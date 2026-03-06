<?php

# 경기 정보 페이지에서 경기에 관해 검색할 수 있는 코드

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");

// 종류와 검색 내용을 가져온다
$kind = $_GET['Kind'];
$search = $_GET['Search'];

// 경기 테이블에서 검색하려는 종류(kind)에서 검색값(search)가 포함된 결과를 select
$sql = "SELECT * FROM Match_Info WHERE $kind LIKE '%$search%'";
$ret = mysqli_query($con, $sql); // 쿼리 실행

if(!$ret) {
     echo "검색 실패 <br>";
     echo "실패 원인 :".mysqli_error($con);
}

// 경기 정보 출력 코드
echo "<h1> 검색 결과 </h1>";

echo "<TABLE BORDER=1>";
echo "<TR>";
echo "<TH>경기 코드</TH> <TH>경기 날짜</TH> <TH>장소</TH> <TH>상대 팀</TH> <TH>경기 결과</TH> <TH> 결과 입력</TH> <TH> 정보</TH>";
echo "</TR>";
while($row = mysqli_fetch_array($ret)) {

   // 코드의 고유한 식별자가 될 수 있게 고유한 코드를 앞에 같이 출력
   $mi = sprintf('M%04d', $row['Match_Code']);
   echo "<TR>";
   echo "<TD>", $mi, "</TD>";
   echo "<TD>", $row['Match_Day'], "</TD>";
   echo "<TD>", $row['Match_Place'], "</TD>";
   echo "<TD>", $row['Match_Team'], "</TD>";
   echo "<TD>", $row['Match_Result'], "</TD>";

   $match_code = $row['Match_Code']; // 경기기 코드를 저장
   
   // 경기 코드에 맞는 각각의 정보 탭으로 이동할 수 있도록 설정
   echo "<form method = 'post' action= 'edit_match.php'>";
   echo "<input type = 'hidden' name = 'Match_Code' value = '$match_code'>";
   echo "<TD><input type= 'submit' value= '입력하기'</TD></form>";
   echo "<form method = 'post' action= 'view_match.php'>";
   echo "<input type = 'hidden' name = 'Match_Code' value = '$match_code'>";
   echo "<TD><input type= 'submit'  value= '보기'</TD></form>";
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
        <button type = "submit" style="width:90pt; height:45pt"> 뒤로</button>
</FORM>
</html>