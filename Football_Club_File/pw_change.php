<?php

# 아이디와 비밀번호, 비밀번호 재입력 입력받은 값을 받고 확인 후 비밀번호를 변경하는 코드

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");

// 입력한 값들을 변수에 저장
$manager_id = $_POST["Manager_ID"];
$password = $_POST["PassWord"];
$password_re = $_POST["PassWord_Re"];

// 기존의 아이디 정보 저장
$sql = "SELECT * FROM Manager WHERE Manager_ID = '$manager_id'";
$result = mysqli_query($con, $sql); // 쿼리 실행

// 사용자가 입력하지 않은 것이 있을 경우 예외처리
if (empty($manager_id) || empty($password) || empty($password_re)) {
    echo "<script> alert('모든 항목을 입력해주세요.')
    location.href='PassWord.html' </script>";   // Password.html로 이동
    exit();
}

// 입력한 아이디가 존재하지 확인
if (mysqli_num_rows($result) == 0) {
    echo "<script> alert('ID가 존재하지 않습니다.')
    location.href='Password.html' </script>";   // Password.html로 이동
    mysqli_close($con);
    exit(); // 종료
}

// 비밀번호와 비밀번호 재입력 값이 같다면
if($password == $password_re) {
    //  기존 비밀번호를 수정하여 비밀번호를 바꾼다
    $sql = "UPDATE Manager SET PassWord = '$password' WHERE Manager_ID = '$manager_id'";
    $ret = mysqli_query($con, $sql);    // 쿼리 실행

    echo "<script>
    alert('변경 완료되었습니다.');
    alert('로그인 화면으로 돌아갑니다.');
    location.href='Login.html';
    </script>";
} else {
    echo "<script> alert('비밀번호를 다시 확인해주세요.')
    location.href='PassWord.html' </script>";   // Password.html
}

mysqli_close($con);
?>