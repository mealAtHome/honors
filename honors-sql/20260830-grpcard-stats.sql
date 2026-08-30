--
-- 모임 카드 지표(멤버수/활동주기/마지막활동/평균비용) (2026-08-30)
-- 참고용 기록 파일 - grpmcnt는 사용자가 이미 적용, 나머지 4개는 아래 수정된 문으로 적용함
--
-- !! 원본 지시문 오타 수정 !!
-- 원본: "alter table grp add column grpclstermunit ... after grpcnt"
-- 실제 컬럼명은 grpcnt가 아니라 grpmcnt라서, 이 순서대로 실행하면 첫 문에서
-- "Unknown column 'grpcnt'" 에러가 나고, 그 뒤 3개 문도 연쇄적으로 실패함.
-- (로컬 dev DB 확인 결과 grpmcnt만 반영되어 있고 나머지 4개는 미반영 상태였음 — 아래로 반영함)
--
alter table grp add column grpclstermunit enum('y','m','w','d') after grpmcnt;
alter table grp add column grpclstermvalue int after grpclstermunit;
alter table grp add column grplastclsregisted datetime after grpclstermvalue;
alter table grp add column grpclsapplybillavg int after grplastclsregisted;
