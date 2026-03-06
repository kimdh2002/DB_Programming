<?php

# 선수단 관리 메인 화면

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");
$get_value = $_GET['stat'] ?? '';  // 버튼을 누르지 않아서 값이 전달되지 않았다면 빈 문자열로 처리한다

// 버튼을 눌렀을 때 sort값이 넘어왔다면 정렬 값을 SELECT
if ($get_value) {

    $sql ="SELECT p.Player_Code, p.Name, p.Back_Number, p.Position,
    SUM(ri.Goal) AS Goal, SUM(ri.Assist) AS Assist, COUNT(ri.Cap) AS Cap, AVG(ri.Grade) AS Grade    # 총 누적 스탯들
    FROM Player AS p 
    INNER JOIN Result_Info AS ri ON p.Player_Code = ri.Player_Code # Player_Code로 Player와 Result_Info 테이블을 조인
    GROUP BY p.Player_Code, p.Name, p.Back_Number, p.Position   # GROUP BY로 선수별 스탯을 합쳐 집계
    ORDER BY $get_value DESC    # 버튼 클릭으로 받은 결과를 내림차순으로 정렬
    LIMIT 5";   // TOP5 이므로 5개만 출력

    // 쿼리 실행
    $ret = mysqli_query($con, $sql);   
    if($ret) {
        $count = mysqli_num_rows($ret);
    } else {
        echo "선수단 데이터 검색 실패!!!"."<br>";
        echo "실패 원인 :".mysqli_error($con);
        exit();
    }

    // 선수단 출력 코드
    echo "<h1> TOP 5 </h1>";
    echo "<TABLE BORDER=1>";
    echo "<TR>";
    echo "<TH>선수 코드</TH> <TH>이름</TH> <TH>등번호</TH> <TH>포지션</TH> <TH>골</TH> <TH>어시스트</TH> <TH>출전 횟수</TH> <TH>평균 평점</TH>";
    echo "</TR>";
    while($row = mysqli_fetch_array($ret)) {

        // 코드의 고유한 식별자가 될 수 있게 고유한 코드를 앞에 같이 출력
        $plc = sprintf('PLC%04d', $row['Player_Code']);
        echo "<TR>";
        echo "<TD>", $plc, "</TD>";
        echo "<TD>", $row['Name'], "</TD>";
        echo "<TD>", $row['Back_Number'], "</TD>";
        echo "<TD>", $row['Position'], "</TD>";
        echo "<TD>", $row['Goal'], "</TD>";
        echo "<TD>", $row['Assist'], "</TD>";
        echo "<TD>", $row['Cap'], "</TD>";
        echo "<TD>",  number_format($row['Grade'], 2), "</TD>";
        echo "</TR>";
    }
    mysqli_close($con);
    echo "</TABLE>"; 
}
?>

<html>
<head>
   <link rel="stylesheet" href="info_style.css">
</head>

 <div>
   <form method="get" action="stat_ranking.php">
      <button type="submit" name="stat" value="Goal" style="width:100pt; height:45pt">골 TOP5</button><br>
      <button type="submit" name="stat" value="Assist" style="width:100pt; height:45pt">어시스트 TOP5 </button><br>
      <button type="submit" name="stat" value="Cap" style="width:100pt; height:45pt">출장 횟수 TOP5</button><br>
      <button type="submit" name="stat" value="Grade" style="width:100pt; height:45pt">평균 평점 TOP5</button><br>
   </form>
</div>

<!-- 선수 스탯 입력 전에 TOP5를 확인하러 올 수도 있으니 이전 페이지로 이동할 수 있게 history.back 사용-->
<button type = "submit" style="width:90pt; height:45pt" onclick="history.back()"> 뒤로가기</button>
