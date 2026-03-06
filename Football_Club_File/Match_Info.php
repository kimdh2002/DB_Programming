<?php

# 경기 관리 메인 화면

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");
$sql = "SELECT * FROM Match_INFO";  // 모든 경기 정보들을 가져온다

// 쿼리 실행
$ret = mysqli_query($con, $sql);   
if($ret) {
   $count = mysqli_num_rows($ret);
}
else {
   echo "경기 데이터 검색 실패!!!"."<br>";
   echo "실패 원인 :".mysqli_error($con);
   exit();
}

// 경기 정보 출력 코드
echo "<h1> 경기 정보 </h1>";

echo "<TABLE BORDER=1>";
echo "<TR>";
echo "<TH>경기 코드</TH> <TH>경기 날짜</TH> <TH>장소</TH> <TH>상대 팀</TH> <TH>경기 결과</TH> <TH>결과 입력 </TH><TH> 정보 </TH> ";
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
   echo "<form method = 'post' action= 'edit_match.php'>";  // edit_match.php로 이동
   echo "<input type = 'hidden' name = 'Match_Code' value = '$match_code'>";
   echo "<TD><input type= 'submit' value= '입력하기'</TD></form>";
   echo "<form method = 'post' action= 'view_match.php'>";  // view_match.php로 이동
   echo "<input type = 'hidden' name = 'Match_Code' value = '$match_code'>";
   echo "<TD><input type= 'submit'  value= '보기'</TD></form>";
   echo "</TR>";
}

mysqli_close($con);
echo "</TABLE>"; 
?>

<html>
   <head>
        <link rel="stylesheet" href="info_style.css">
    </head>
   <div>
      <!-- 검색 select 박스 생성 코드 -->
      <form method=GET name=frm1 action='search_match.php'>
         <select name = Kind>
            <option value="Match_Day" selected>경기 날짜
            <option value="Match_Place" selected>경기 장소
            <option value="Match_Team" selected>상대 팀 </option>
      </select>
      
      <input type=text size=30 name=Search>
      <input type="submit"  value="검색">
      </form>
   </div>

   <div>
   <form method="get" action="Match.html">
      <h1> 경기 일정 추가 </h1>
      <input type="submit" name="add" style="width:90pt; height:45pt" value="추가"><br>
   </form>
   </div>

   <FORM action = "Main_System.html" method="get">
         <button type = "submit" style="width:90pt; height:45pt"> 메인 화면으로</button>
   </FORM>
</html>