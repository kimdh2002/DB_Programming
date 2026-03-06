<?php

# 관리 이력을 확인하는 코드

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");

// 선수 이름을 가져오기 위해 JOIN
$sql = "SELECT p.Name, m.Management_Record_Code,m.Manager_ID, m.Player_Code, m.Management_Date, m.Management_Type  
FROM Player AS p    # Player 테이블로 부터
RIGHT JOIN Management AS m ON p.Player_Code = m.Player_Code"; # Player_Code를 기준으로 Management와 Player 테이블 연결

$ret = mysqli_query($con, $sql);   
if($ret) {
    $count = mysqli_num_rows($ret);  // 행의 개수를 저장
}
else {
    echo "선수단 데이터 검색 실패!!!"."<br>";
    echo "실패 원인 :".mysqli_error($con);
    exit(); // 종료
}

// 부상자 명단 출력 코드
if($count > 0) { // 저장한 행의 개수가 0일 시에는 부상자가 현재 존재하지 않음
    echo "<h1> 관리 이력 확인 </h1>";
    echo "<TABLE BORDER=1>";
    echo "<TR>";
    echo "<TH>관리 코드</TH> <TH>관리자</TH><TH>선수 이름</TH> <TH>날짜</TH> <TH>유형</TH>";
    echo "</TR>";

    // 행에서 값들을 가져온다
    while($row = mysqli_fetch_array($ret)) {

        // 코드의 고유한 식별자가 될 수 있게 고유한 코드를 앞에 같이 출력
        $mrc = sprintf('MRC%04d', $row['Management_Record_Code']);
        echo "<TR>";
        echo "<TD>", $mrc, "</TD>";
         echo "<TD>", $row['Manager_ID'], "</TD>";
         
        // 선수가 DB에서 삭제되면 NULL로 저장되어 있기 때문에 NULL일 경우 삭제됐다고 알려주기
        if($row['Name'] == NULL) {
            echo "<TD> 계약 해지된 선수 </TD>";
        } else { echo "<TD>", $row['Name'], "</TD>"; }

        echo "<TD>", $row['Management_Date'], "</TD>";
        echo "<TD>", $row['Management_Type'], "</TD>";
        echo "</TR>";
    } 
} else { echo "<h1> 관리 이력 없음 </h1> <br><br><br><br>"; }

mysqli_close($con);
echo "</TABLE><br><br>"; 
?>

<head>
    <link rel="stylesheet" href="info_style.css">
</head>
<FORM action = "Main_System.html" method="get">
    <button type = "submit" style="width:90pt; height:45pt"> 뒤로가기</button>
</FORM>