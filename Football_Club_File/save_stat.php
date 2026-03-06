<?php

# 선수들이 경기에서 쌓은 골, 어시, 평점 등의 데이터들을 DB에 저장하는 코드

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");

// 쿼리 실행 함수
function InputData($con, $sql, $kind) {
$ret = mysqli_query($con, $sql);
if ($ret) {
    echo "$kind 입력 성공<br>";
} else {
    echo "$kind 입력 실패!!!<br>";
    echo "실패 원인: " . mysqli_error($con) . "<br>";
    }
}


// 골과 어시, 평점은 선수코드 -> 골 이런 식으로 저장되어 있음
$match_code = $_POST['Match_Code'];
$result = $_POST['Result'];
$goals = $_POST['Goal'];
$assists = $_POST['Assist'];
$grades = $_POST['Grade'];

// 경기 결과가 아무것도 입력되지 않았다면 종료
if (empty($result)) {
    echo "<script> alert('경기 결과를 입력해야 합니다.')
    location.href='Match_Info.php' </script>";  // Match_Info.php로 이동한다.
    exit(); // 종료한다
}

// 배열의 모든 키를 가져와 저장함
$player_codes = array_keys($goals);

// 출전한 선수의 개수 만큼 반복한다
for($i=0; $i < count($player_codes); $i++) {
    $player_code = $player_codes[$i];   // player_codes에서 선수 코드를 저장한다
    $goal = $goals[$player_code];   // 선수 코드에 맞는 골 기록을 가져온다
    $assist = $assists[$player_code];   // 선수 코드에 맞는 어시스트 기록을 가져온다
    $grade = $grades[$player_code]; // 선수 코드에 맞는 평점 기록을 가져온다

    // Result_Info에 스탯을 저장한다.
    $sql = "INSERT INTO Result_Info (Match_Code, Player_Code, Goal, Assist, Cap, Grade) " .
    "VALUES ('$match_code', '$player_code', '$goal', '$assist',TRUE ,'$grade')";
        
    InputData($con, $sql, "선수 스탯");
}
    // 예정으로 되어 있던 경기 결과를 $result 값으로 변경한다
    $sql = "UPDATE Match_Info SET Match_Result='$result' WHERE Match_Code='$match_code'";
    $ret = mysqli_query($con, $sql);

    // 알림뜨게 하고 이동하기 위해서 쿼리 실행을 따로 진행
    if($ret) {
        // 알림창 뜨고 이동
        echo "<script> alert('저장 완료')
        location.href='Match_Info.php' </script>";  // Match_Info.php로 이동
    } else {
        echo "데이터 변경 실패!!!"."<br>";
        echo "실패 원인 :".mysqli_error($con);
        exit();
    }
?>