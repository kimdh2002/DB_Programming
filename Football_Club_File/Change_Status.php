<?php

# 선수의 부상 상태를 회복 중 -> 회복으로 바꾸는 코드

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");

// 입력한 값들을 변수에 저장
$injury_case_code = $_POST["Injury_Case_Code"];

$time = date("Y-m-d H:i:s");    // 현재 시간
// 회북 중 -> 회복으로 상태를 변경하고 회복 날짜를 업데이트한다다
$sql = "UPDATE Injury_Status SET Status = '회복', Recovery_Date = '$time' WHERE Injury_Case_Code = '$injury_case_code' AND Status = '회복 중'"; // 수정한다
$ret = mysqli_query($con, $sql);    // 쿼리 실행

if($ret) {
    // 알림창 뜨고 이동
    echo "<script> alert('변경 완료')
    location.href='Management_Injury.php' </script>";   // Management_Injury.php로 이동
}
else {
    echo "데이터 변경 실패!!!"."<br>";
    echo "실패 원인 :".mysqli_error($con);
    exit();
}
mysqli_close($con);
?>