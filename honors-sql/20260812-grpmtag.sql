--
-- 그룹멤버 태그기능 (2026-08-12)
--

--
-- Table structure for table `grpmtaga` (태그정보)
--

DROP TABLE IF EXISTS `grpmtaga`;
CREATE TABLE `grpmtaga` (
  `grpno` char(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tagidx` int NOT NULL,
  `tagname` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tagcolorfont` char(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tagcolorback` char(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tagregcnt` int NOT NULL DEFAULT 0,
  `modidt` datetime DEFAULT NULL,
  `regdt` datetime DEFAULT NULL,
  PRIMARY KEY (`grpno`,`tagidx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `grpmtagb` (태그별 등록 멤버)
--

DROP TABLE IF EXISTS `grpmtagb`;
CREATE TABLE `grpmtagb` (
  `grpno` char(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tagidx` int NOT NULL,
  `userno` char(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `regdt` datetime DEFAULT NULL,
  PRIMARY KEY (`grpno`,`tagidx`,`userno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
