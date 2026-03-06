<?php

# 선수와의 계약 해지로 DB에서 선수의 정보를 삭제하는 코드

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");

// 값을 전달 받는다
$player_code = $_POST["Player_Code"];
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

// 사용자가 입력하지 않은 것이 있을 경우 예외처리
if (empty($manager_id) || empty($password) ) {
    echo "<script> alert('모든 항목을 입력해주세요.')
    location.href='re_contract.php' </script>";
    exit();
}

// 아이디와 비밀번호를 SELECT
$manager_check = "SELECT * FROM Manager WHERE Manager_ID = '$manager_id' AND PassWord = '$password'";
$ret = mysqli_query($con, $manager_check);  // 쿼리 실행

if (mysqli_num_rows($ret) == 0) {   // 관리자 계정이 존재하는지 확인
    echo "<script>
    alert('존재하지 않는 관리자거나 비밀번호가 틀립니다.');
    alert('메인 화면으로 돌아갑니다.');
    location.href='Club.php';
    </script>";
    mysqli_close($con);
    exit();
} else { 
    
    // 관리 로그 입력
    $time = date("Y-m-d H:i:s");
    $sql = "INSERT INTO Management (Player_Code, Manager_ID, Management_Date, Management_Type) " .
    "VALUES ($player_code, '$manager_id', '$time', '계약 해지')";
    InputData($con, $sql, "계약 해지");

    // 외래키로 연결된 선수의 데이터를 먼저 삭제하고 -> 선수 정보를 삭제한다

    // 선수 스탯 삭제
    $stat ="DELETE FROM Result_Info WHERE Player_Code='".$player_code."'";
    $ret1 = mysqli_query($con, $stat);

    // 부상 중이거나 부상이력이 있는 선수의 부상 상태 테이블을 삭제한다, 이때 부상 상태 테이블은 부상 확인 테이블을 외래키로 사용하기 때문에 맞는 조건 사용
    $injury_status ="DELETE FROM Injury_Status WHERE Injury_Case_Code IN (SELECT Injury_Case_Code FROM Injury_Check WHERE Player_Code='$player_code')";
    $ret2 = mysqli_query($con, $injury_status);

    // 선수 부상 이력 삭제 계약 해지됐다면 더 이상 그 선수의 부상 내역을 저장할 이유가 없음
    $injury_check ="DELETE FROM Injury_Check WHERE Player_Code='".$player_code."'";
    $ret3 = mysqli_query($con, $injury_check);

    // 선수와의 계약 삭제
    $contract ="DELETE FROM Contract WHERE Player_Code='".$player_code."'";
    $ret4 = mysqli_query($con, $contract);

    // 마지막으로 선수 정보 삭제
    $player ="DELETE FROM Player WHERE Player_Code='".$player_code."'";
    $ret5 = mysqli_query($con, $player);

    // 전부 삭제 잘 되었다면 알림띄우고 이전으로
    if($ret1 && $ret2 && $ret3 && $ret4 && $ret5) {
        echo "<script> alert('정상적으로 계약 해지되었습니다.')
        location.href='Club.php' </script>";    // Club.php로 이동
    } else {
        echo "<script> alert('계약 해지하는 중에 문제 발생')
        location.href='Club.php' </script>";    // Club.php로 이동
    }    
}

mysqli_close($con);  
?>