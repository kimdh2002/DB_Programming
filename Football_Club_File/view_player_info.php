<?php

# 선수의 개인 스탯과 정보, 계약 정보를 확인하는 코드

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");

// Player_Code를 전달받음
$player_code = $_POST["Player_Code"];

$sql =
"SELECT * FROM Player WHERE Player_Code = $player_code";    // Player 테이블에서 $player_code에 해당하는 선수의 정보를 가져옴

$ret = mysqli_query($con, $sql);   
if($ret) {
    $count = mysqli_num_rows($ret);
}
else {
    echo "데이터 검색 실패!!!"."<br>";
    echo "실패 원인 :".mysqli_error($con);
    exit(); // 종료
} 

// 제목에 선수 이름을 출력하기 위한 코드
$name = "SELECT Name FROM Player WHERE Player_Code=$player_code";   // Player 테이블에서 $player_code에 해당하는 이름을 가져온다
$ret_name = mysqli_query($con, $name);  // 쿼리 실행
$row_name = mysqli_fetch_assoc($ret_name);  // 연관 배열을 리턴
echo "<TABLE BORDER=1>";
echo "<h1>".$row_name['Name']." 선수 정보</h1>";
echo "<TR>";
echo "<TH>선수 코드</TH> <TH>이름</TH> <TH>생년월일</TH> <TH>국적</TH><TH>포지션</TH> <TH>등번호</TH>";
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
   echo "</TR>";
}
echo "<br>";

$stat =
// 만약 NULL이라면 0으로 출력하도록 함
// 선수 코드로 Result_Info에 기록된 선수의 골, 어시, 출전 횟수, 평점을 가져온다
"SELECT IFNULL(SUM(Goal), 0), IFNULL(SUM(Assist), 0), COUNT(Cap), Avg(Grade) FROM Result_Info WHERE Player_Code = $player_code";
$ret_stat = mysqli_query($con, $stat);  // 쿼리 실행

echo "<TABLE BORDER=1>";
echo "<h1>".$row_name['Name']." 선수 누적 스탯</h1>";
echo "<TR>";
echo "<TH>누적 골</TH> <TH>누적 어시스트 </TH> <TH>총 줄장 횟수</TH> <TH>평균 평점</TH>";
echo "</TR>";
while($row = mysqli_fetch_array($ret_stat)) {
    echo "<TR>";
    // row가 배열로 저장됨 (goal -> assist -> cap -> grade 순으로 저장됨)
    echo "<TD>", $row[0], "</TD>";  // 누적 골 출력
    echo "<TD>", $row[1], "</TD>";  // 누적 어시스트 출력
    echo "<TD>", $row[2], "</TD>";  // 총 줄장 횟수 출력
    echo "<TD>", number_format($row[3], 2), "</TD>";  // 평균 평점 출력 (소수점 2번째 자리까지 출력)
    echo "</TR>";
    $data_array[] = ($row); // 모든 데이터 담기
}
$chart = json_encode($data_array);   //배열을 json으로 encode 해서 변수에 할당

// 선수의 계약을 가져온다
$contract = "SELECT * FROM Contract WHERE Player_Code = $player_code";  // 계약 테이블에서 $player_code에 해당하는 정보를 모두 가져온다
$ret_con = mysqli_query($con, $contract);   // 쿼리 실행

// 계약 정보 테이블 출력
echo "<TABLE BORDER=1>";
echo "<h1>".$row_name['Name']." 선수 계약 정보</h1>";
echo "<TR>";
echo "<TH>계약 기간</TH> <TH>현재 연봉 </TH>";
echo "</TR>";
while($row = mysqli_fetch_array($ret_con)) {
    echo "<TR>";
    echo "<TD>", "~",$row['Contract_Period'], "</TD>";
    echo "<TD>", number_format($row['Annual_Income']), "</TD>";  // 천 단위 콤마 사용
    echo "</TR>";
}

mysqli_close($con);

echo "</TABLE><br><br>";
?>

<!-- 선수 정보 수정하는 버튼 만들기 -->
<FORM action = "edit_player.php" method="post">
<!-- 버튼을 누르면 해당 선수의 선수 코드를 전송한다-->
    <input type="hidden" name = "Player_Code" value="<?= $player_code ?>">
    <input type="submit" style="width:90pt; height:45pt" value="선수 정보 변경">
</FORM>

<!-- 그래프 그리는 코드-->
<!DOCTYPE html>
<html lang="ko">

<head>
<link rel="stylesheet" href="info_style.css">   <!-- css파일 가져오기 -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {'packages':['corechart','bar']});
google.charts.setOnLoadCallback(drawChart);

function drawChart(){
    var chartData = <?php echo $chart; ?>;   // 인코딩한 데이터 가져오기
    var data = google.visualization.arrayToDataTable([
    ['항목','값'],
    ['누적 골', parseInt(chartData[0][0])],
    ['누적 어시스트', parseInt(chartData[0][1])],
    ['총 줄장 횟수', parseInt(chartData[0][2])],
    ['평균 평점', parseFloat(chartData[0][3])]
    ]);

    var options = {
        title: '누적 스탯',
        chartArea: {width: '80%'},
        hAxis: {
            title: '수치',
            minValue: 0
        }
    };

    var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
    chart.draw(data, options);
}
</script>
</head>
<body>
<div id="chart_div" style = "width:800px; height:450px; margin-top:20px"></div>
</body>
</html>

<FORM action = "Club.php" method="get">
        <button type = "submit" style="width:90pt; height:45pt"> 뒤로가기</button>
</FORM>
</html>