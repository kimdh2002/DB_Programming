<?php

# 입력받은 ID와 비밀번호가 맞는지 확인하고 메인 페이지로 이동하는 코드

$con = mysqli_connect("localhost", "cookUser", "1234", "FootBall_Club") or die("MySQL 접속 실패 !!");

// 입력 받은 아이디와 비밀번호를 변수에 저장
$manager_id = $_POST['Manager_ID'];
$password = $_POST['PassWord'];

// 아이디와 비밀번호를 SELECT
$manager_check = "SELECT * FROM Manager WHERE Manager_ID = '$manager_id' AND PassWord = '$password'";
$ret = mysqli_query($con, $manager_check);  // 쿼리 실행

// 사용자가 입력하지 않은 것이 있을 경우 예외처리
if (empty($manager_id) || empty($password)) {
    echo "<script> alert('모든 항목을 입력해주세요.')
    location.href='Login.html' </script>";  // Login.html로 이동
    exit(); // 종료
}


if (mysqli_num_rows($ret) == 0) {   // 관리자 ID와 비밀번호가 맞는지 확인
    echo "<script> alert('존재하지 않는 관리자거나 비밀번호가 틀립니다.')
    location.href='Login.html' </script>";  // Login.html로 이동
    mysqli_close($con);
    exit;
} else { 
    echo "<script> alert('환영합니다 {$manager_id}님')
    location.href='Main_System.html' </script>";    // Main_System.html로 이동
}

mysqli_close($con);
?>
