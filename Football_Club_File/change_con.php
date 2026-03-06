<?php

# 계약 기간과 연봉을 입력받아 계약 기간을 갱신하는 코드

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");

// 입력한 값들을 변수에 저장
$player_code = $_POST["Player_Code"];
$contract_period = $_POST["Contract_Period"];
$annual_income = $_POST["Annual_Income"];
$manager_id = $_POST["Manager_ID"];
$password = $_POST["PassWord"];

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

// 선수 정보 가져오기
$sql = "SELECT * FROM Contract WHERE Player_Code = '$player_code'";
$result = mysqli_query($con, $sql); // 쿼리 실행

// 혹여나 선수 코드가 NULL로 인식될 경우 예외처리
if (mysqli_num_rows($result) == 0) {
    echo "<script>
    alert('해당 선수는 존재하지 않습니다.');
    alert('메인 화면으로 돌아갑니다.');
    location.href='Club.php';
    </script>";
    mysqli_close($con);
    exit(); // 종료
}

// 계약 기간이 현재 시간보다 이전인지 검사
if(strtotime($contract_period) < strtotime(date("Y-m-d"))) {
    echo "<script> alert('계약 기간을 다시 확인하세요.')
    location.href='Club.php' </script>";    // Club.php로 이동
    exit(); // 종료
}

// 아이디와 비밀번호를 SELECT
$manager_check = "SELECT * FROM Manager WHERE Manager_ID = '$manager_id' AND PassWord = '$password'";
$ret = mysqli_query($con, $manager_check);  // 쿼리 실행

if (mysqli_num_rows($ret) == 0) {   // 관리자 ID와 비밀번호가 맞는지 확인
    echo "<script>
    alert('존재하지 않는 관리자거나 비밀번호가 틀립니다.');
    alert('메인 화면으로 돌아갑니다.');
    location.href='Club.php';
    </script>";
    mysqli_close($con);
    exit();
} else { 
    // Contract테이블의 Contract_Period와 Annual_Income 내용을 player_code에 해당하는 선수의 데이터를 UPDATE
    $sql = "UPDATE Contract SET Contract_Period = '$contract_period',Annual_Income = '$annual_income' WHERE Player_Code = '$player_code'";
    $ret = mysqli_query($con, $sql);    // 쿼리 실행

    // 관리 로그 입력
    $time = date("Y-m-d H:i:s");    // 현재 시간
    $sql = "INSERT INTO Management (Player_Code, Manager_ID, Management_Date, Management_Type) " .  // 삽입한다
    "VALUES ($player_code, '$manager_id', '$time', '계약 갱신')";
    InputData($con, $sql, "관리 로그"); // 쿼리 실행함수 실행

    // 입력 성공했다면 알림 뜨고 메인으로
    if (!$ret) {
    echo "<script> alert('계약 수정 실패.')
    location.href='re_contract.php' </script>"; // re_contract.php로 이동
    } else {
    echo "<script> alert('변경 완료되었습니다.')
    location.href='Club.php' </script>";    // Club.php로 이동
}
    
}

mysqli_close($con);
?>