<?php

# 선수단 관리 페이지에서 선수에 관해 검색할 수 있는 코드

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");

// 종류와 검색 내용을 가져온다
$kind = $_GET['Kind'];
$search = $_GET['Search'];

// 선수 테이블에서 검색하려는 종류(kind)에서 검색값(search)가 포함된 결과를 select
$sql = "SELECT * FROM Player WHERE $kind LIKE '%$search%'";
$ret = mysqli_query($con, $sql); // 쿼리 실행

if(!$ret) {
     echo "검색 실패 <br>";
     echo "실패 원인 :".mysqli_error($con);

     echo "<br> <a href='Club.php'> <--처음으로</a> ";
}

echo "<h1> 검색 결과 </h1>";
echo "<TABLE BORDER=1>";
echo "<TR>";
echo "<TH>선수 코드</TH> <TH>이름</TH> <TH>생년월일</TH> <TH>국적</TH> <TH>포지션</TH> <TH>등번호</TH> <TH>정보</TH> <TH>계약</TH>";
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
   
   // 선수 코드에 맞는 각각의 정보 탭으로 이동할 수 있도록 설정
   echo "<form method = 'post' action= 'view_player_info.php'>";  // view_player_info.php로 이동
   echo "<input type = 'hidden' name = 'Player_Code' value = '$player_code'>";
   echo "<TD><input type= 'submit'  value= '보기'</TD></form>";
   echo "<form method = 'post' action= 're_contract.php'>"; // re_contract.php로 이동
   echo "<input type = 'hidden' name = 'Player_Code' value = '$player_code'>";
   echo "<TD><input type= 'submit'  value= '갱신'</TD></form>";
   echo "</TR>";
}

mysqli_close($con);
echo "</TABLE><br><br>";
?>
<html>
   <head>
      <link rel="stylesheet" href="info_style.css">
   </head>

   <FORM action = "Club.php" method="get">
        <button type = "submit" style="width:90pt; height:45pt"> 뒤로</button>
     </FORM>
</html>