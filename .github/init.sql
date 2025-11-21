/*
 Navicat Premium Dump SQL

 Source Server         : 127.0.0.1_3306
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : 127.0.0.1:3306
 Source Schema         : secret_space

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 21/11/2025 20:28:54
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for content
-- ----------------------------
DROP TABLE IF EXISTS `content`;
CREATE TABLE `content` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL COMMENT '用户 ID',
  `secret_id` bigint unsigned NOT NULL COMMENT '密码 ID',
  `title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `content` varchar(10240) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '内容',
  `type` int unsigned NOT NULL DEFAULT '0' COMMENT '类型 0 文本 1 音频 2 视频 3 图片',
  `created_at` datetime NOT NULL DEFAULT '2025-01-01 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '2025-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `INDEX_SECRET_ID` (`secret_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='内容表';

-- ----------------------------
-- Records of content
-- ----------------------------
BEGIN;
INSERT INTO `content` (`id`, `user_id`, `secret_id`, `title`, `content`, `type`, `created_at`, `updated_at`) VALUES (1, 1, 1, 'Hello', 'eyJpdiI6Ik5YeVZGdFljNUpuM1JNZVY2Vnc3VGc9PSIsInZhbHVlIjoibXRrMS91Yk1HeVpvSDF0aWxjaUQ5dz09IiwibWFjIjoiNTM5OGJjMDBkZGI1ODdiNTk1ZjU1N2YzYmQ2OWVhYTBiMzFjYjExZGJkOGExZGU5ZjNmNmNjMmNhOGQxNmJjZiJ9', 0, '2025-11-16 16:08:21', '2025-11-20 11:30:08');
COMMIT;

-- ----------------------------
-- Table structure for secret
-- ----------------------------
DROP TABLE IF EXISTS `secret`;
CREATE TABLE `secret` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL COMMENT '用户 ID',
  `secret` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '密码',
  `created_at` datetime DEFAULT '2025-01-01 00:00:00',
  `updated_at` datetime DEFAULT '2025-01-01 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_USER_SECRET` (`user_id`,`secret`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='密码表';

-- ----------------------------
-- Records of secret
-- ----------------------------
BEGIN;
INSERT INTO `secret` (`id`, `user_id`, `secret`, `created_at`, `updated_at`) VALUES (1, 1, 'fae0b27c451c728867a567e8c1bb4e53', '2025-11-14 11:07:26', '2025-11-14 11:07:26');
INSERT INTO `secret` (`id`, `user_id`, `secret`, `created_at`, `updated_at`) VALUES (42, 1, '81dc9bdb52d04dc20036dbd8313ed055', '2025-11-20 11:30:09', '2025-11-20 11:30:09');
COMMIT;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '小程序 OpenID',
  `created_at` datetime NOT NULL DEFAULT '2025-01-01 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '2025-01-01 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_OPENID` (`openid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户表';

-- ----------------------------
-- Records of user
-- ----------------------------
BEGIN;
INSERT INTO `user` (`id`, `openid`, `created_at`, `updated_at`) VALUES (1, 'oDA2A1x56y3kqdwVLqCP_WqcI0x0', '2025-11-09 22:35:37', '2025-11-09 22:35:37');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
