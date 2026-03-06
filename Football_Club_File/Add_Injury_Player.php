<?php

#부상 선수를 입력받은 값을 받아 DB에 저장하는 코드

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
$player_code = $_POST["Player_Code"];
$injury_code = $_POST["Injury_Code"];
$estimated_period = $_POST["Estimated_Period"];
$manager_id = $_POST["Manager_ID"];
$password = $_POST["PassWord"];

// 사용자가 입력하지 않은 것이 있을 경우 예외처리
if (empty($player_code) || empty($injury_code) || empty($estimated_period) || empty($manager_id) || empty($password)){
    echo "<script> alert('모든 항목을 입력해주세요.')
    location.href='Club.php' </script>";    // Club.php로 이동
    exit();
}

// 아이디와 비밀번호를 SELECT
$manager_check = "SELECT * FROM Manager WHERE Manager_ID = '$manager_id' AND PassWord = '$password'";
$ret = mysqli_query($con, $manager_check);

if (mysqli_num_rows($ret) == 0) {   // 관리자 ID와 비밀번호가 맞는지 확인
    echo "<script> alert('존재하지 않는 관리자거나 비밀번호가 틀립니다.')
    location.href='Management_Injury.php' </script>";   // Management_Injury.php로 이동
    mysqli_close($con);
    exit;
} else { 
    // Injury_Check에 저장
    $time = date("Y-m-d H:i:s");    // 현재 시간 저장
    $check = "INSERT INTO Injury_Check (Injury_Code, Player_Code, Injury_Date, Estimated_Period) " .    // 삽입한다
    "VALUES ('$injury_code', '$player_code', '$time', '$estimated_period')";
    InputData($con, $check, "Injury_Check");    // 쿼리 실행 함수 실행

    // Injury_Status에 저장
    $status = "INSERT INTO Injury_Status (Injury_Case_Code, Recovery_Date, Status) " .  // 삽입 한다
    "VALUES ((SELECT Injury_Case_Code FROM Injury_Check WHERE Player_Code = '$player_code' AND Injury_Code = '$injury_code'), NULL, '회복 중')";
    InputData($con, $status, "Injury_Check");   // 쿼리 실행 함수 실행

    // 관리 로그 작성
    $log = "INSERT INTO Management (Player_Code, Manager_ID, Management_Date, Management_Type) " .  // 삽입한다
    "VALUES ('$player_code', '$manager_id', '$time', '부상자 등록')";   // 쿼리 실행 함수 실행
    InputData($con, $log, "관리 로그");

    // 등록 완료했다는 알림 띄우기
    echo "<script> alert('등록 완료되었습니다.')
    location.href='Management_Injury.php' </script>";   // Management_Injury.php로 이동
}

mysqli_close($con);
?>