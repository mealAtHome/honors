--
-- 유저 주소관리 / 로그인 위치 동기화 (2026-08-30)
-- user_addr, user.userloginedpoint 는 사용자가 이미 적용함 (PK는 (userno, useraddridx)로 재수정됨).
-- 아래는 이번에 새로 추가한 필드 - 그룹멤버별로 노출할 주소(동읍면 표시용, 거리계산에는 미사용)를 참조.
--
alter table grp_member add column useraddridx int null after userno;
