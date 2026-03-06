<?php

# 선수들의 부상 현황과 상태를 확인하고 부상자를 추가하는 코드

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");
$sort_value = $_GET['sort'] ?? '';  // 버튼을 누르지 않아서 값이 전달되지 않았다면 빈 문자열로 처리한다

// ================================== 현재 부상자를 확인 하는 코드 ==================================
$injury_ing = 
"SELECT
p.Player_Code, p.Name, p.Birth, # Player = p 별칭 지정
i.Injury_Code, i.Injury_Name, i.Injury_Part, # Injury = i 별칭 지정
ic.Injury_Case_Code, ic.Injury_Date, ic.Estimated_Period, # Injury_Check = ic 별칭 지정
ijs.Recovery_Date, ijs.Status    # Injury_Recovery = ijr 별칭 지정

FROM Player AS p   # Player 테이블로부터

INNER JOIN Injury_Check AS ic ON p.Player_Code = ic.Player_Code # Player_Code로 Player와 Injury_Check 테이블을 조인
INNER JOIN Injury AS i ON i.Injury_Code = ic.Injury_Code # Injury_Code로 Injury와 Injury_Check 테이블을 조인
INNER JOIN Injury_Status AS ijs ON ic.Injury_Case_Code = ijs.Injury_Case_Code  # Injury_Status를 Player_Code와 Injury_Code로 연결
WHERE ijs.Status != '회복'"; # 완치된 선수를 제외하고 부상 중인 선수만 가져온다

// 쿼리 실행
$ret_ing = mysqli_query($con, $injury_ing);
if($ret_ing) {
    $count = mysqli_num_rows($ret_ing);  // 행의 개수를 저장
}
    else {
    echo "선수단 데이터 검색 실패!!!"."<br>";
    echo "실패 원인 :".mysqli_error($con);
    exit(); // 종료
}

// 부상자 명단 출력 코드
if($count > 0) { // 저장한 행의 개수가 0일 시에는 부상자가 현재 존재하지 않음
echo "<h1> 부상자 명단</h1>";
echo "<TABLE BORDER=1>";
echo "<TR>";
echo "<TH>부상 사건 코드</TH> <TH>선수 코드</TH> <TH>이름</TH> <TH>생년월일</TH> <TH>부상명</TH> <TH>부상 부위</TH> 
        <TH>부상 날짜</TH> <TH>예상 회복 기간</TH> <TH>상태</TH> <TH>변경</TH>";
echo "</TR>";

// 행에서 값들을 가져온다
while($row = mysqli_fetch_array($ret_ing)) {
    // 코드의 고유한 식별자가 될 수 있게 고유한 코드를 앞에 같이 출력
    $icc = sprintf('ICC%04d', $row['Injury_Case_Code']);
    $plc = sprintf('PLC%04d', $row['Player_Code']);
    echo "<TR>";
    echo "<TD>", $icc, "</TD>";
    echo "<TD>", $plc, "</TD>";
    echo "<TD>", $row['Name'], "</TD>";
    echo "<TD>", $row['Birth'], "</TD>";
    echo "<TD>", $row['Injury_Name'], "</TD>";
    echo "<TD>", $row['Injury_Part'], "</TD>";
    echo "<TD>", $row['Injury_Date'], "</TD>";
    echo "<TD>", $row['Estimated_Period'], "</TD>";
    echo "<TD>", $row['Status'], "</TD>";
    
    // 해당 행에 있는 부상 사건 코드를 저장
    $injury_case_code = $row['Injury_Case_Code'];
    // 버튼을 누를 시에 해당 행에 있는 부상 사건 코드를 Change_Status로 전송한다
    echo "<form method = 'post' action= 'Change_Status.php'>";
    echo "<input type = 'hidden' name = 'Injury_Case_Code' value = '$injury_case_code'>";
    echo "<TD><input type= 'submit'  value= '상태 변경'</TD>";
    echo "</form>";
    echo "</TR>";
} 
} else { echo "<h1> 현재 부상자 없음 </h1> <br><br><br><br>"; }
echo "</TABLE>";

// ================================== 부상 전체 내역을 확인 하는 코드 ================================== 
$injury_history =
"SELECT
p.Player_Code, p.Name, p.Birth, # Player = p 별칭 지정
i.Injury_Code, i.Injury_Name, i.Injury_Part, # Injury = i 별칭 지정
ic.Injury_Case_Code, ic.Injury_Date, ic.Estimated_Period, # Injury_Check = ic 별칭 지정
ijs.Recovery_Date, ijs.Status    # Injury_Status 별칭 지정

FROM Player AS p    # Player 테이블로부터

INNER JOIN Injury_Check AS ic ON p.Player_Code = ic.Player_Code # Player_Code로 Player와 Injury_Check 테이블을 조인
INNER JOIN Injury AS i ON i.Injury_Code = ic.Injury_Code # Injury_Code로 Injury와 Injury_Check 테이블을 조인
INNER JOIN Injury_Status AS ijs ON ic.Injury_Case_Code = ijs.Injury_Case_Code";    # Injury_Status를 Player_Code와 Injury_Code로 연결

// 정렬 종류에 맞춰서 ORDER BY를 통해 정렬을 진행한다
if ($sort_value == 'Status') {  // 만약 정렬 기준이 상태라면
    $injury_history .= " ORDER BY FIELD($sort_value, '회복', '회복 중')";   // 회복을 우선적으로 정렬한다.
} else if ($sort_value){    // 정렬 버튼을 눌렀을 경우
    $injury_history .= " ORDER BY $sort_value"; // 정렬 기준에 맞춰 정렬을 진행
}

// 쿼리 실행 
$ret_history = mysqli_query($con, $injury_history);   
if($ret_history) {
    $count = mysqli_num_rows($ret_history); // 행의 개수를 저장
} else {
    echo "선수단 데이터 검색 실패!!!"."<br>";
    echo "실패 원인 :".mysqli_error($con);
    exit();
}

// 부상 내역 출력 코드
echo "<h1> 부상자 내역</h1>";

// 정렬 셀렉트 박스 생성
echo "<form met   hod=get action=''>";
echo "<select name='sort'>";
echo "<option value='Player_Code'>선수 코드</option>";
echo "<option value='Name'>이름</option>";
echo "<option value='Birth'>생년월일</option>";
echo "<option value='Injury_Name'>부상명</option>";
echo "<option value='Injury_Part'>부상부위</option>";
echo "<option value='Status'>상태</option>";
echo "</select> &nbsp"; // 셀렉트 박스랑 버튼이랑 한칸 띄우기
echo "<input type= 'submit'  value= '정렬'>";
echo "</form>";

echo "<TABLE BORDER=1>";
echo "<TR>";
echo "<TH>부상 사건 코드</TH> <TH>선수 코드</TH> <TH>이름</TH> <TH>생년월일</TH><TH>부상명</TH> <TH>부상 부위</TH> 
    <TH>부상 날짜</TH> <TH>회복 날짜</TH> <TH>상태</TH>";
echo "</TR>";
while($row = mysqli_fetch_array($ret_history)) {

    // 코드의 고유한 식별자가 될 수 있게 고유한 코드를 앞에 같이 출력
    $icc = sprintf('ICC%04d', $row['Injury_Case_Code']);
    $plc = sprintf('PLC%04d', $row['Player_Code']);
    echo "<TR>";
    echo "<TD>", $icc, "</TD>";
    echo "<TD>", $plc, "</TD>";
    echo "<TD>", $row['Name'], "</TD>";
    echo "<TD>", $row['Birth'], "</TD>";
    echo "<TD>", $row['Injury_Name'], "</TD>";
    echo "<TD>", $row['Injury_Part'], "</TD>";
    echo "<TD>", $row['Injury_Date'], "</TD>";

    // 회복 날짜가 NULL이라면 미정으로 출력한다
    if($row['Recovery_Date'] == NULL) {
            echo "<TD> 미정 </TD>";
        } else { echo "<TD>", $row['Recovery_Date'], "</TD>"; }
    echo "<TD>", $row['Status'], "</TD>";
    
    echo "</TR>";
}
echo "</TABLE>"; 
echo "<br><br>";

?>

<html>
    <head>
        <link rel="stylesheet" href="info_style.css">
    </head>
<h1> 부상자 추가 </h1>
 <FORM METHOD="POST"  ACTION="Add_Injury_Player.php">
        <select id = "Player_Code" name="Player_Code">
            <option value=''> 선수 선택 </option>
            <?php
            $sql = "SELECT Player_Code, Name, Back_Number FROM Player";
            $ret = mysqli_query($con, $sql);

            // 쿼리 결과를 option 태그로 출력
            while($row = mysqli_fetch_array($ret)) {
                // value를 player_code로, 출력을 등번호(선수 이름)으로 select 박스에 출력
                // {$row['Back_Number']} = 등번호 출력 ({$row['Injury_Code']}) = 이름 출력
               echo "<option value='{$row['Player_Code']}'>{$row['Back_Number']} ({$row['Name']}) </option>";
            }
            ?>
        </select> <br>
        <select id = "Injury_Code" name="Injury_Code">
            <option value=''> 부상명 </option>
            <?php
            $sql = "SELECT Injury_Code, Injury_Name FROM Injury";
            $ret = mysqli_query($con, $sql);

            // 쿼리 결과를 option 태그로 출력
            while($row = mysqli_fetch_array($ret)) {
                // value를 Injury_code로, 출력을 부상 코드(부상명)으로 select 박스에 출력
                // {$row['Injury_Code']} = 부상 코드 출력 ({$row['Injury_Code']}) = 부상 명 출력
               echo "<option value='{$row['Injury_Code']}'> {$row['Injury_Code']} ({$row['Injury_Name']}) </option>";
            }
            ?>
        </select> <br>
        예상 기간 : <INPUT TYPE ="text" NAME="Estimated_Period"> <br> 
        관리자 ID : <INPUT TYPE ="text" NAME="Manager_ID"> <br> 
        비밀번호 : <INPUT TYPE ="password" NAME="PassWord"> <br><br>
        <INPUT TYPE="submit" style="width:90pt; height:45pt" VALUE="등록">
        <?php mysqli_close($con); ?>    <!-- sql 닫음 -->
    </FORM>

    <br><br>
<FORM action = "Club.php" method="get">
        <button type = "submit" style="width:90pt; height:45pt"> 뒤로가기</button>
</FORM>
</html>