CREATE DATABASE honors CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sadmin'@'%' IDENTIFIED BY 'svwy@pf!';
GRANT ALL ON honors.* TO 'sadmin'@'%';
flush privileges;

/* shard (all) */
/* CREATE DATABASE honors_all CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; */
/* GRANT ALL ON honors_all.* TO 'jun'@'%'; */
