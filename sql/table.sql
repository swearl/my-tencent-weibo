-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-10-12 17:07:23
-- 服务器版本： 8.0.21-0ubuntu0.20.04.4
-- PHP 版本： 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- 数据库： `tencent_weibo`
--

-- --------------------------------------------------------

--
-- 表的结构 `authors`
--

CREATE TABLE `authors` (
  `id` int NOT NULL COMMENT 'ID',
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT '昵称'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='作者表';

-- --------------------------------------------------------

--
-- 表的结构 `images`
--

CREATE TABLE `images` (
  `id` int NOT NULL COMMENT 'ID',
  `hash` varchar(40) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '图片ID',
  `url` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '地址',
  `type` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '类型',
  `size` int NOT NULL DEFAULT '0' COMMENT '大小'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='图片表';

-- --------------------------------------------------------

--
-- 表的结构 `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL COMMENT 'ID',
  `parent_id` int NOT NULL DEFAULT '0' COMMENT '父ID',
  `author` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '作者',
  `content` varchar(250) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '内容',
  `date` datetime DEFAULT NULL COMMENT '日期',
  `images` json DEFAULT NULL COMMENT '图片'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='文章表';

--
-- 转储表的索引
--

--
-- 表的索引 `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- 表的索引 `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hash` (`hash`),
  ADD KEY `type` (`type`);

--
-- 表的索引 `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `date` (`date`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `images`
--
ALTER TABLE `images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID';
COMMIT;
