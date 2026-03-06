<?php

# 프로젝트의 기본 데이터를 삽입하는 코드

 $con = mysqli_connect("localhost", "cookUser", "1234", "Football_Club") or die("MySQL 접속 실패!!");

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

$manager = "
INSERT INTO Manager (Manager_ID, PassWord) VALUES
('admin', 'admin123')";
InputData($con, $manager, "관리자"); // SQL에 관리자 데이터 삽입

 // 선수 개체 데이터 삽입
 $player = "
 INSERT INTO Player (Name, Birth, Country, Position, Back_Number) VALUES
 ('이창근', '1993-08-30', '대한민국', 'GK', 1),
 ('박규현', '2001-04-14', '대한민국', 'DF', 2),
 ('하창래', '1994-10-16', '대한민국', 'DF', 3),
 ('김현우', '1999-03-07', '대한민국', 'DF', 4),
 ('임종은', '1990-06-18', '대한민국', 'DF', 5),
 ('강윤성', '1997-07-01', '대한민국', 'DF', 6),
 ('마사', '1995-05-04', '일본', 'MF', 7),
 ('구텍', '1995-04-02', '라트비아', 'FW', 9),
 ('주민규', '1990-04-13', '대한민국', 'FW', 10),
 ('김인균', '1998-07-23', '대한민국', 'MF', 11),
 ('김승대', '1991-04-01', '대한민국', 'FW', 12),
 ('정우빈', '2001-05-08', '대한민국', 'MF', 13),
 ('김준범', '1998-01-14', '대한민국', 'MF', 14),
 ('이순민', '1994-05-22', '대한민국', 'MF', 44)
 ";
 InputData($con, $player, "선수");    // SQL에 선수 데이터 삽입
 
 // Player 테이블에서 이름과 생년월일에 해당하는 선수 코드를 가져와 계약 기간을 저장한다
 $contract = "
INSERT INTO Contract (Player_Code, Contract_Period, Annual_Income) VALUES
    ((SELECT Player_Code FROM Player WHERE Name = '이창근' AND Birth = '1993-08-30'), '2026-01-01', 80000000), 
    ((SELECT Player_Code FROM Player WHERE Name = '박규현' AND Birth = '2001-04-14'), '2025-12-15', 100000000), 
    ((SELECT Player_Code FROM Player WHERE Name = '하창래' AND Birth = '1994-10-16'), '2025-12-31', 100000000), 
    ((SELECT Player_Code FROM Player WHERE Name = '김현우' AND Birth = '1999-03-07'), '2024-06-30', 70000000), 
    ((SELECT Player_Code FROM Player WHERE Name = '임종은' AND Birth = '1990-06-18'), '2023-12-31', 90000000), 
    ((SELECT Player_Code FROM Player WHERE Name = '강윤성' AND Birth = '1997-07-01'), '2026-12-31', 80000000), 
    ((SELECT Player_Code FROM Player WHERE Name = '마사' AND Birth = '1995-05-04'), '2026-06-30', 150000000), 
    ((SELECT Player_Code FROM Player WHERE Name = '구텍' AND Birth = '1995-04-02'), '2027-01-01', 200000000), 
    ((SELECT Player_Code FROM Player WHERE Name = '주민규' AND Birth = '1990-04-13'), '2025-12-31', 350000000), 
    ((SELECT Player_Code FROM Player WHERE Name = '김인균' AND Birth = '1998-07-23'), '2024-12-31', 80000000), 
    ((SELECT Player_Code FROM Player WHERE Name = '김승대' AND Birth = '1991-04-01'), '2025-06-30', 70000000), 
    ((SELECT Player_Code FROM Player WHERE Name = '정우빈' AND Birth = '2001-05-08'), '2027-12-31', 50000000), 
    ((SELECT Player_Code FROM Player WHERE Name = '김준범' AND Birth = '1998-01-14'), '2024-06-30', 100000000), 
    ((SELECT Player_Code FROM Player WHERE Name = '이순민' AND Birth = '1994-05-22'), '2025-05-31', 150000000)
";
InputData($con, $contract, "계약"); // SQL에 계약 데이터 삽입

// 선수들이 가장 자주 당하는 부상의 종류를 저장
$injury = "
INSERT INTO Injury (Injury_Name, Injury_Part) VALUES 
('뇌진탕', '머리'), ('안면 골절', '얼굴'), ('어깨 탈구', '어깨'), ('팔꿈치 염좌', '팔꿈치'), ('손목 골절', '손목'),
('햄스트링 부상', '허벅지'), ('대퇴사두근 부상', '허벅지'), ('사타구니 염좌', '사타구니'),
('전방 십자인대 파열', '무릎'), ('후방 십자인대 파열', '무릎'), ('반월판 손상', '무릎'),
('발목 염좌', '발목'), ('발목 골절', '발목'), ('족저근막염', '발바닥'), ('발가락 골절', '발가락')";
InputData($con, $injury, "부상"); // SQL에 부상 데이터 삽입

// 경기 정보 기본 데이터 삽입 (처음에는 예정된 경기만 삽입하고 그 후에 데이터를 입력하는 방식으로)
$match_info = "
INSERT INTO Match_Info (Match_Day, Match_Place, Match_Team, Match_Result) VALUES
('2025-05-10','대전월드컵경기장', 'FC 서울', '0-0'), ('2025-05-14','대전월드컵경기장', '전북 현대 모터스', '2-3')
";
InputData($con, $match_info, "경기 정보"); // SQL에 경기 데이터 삽입

// 부상 당한 선수의 데이터를 저장
$injury_check = "
INSERT INTO Injury_Check (Injury_Code, Player_Code, Injury_Date, Estimated_Period) VALUES
(3, 11, '2025-05-01', '6주'), (6,12, '2025-05-10', '2주')
";
InputData($con, $injury_check, "부상 확인"); // SQL에 부상 확인 데이터 삽입

// 부상 당한 선수의 회복 상태 데이터를 저장
$injury_status = "
INSERT INTO Injury_Status (Injury_Case_Code, Recovery_Date, Status) VALUES
(1, NULL, '회복 중'), (2, NULL, '회복 중')
";
InputData($con, $injury_status, "부상 상태"); // SQL에 부상 확인 데이터 삽입

// 경기결과 입력
$result_info = "
INSERT INTO Result_Info (Player_Code, Match_Code, Goal, Assist, Cap, Grade) VALUES
((SELECT Player_Code FROM Player WHERE Name = '이창근' AND Birth = '1993-08-30'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-10' AND Match_Team = 'FC 서울'), 0, 0, TRUE, 6.1),
((SELECT Player_Code FROM Player WHERE Name = '박규현' AND Birth = '2001-04-14'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-10' AND Match_Team = 'FC 서울'), 0, 0, TRUE, 6.2),
((SELECT Player_Code FROM Player WHERE Name = '하창래' AND Birth = '1994-10-16'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-10' AND Match_Team = 'FC 서울'), 0, 0, TRUE, 6.3),
((SELECT Player_Code FROM Player WHERE Name = '김현우' AND Birth = '1999-03-07'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-10' AND Match_Team = 'FC 서울'), 0, 0, TRUE, 6.4),
((SELECT Player_Code FROM Player WHERE Name = '임종은' AND Birth = '1990-06-18'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-10' AND Match_Team = 'FC 서울'), 0, 0, TRUE, 6.5),
((SELECT Player_Code FROM Player WHERE Name = '마사' AND Birth = '1995-05-04'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-10' AND Match_Team = 'FC 서울'), 0, 0, TRUE, 6.6),
((SELECT Player_Code FROM Player WHERE Name = '김인균' AND Birth = '1998-07-23'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-10' AND Match_Team = 'FC 서울'), 0, 0, TRUE, 6.7),
((SELECT Player_Code FROM Player WHERE Name = '이순민' AND Birth = '1994-05-22'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-10' AND Match_Team = 'FC 서울'), 0, 0, TRUE, 6.3),
((SELECT Player_Code FROM Player WHERE Name = '김준범' AND Birth = '1998-01-14'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-10' AND Match_Team = 'FC 서울'), 0, 0, TRUE, 6.4),
((SELECT Player_Code FROM Player WHERE Name = '주민규' AND Birth = '1990-04-13'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-10' AND Match_Team = 'FC 서울'), 0, 0, TRUE, 6.5),
((SELECT Player_Code FROM Player WHERE Name = '구텍' AND Birth = '1995-04-02'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-10' AND Match_Team = 'FC 서울'), 0, 0, TRUE, 6.6)
";

InputData($con, $result_info, "FC 서울전 결과"); // SQL에 부상 확인 데이터 삽입

$result_info = "
INSERT INTO Result_Info (Player_Code, Match_Code, Goal, Assist, Cap, Grade) VALUES
((SELECT Player_Code FROM Player WHERE Name = '이창근' AND Birth = '1993-08-30'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-14' AND Match_Team = '전북 현대 모터스'), 0, 0, TRUE, 6.1),
((SELECT Player_Code FROM Player WHERE Name = '박규현' AND Birth = '2001-04-14'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-14' AND Match_Team = '전북 현대 모터스'), 0, 0, TRUE, 6.1),
((SELECT Player_Code FROM Player WHERE Name = '하창래' AND Birth = '1994-10-16'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-14' AND Match_Team = '전북 현대 모터스'), 0, 0, TRUE, 6.1),
((SELECT Player_Code FROM Player WHERE Name = '김현우' AND Birth = '1999-03-07'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-14' AND Match_Team = '전북 현대 모터스'), 0, 0, TRUE, 6.1),
((SELECT Player_Code FROM Player WHERE Name = '임종은' AND Birth = '1990-06-18'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-14' AND Match_Team = '전북 현대 모터스'), 0, 1, TRUE, 6.1),
((SELECT Player_Code FROM Player WHERE Name = '마사' AND Birth = '1995-05-04'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-14' AND Match_Team = '전북 현대 모터스'), 0, 0, TRUE, 6.1),
((SELECT Player_Code FROM Player WHERE Name = '김인균' AND Birth = '1998-07-23'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-14' AND Match_Team = '전북 현대 모터스'), 1, 0, TRUE, 6.6),
((SELECT Player_Code FROM Player WHERE Name = '이순민' AND Birth = '1994-05-22'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-14' AND Match_Team = '전북 현대 모터스'), 0, 0, TRUE, 6.1),
((SELECT Player_Code FROM Player WHERE Name = '김준범' AND Birth = '1998-01-14'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-14' AND Match_Team = '전북 현대 모터스'), 0, 0, TRUE, 6.1),
((SELECT Player_Code FROM Player WHERE Name = '주민규' AND Birth = '1990-04-13'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-14' AND Match_Team = '전북 현대 모터스'), 1, 1, TRUE, 7.1),
((SELECT Player_Code FROM Player WHERE Name = '구텍' AND Birth = '1995-04-02'),
(SELECT Match_Code FROM Match_Info WHERE Match_Day = '2025-05-14' AND Match_Team = '전북 현대 모터스'), 0, 0, TRUE, 6.1)
";
InputData($con, $result_info, "전북 현대전 결과"); // SQL에 부상 확인 데이터 삽입

mysqli_close($con);
?> 