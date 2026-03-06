<?php

# 선수단 관리 메인 화면

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");
$sort_value = $_GET['sort'] ?? '';  // 버튼을 누르지 않아서 값이 전달되지 않았다면 빈 문자열로 처리한다

// 버튼을 눌렀을 때 sort값이 넘어왔다면 정렬 값을 SELECT
if ($sort_value == 'Position') { // 포지션은 GK > DF > MF > FW 순으로 정렬해야 하니 FIELD로 우선순위를 정함
   $sql = "SELECT * FROM Player ORDER BY FIELD(Position, 'GK', 'DF', 'MF', 'FW')";
} else if ($sort_value) {
   $sql ="SELECT * FROM Player ORDER BY $sort_value"; // 포지션을 제외한 값이 전달 됐다면 그 값을 매개로 정렬
   } else {
      // 버튼을 누른 상태가 아니라면 모든 값 SELECT
      $sql = "SELECT * FROM Player";
   }

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

// 선수단 출력 코드
echo "<h1> 선수단 </h1>";

// 정렬 셀렉트 박스 생성
echo "<form met   hod=get action=''>";
echo "<select name='sort'>";
echo "<option value='Player_Code'>선수 코드</option>";
echo "<option value='Name'>이름</option>";
echo "<option value='Birth'>생년월일</option>";
echo "<option value='Position'>포지션</option>";
echo "<option value='Back_Number'>등번호</option>";
echo "</select> &nbsp"; // 셀렉트 박스랑 버튼이랑 한칸 띄우기
echo "<input type= 'submit'  value= '정렬'>";
echo "</form>";

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

   $player_code = $row['Player_Code']; // 해당 행의 선수 코드를 저장

   
   // 선수 코드에 맞는 각각의 정보 탭으로 이동할 수 있도록 설정
   echo "<form method = 'post' action= 'view_player_info.php'>";  // 버튼 누르면 view_player_info.php로 이동
   echo "<input type = 'hidden' name = 'Player_Code' value = '$player_code'>";   // 버튼 누르면 player_code 전달
   echo "<TD>", "<input type= 'submit'  value= '보기'>", "</TD> </form>";

   echo "<form method = 'post' action= 're_contract.php'>"; // 버튼  누르면 re_contract.php로 이동
   echo "<input type = 'hidden' name = 'Player_Code' value = '$player_code'>";   // 버튼 누르면 player_code 전달
   echo "<TD>", "<input type= 'submit'  value= '갱신'>", "</TD> </form>";
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
      <form method=GET name=frm1 action='search_player.php'>
      <!-- Kind라는 이름의 셀렉트 박스로 어떤 종류의 내용을 검색하는지-->
         <select name = Kind>
            <option value="Name" selected>이름
            <option value="Position" selected>포지션
            <option value="Back_Number" selected>등번호 </option>
         </select>
      
      <!-- 찾고자 하는 값을 Search라는 이름으로 전송 -->
      <input type=text size=30 name=Search>
      <input type="submit"  value="검색">
      </form>
   </div>

   <div>
   <form method="get" action="stat_ranking.php">
      <h1> 스탯별 TOP5 </h1>
      <input type="submit" name="add" style="width:90pt; height:45pt" value="이동"><br>
   </form>
   </div>

   <div>
   <form method="get" action="Player.html">
      <h1> 선수 추가 </h1>
      <input type="submit" name="add" style="width:90pt; height:45pt" value="추가"><br>
   </form>
   </div>

   <div>
   <form method="get" action="Management_Injury.php">
      <h1> 부상 관리 </h1>
      <input type="submit" name="mng" style="width:90pt; height:45pt"value="이동" size="40"><br><br>
   </form>
   </div>

   <FORM action = "Main_System.html" method="get">
         <button type = "submit" style="width:90pt; height:45pt"> 메인 화면으로</button>
   </FORM>
   </html>