<?php

# 선수에 대한 정보를 받아 DB에 저장하는 코드

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
$name = $_POST["Name"];
$birth = $_POST["Birth"];
$country = $_POST["Country"];
$position = $_POST["Position"];
$back_number = $_POST["Back_Number"];
$contract_period = $_POST["Contract_Period"];
$annual_income = $_POST["Annual_Income"];
$manager_id = $_POST["Manager_ID"];
$password = $_POST["PassWord"];

// 사용자가 입력하지 않은 것이 있을 경우 예외처리
if (empty($name) || empty($birth) || empty($country) || empty($position) || empty($back_number) ||
    empty($contract_period) || empty($annual_income) || empty($manager_id) || empty($password)) {
    echo "<script> alert('최대 길이를 초과했습니다.')
    location.href='Club.php' </script>";
    exit();
}

// SQL에 선언한 최대 크기를 벗어 난 경우
if (strlen($name) > 20 || strlen($country) > 50) {
    echo "<script> alert('글자 범위를 벗어났습니다.')
    location.href='Club.php' </script>";    // Club.php로 이동
    exit(); // 종료
}

// 등번호는 1~99까지의 숫자여야 한다.
if($back_number >= 1 && $back_number <= 99) {
}  else {
    echo "<script> alert('등번호는 1~99까지의 숫자여야 합니다.')
    location.href='Club.php' </script>";    // Club.php로 이동
    exit(); // 종료
}

// 해당 등번호가 이미 존재하는지 확인 (등번호는 유일한 값이기 때문)
$check = "SELECT * FROM Player WHERE Back_Number = '$back_number'";
$ret = mysqli_query($con, $check);  // 쿼리 실행

// 등번호 중복 검사
if(mysqli_num_rows($ret) > 0) {
    echo "<script> alert('해당 등번호가 이미 존재합니다.')
    location.href='Club.php' </script>";    // Club.php로 이동
    exit(); // 종료
}

// 계약 기간이 현재 시간보다 이전인지 검사
if(strtotime($contract_period) < strtotime(date("Y-m-d"))) {
    echo "<script> alert('계약 기간을 다시 확인하세요.')
    location.href='Club.php' </script>";    // Club.php로 이동
    exit(); // 종료
}

// 생년월일이 현재 시간보다 이후인지 검사
if(strtotime($birth) > strtotime(date("Y-m-d"))) {
    echo "<script> alert('생년월일을 다시 확인하세요.')
    location.href='Club.php' </script>";    // Club.php로 이동
    exit(); // 종료
}

// 아이디와 비밀번호를 SELECT
$manager_check = "SELECT * FROM Manager WHERE Manager_ID = '$manager_id' AND PassWord = '$password'";
$ret = mysqli_query($con, $manager_check);  // 쿼리 실행

if (mysqli_num_rows($ret) == 0) {   // 관리자 ID와 비밀번호가 맞는지 확인
    echo "<script> alert('존재하지 않는 관리자거나 비밀번호가 틀립니다.')
    location.href='Player.html' </script>"; // Player.html로 이동
    mysqli_close($con);
    exit;
} else { 
    // 선수 정보 입력
    $sql = "INSERT INTO Player (Name, Birth, Country, Position, Back_Number) " .    // 삽입한다
    "VALUES ('$name', '$birth', '$country', '$position', '$back_number')";
        
    InputData($con, $sql, "선수 정보"); // 쿼리 실행함수 실행

    // 계약 정보 입력
    $sql = "INSERT INTO Contract (Player_Code, Contract_Period, Annual_Income) " .  // 삽입한다
    "VALUES ((SELECT Player_Code FROM Player WHERE Name = '$name' AND Birth = '$birth'), '$contract_period', '$annual_income')";
    InputData($con, $sql, "계약 정보"); // 쿼리 실행함수 실행

    // 관리 로그 입력
    $time = date("Y-m-d H:i:s");    // 현재 시간 저장
    $sql = "INSERT INTO Management (Player_Code, Manager_ID, Management_Date, Management_Type) " .  // 삽입한다
    "VALUES ((SELECT Player_Code FROM Player WHERE Name = '$name' AND Birth = '$birth'), '$manager_id', '$time', '선수 등록')";
    InputData($con, $sql, "관리 로그"); // 쿼리 실행함수 실행

    echo "<script> alert('등록 완료되었습니다.')
    location.href='Club.php' </script>";    // Club.php로 이동
}

mysqli_close($con);
?>