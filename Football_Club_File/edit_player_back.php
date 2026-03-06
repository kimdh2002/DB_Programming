<?php

# 선수의 정보를 수정하는 코드

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
$name = $_POST["Name"];
$birth = $_POST["Birth"];
$country = $_POST["Country"];
$position = $_POST["Position"];
$back_number = $_POST["Back_Number"];
$manager_id = $_POST["Manager_ID"];
$password = $_POST["PassWord"];

// 사용자가 입력하지 않은 것이 있을 경우 예외처리
if (empty($name) || empty($birth) || empty($country) || empty($position) || empty($back_number) || empty($manager_id) || empty($password)) {
    echo "<script> alert('모든 항목을 입력해주세요.')
    location.href='Club.php' </script>";    // Club.php로 이동
    exit(); // 종료
}

// 본인 등번호를 제외한 등번호가 이미 존재하는지 확인 (등번호는 유일한 값이기 때문)
$check = "SELECT * FROM Player WHERE Back_Number = '$back_number' AND Player_Code != '$player_code'";
$ret_check = mysqli_query($con, $check);    // 쿼리 실행

// 등번호 중복 검사
if(mysqli_num_rows($ret_check) > 0) {
    echo "<script> alert('해당 등번호가 이미 존재합니다.')
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
$ret = mysqli_query($con, $manager_check);

if (mysqli_num_rows($ret) == 0) {   // 관리자 ID와 비밀번호가 맞는지 확인
    echo "<script> alert('존재하지 않는 관리자거나 비밀번호가 틀립니다.')
    location.href='Player.html' </script>"; // Player.html로 이동
    mysqli_close($con);
    exit;   // 종료
} else { 
    // 선수 정보 수정
    $sql = "UPDATE Player SET Name='$name', Birth='$birth', Country='$country', Position='$position', Back_Number='$back_number' WHERE Player_Code='$player_code'"; 
        
    InputData($con, $sql, "선수 정보"); // 수정 결과 쿼리에 삽입

    // 관리 로그 입력
    $time = date("Y-m-d H:i:s");    // 현재 시간 저장
    $sql = "INSERT INTO Management (Player_Code, Manager_ID, Management_Date, Management_Type) " .  // 관리 로그를 삽입한다
    "VALUES ((SELECT Player_Code FROM Player WHERE Name = '$name' AND Birth = '$birth'), '$manager_id', '$time', '선수 정보 수정')";
    InputData($con, $sql, "관리 로그"); // 쿼리 실행함수 실행

    echo "<script> alert('변경 완료되었습니다.')
    location.href='Club.php' </script>";    // Club.php로 이동
}

mysqli_close($con);
?>