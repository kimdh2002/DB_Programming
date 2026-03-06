<?php

# 선수의 정보 수정 입력하는 코드

$con=mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패 !!");

// 클릭한 Player_Code를 전달하기 위해 HTML이 아닌 php로 파일 생성
$player_code = $_POST["Player_Code"];
$sql = "SELECT * FROM Player WHERE Player_Code = $player_code";
$ret = mysqli_query($con, $sql);

if($ret) {
    $row = mysqli_fetch_array($ret);
} else { echo "오류 발생"; exit(); }

echo "<h1>".$row['Name']." 선수 정보 수정</h1>"
?>

<HTML>
    <HEAD>
    <META http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="info_style.css">
    </HEAD>
    <BODY>

    <FORM METHOD="POST"  ACTION="edit_player_back.php">
        <!-- 기존의 값들을 입력 란에 미리 입력된 상태로-->
        선수 이름 : <INPUT TYPE ="text" NAME="Name" value="<?php echo $row['Name']; ?>"> <br> 
        생년월일 : <INPUT TYPE ="Date" NAME="Birth" value="<?php echo $row['Birth']; ?>"> <br>
        국적 : <INPUT TYPE ="text" NAME="Country" value="<?php echo $row['Country']; ?>"> <br>
        포지션 :  <select name = Position>
            <option value="GK" selected>GK
            <option value="DF" selected>DF
            <option value="MF" selected>MF 
            <option value="FW" selected>FW
            </option> </select> <br>
        등번호 : <INPUT TYPE ="text" NAME="Back_Number" value="<?php echo $row['Back_Number']; ?>"> <br><br><br>
        관리자 ID : <INPUT TYPE ="text" NAME="Manager_ID"> <br> 
        비밀번호 : <INPUT TYPE ="password" NAME="PassWord"> <br><br>
        <INPUT TYPE = "hidden" NAME="Player_Code" value="<?= $player_code ?>">  <!-- Player_Code 쿼리 전달 -->
        <INPUT TYPE="submit" style="width:90pt; height:45pt" VALUE="갱신"> <br>
    </FORM>

    </BODY>
        <FORM action = "Club.php" method="get">
        <input type = 'hidden' name = 'Player_Code' value = "<?= $player_code ?>">
        <button type = "submit" style="width:100pt; height:45pt"> 메인 화면으로</button>
    </FORM>
</HTML>