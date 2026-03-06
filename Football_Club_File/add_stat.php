<?php

# 입력받은 선수들의 개개인의 스탯들을 DB에 저장하는 코드

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");
$players = $_POST['Players'];   // edit_match.php에서 받은 체크된 선수들의 player_code를 배열 형태로 받음
$match_code = $_POST['Match_Code'];

// 한 경기에 최소 11명의 선수가 출전해야함
if(count($players) < 11) {
    // 11명 이하라면 이전 화면으로 돌아간다
    echo "<script> alert('최소 출전 인원 수는 11명 이상입니다.')
    location.href='Match_Info.php' </script>";  // Match_Info.php로 이동
} else {
    $list = implode(",", $players); // 배열 그대로 SELECT를 진행하면 오류 발생 -> 배열을 문자열로 변환 ex) 1,2,3,4
    $sql = "SELECT * FROM Player WHERE Player_Code IN ($list)"; // 변환된 문자열은 범위가 되어 IN으로 해당하는 선수 코드의 선수들을 가져옴

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
    echo "<h1>출전 명단</h1>";
    echo "<TABLE BORDER=1>";
    echo "<TR>";
    echo "<TH>선수 코드</TH> <TH>이름</TH> <TH>생년월일</TH> <TH>국적</TH> <TH>포지션</TH> <TH>등번호</TH>  <TH>골</TH>  <TH>어시스트</TH>  <TH>평점</TH>";
    echo "</TR>";
    echo "<form method = 'post' action= 'save_stat.php'>";
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
    // 골과 어시, 평점은 선수코드 -> 골 이런 식으로 저장되어 배열로 전송
    echo "<TD><input type='number' name='Goal[$player_code]' value='0' min='0'></TD>";
    echo "<TD><input type='number' name='Assist[$player_code]' value='0' min='0'></TD>";
    echo "<TD><input type='number' name='Grade[$player_code]' value='6.0' min='0.0' max='10.0' step='0.1'></TD>";  // 평점은 최대 10.0
    }
    echo "</TABLE><br>"; 
    // form을 지금 닫음으로써 선수들의 정보를 넘김

    echo "<h2> 경기 결과 입력 (우리 팀-상대 팀) </h2>";
    echo "<input type='text' name='Result'> <br><br>";
    echo "<input type = 'hidden' name = 'Match_Code' value = '$match_code'>";   // 버튼을 누르면 match_code도 함께 전송
    echo "<TD><input type= 'submit'  value= '저장'</TD></form>";

    mysqli_close($con);
}

?>

<html>
   <head>
      <link rel="stylesheet" href="info_style.css">
   </head>
</html>