<?php

# 선수 계약 갱신 버튼을 누르고 이동하는 페이지

// 클릭한 Player_Code를 전달하기 위해 HTML이 아닌 php로 파일 생성
$player_code = $_POST["Player_Code"];
?>

<HTML>
    <HEAD>
    <META http-equiv="content-type">
    <link rel="stylesheet" href="info_style.css">   <!-- css파일 가져오기 -->
    </HEAD>
    <BODY>

    <h1> 계약 정보 변경</h1>

    <FORM METHOD="POST"  ACTION="change_con.php">
        계약 기간 : <INPUT TYPE ="DATE" NAME="Contract_Period"> <br> 
        연봉 : <INPUT TYPE ="text" NAME="Annual_Income"> <br>
        관리자 ID : <INPUT TYPE ="text" NAME="Manager_ID"> <br> 
        비밀번호 : <INPUT TYPE ="password" NAME="PassWord"> <br><br>
        <INPUT TYPE = "hidden" NAME="Player_Code" value="<?= $player_code ?>">  <!-- Player_Code 쿼리 전달 -->
        <INPUT TYPE="submit" style="width:90pt; height:45pt" VALUE="갱신">
    </FORM>

    <h1> 계약 종료 </h1>
    <FORM METHOD = "POST" ACTION="delete_con.php">
        <INPUT TYPE = "hidden" NAME="Player_Code" value="<?= $player_code ?>">  <!-- Player_Code 쿼리 전달 -->
        관리자 ID : <INPUT TYPE ="text" NAME="Manager_ID"> <br> 
        비밀번호 : <INPUT TYPE ="password" NAME="PassWord"> <br> <br>
        <INPUT TYPE="submit"  style="width:90pt; height:45pt"   VALUE="계약 종료">
        <br><br><br><br>
    </FORM>

    <br><br>
    </BODY>
        <FORM action = "Club.php" method="get">
        <button type = "submit" style="width:90pt; height:45pt"> 뒤로가기</button>
    </FORM>
</HTML>