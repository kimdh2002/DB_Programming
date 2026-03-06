<?php

# 경기에 대한 정보를 받아 DB에 저장하는 코드

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

// 입력으로 받아온 값들 저장
$day = $_POST["Match_Day"];
$place = $_POST["Match_Place"];
$team = $_POST["Match_Team"];
$manager_id = $_POST["Manager_ID"];
$password = $_POST["PassWord"];

// 사용자가 입력하지 않은 것이 있을 경우 예외처리
if (empty($day) || empty($place) || empty($team) || empty($manager_id) || empty($password)) {
    echo "<script> alert('모든 항목을 입력해주세요.')
    location.href='Match_Info.php' </script>";  // Match_Info.php로 이동
    exit();
}

// SQL에 선언한 최대 크기를 벗어 난 경우
if (strlen($place) > 50 || strlen($team) > 50) {
    echo "<script> alert('글자 범위를 벗어났습니다.')
    location.href='Club.php' </script>";    // Club.php로 이동
    exit(); // 종료
}


// 아이디와 비밀번호를 SELECT
$manager_check = "SELECT * FROM Manager WHERE Manager_ID = '$manager_id' AND PassWord = '$password'";
$ret = mysqli_query($con, $manager_check);  // 쿼리 실행

if (mysqli_num_rows($ret) == 0) {   // 관리자 ID와 비밀번호가 맞는지 확인
    // 틀리다면 알림 띄우고 이전 화면으로 돌아감
    echo "<script> alert('존재하지 않는 관리자거나 비밀번호가 틀립니다.')
    location.href='Match_Info.php' </script>";  // Match_Info.php로 이동
    mysqli_close($con);
    exit;
} else { 
    // 경기 정보 입력
    $sql = "INSERT INTO Match_Info (Match_Day, Match_Place, Match_Team, Match_Result) " .   // 삽입한다.
    "VALUES ('$day', '$place', '$team', '예정')";
        
    InputData($con, $sql, "경기 일정"); // 쿼리 실행 함수 실행

    echo "<script> alert('추가 완료되었습니다.')
    location.href='Match_Info.php' </script>";  // Match_Info.php로 이동
}

mysqli_close($con);
?>