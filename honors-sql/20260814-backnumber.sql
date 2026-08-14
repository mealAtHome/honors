--
-- 등번호 부여 기능 (2026-08-14)
-- 참고용 기록 파일 - 로컬 dev DB에는 이미 반영되어 있음
--

alter table grp
    add column backnumberlength int default 2 after baccnodefault;

alter table grp_member
    add column grpmbacknum char(5) after userno,
    add column backnumupdatedt datetime after point;
