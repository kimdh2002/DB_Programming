# DB 생성
CREATE DATABASE IF NOT EXISTS Football_Club;
USE Football_Club;

#관리자 개체 테이블
CREATE TABLE IF NOT EXISTS Manager (
Manager_ID VARCHAR(100) UNIQUE PRIMARY KEY,	# 관리자 ID
PassWord VARCHAR(100) NOT NULL	# 관리자 비밀번호
);

#선수 개체 테이블
CREATE TABLE IF NOT EXISTS Player (
Player_Code INT AUTO_INCREMENT PRIMARY KEY, #선수 코드
Name VARCHAR(20), #이름
Birth DATE, #생년월일
Country VARCHAR(50), #국적
Position VARCHAR(10), # 포지션
Back_Number INT NOT NULL UNIQUE	#등번호
);

#계약 테이블
CREATE TABLE IF NOT EXISTS Contract (
Player_Code INT, #선수 코드
Contract_Period DATE, # 계약 기간
Annual_Income INT, # 연봉

PRIMARY KEY(Player_Code),
FOREIGN KEY(Player_Code) REFERENCES Player(Player_Code)
);

#부상 테이블
CREATE TABLE IF NOT EXISTS Injury (
Injury_Code INT AUTO_INCREMENT PRIMARY KEY,
Injury_Name VARCHAR(30),
Injury_Part VARCHAR(30)
);

#경기 개체 테이블
CREATE TABLE IF NOT EXISTS Match_Info(
Match_Code INT AUTO_INCREMENT PRIMARY KEY,	#경기 코드
Match_Day DATE,	#경기 날짜
Match_Place VARCHAR(50),	#경기 장소
Match_Team VARCHAR(50),	#상대 팀
Match_Result VARCHAR(10) DEFAULT '예정' #경기 결과
);

#관리 관계 테이블
CREATE TABLE IF NOT EXISTS Management (
Management_Record_Code INT AUTO_INCREMENT PRIMARY KEY,	#관리 이력 코드
Manager_ID VARCHAR(100),
Player_Code INT,
Management_Date DATE,	# 관리 날짜
Management_Type VARCHAR(50),	#유형

# Player_Code 삭제 시 관리 테이블에서는 NULL로 저장하기 위해 ON DELETE SET NULL을 사용한다
FOREIGN KEY(Player_Code) REFERENCES Player(Player_Code) ON DELETE SET NULL,	#선수 테이블 -> 선수 코드
FOREIGN KEY(Manager_ID) REFERENCES Manager(Manager_ID)	#관리자 테이블 -> 관리자 ID
);

#부상 확인 테이블
CREATE TABLE IF NOT EXISTS Injury_Check (
Injury_Case_Code INT AUTO_INCREMENT PRIMARY KEY,	# 부상 사건 코드
Injury_Code INT,	#부상 코드
Player_Code INT NULL,	# Null 허용	
Injury_Date DATE,	#부상 발생 날짜
Estimated_Period VARCHAR(30),	#예상 기간

FOREIGN KEY(Player_Code) REFERENCES Player(Player_Code),	#선수 테이블 -> 선수 코드
FOREIGN KEY(Injury_Code) REFERENCES Injury(Injury_Code)	#부상 테이블 -> 부상 코드
);

#부상 상태 확인 관계 테이블
CREATE TABLE IF NOT EXISTS Injury_Status (
Injury_Case_Code INT,
Recovery_Date DATE,	# 회복 날짜
Status VARCHAR(20),	# 상태

PRIMARY KEY(Injury_Case_Code),	# 기본 키
FOREIGN KEY(Injury_Case_Code) REFERENCES Injury_Check(Injury_Case_Code)	#Injury_Check에서 (부상 사건 코드를 가져온다)
);

#결과 확인 관계 테이블
CREATE TABLE IF NOT EXISTS Result_Info (
Player_Code INT,
Match_Code INT,
Goal INT DEFAULT 0,	# 경기에서의 골
Assist INT DEFAULT 0, # 경기에서의 어시스트
Cap BOOL DEFAULT FALSE,	# 출장
Grade DOUBLE DEFAULT 0.0,	#평점

PRIMARY KEY(Player_Code, Match_Code),	# 가져온 값을 기본키로
#Player_Code는 Match_Code와 기본키라 NULL 허용이 안됨
FOREIGN KEY(Player_Code) REFERENCES Player(Player_Code),	#선수 테이블 -> 선수 코드
FOREIGN KEY(Match_Code) REFERENCES Match_Info(Match_Code)		#경기 테이블 -> 경기 코드
);