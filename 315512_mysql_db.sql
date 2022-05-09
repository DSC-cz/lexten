-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Počítač: localhost:3306
-- Vytvořeno: Pon 09. kvě 2022, 22:05
-- Verze serveru: 5.7.38-0ubuntu0.18.04.1
-- Verze PHP: 7.2.24-0ubuntu0.18.04.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `315512_mysql_db`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `buy_tokens`
--

CREATE TABLE `buy_tokens` (
  `id` int(11) NOT NULL,
  `steamid` varchar(32) NOT NULL,
  `awps` int(11) NOT NULL,
  `molotovs` int(11) NOT NULL,
  `recieved` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `cidr_list`
--

CREATE TABLE `cidr_list` (
  `id` int(11) NOT NULL,
  `cidr` varchar(32) NOT NULL,
  `kick_message` varchar(64) NOT NULL DEFAULT 'IP BLOCKED',
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `cidr_log`
--

CREATE TABLE `cidr_log` (
  `id` int(11) NOT NULL,
  `ip` varbinary(16) NOT NULL,
  `steamid` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL,
  `cidr` varchar(32) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `cidr_whitelist`
--

CREATE TABLE `cidr_whitelist` (
  `id` int(11) NOT NULL,
  `type` enum('steam','ip') NOT NULL,
  `identity` varchar(32) NOT NULL,
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `estickers_users`
--

CREATE TABLE `estickers_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `steamid` varchar(18) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `estickers_user_stickers`
--

CREATE TABLE `estickers_user_stickers` (
  `fk_user` int(10) UNSIGNED NOT NULL,
  `def_index` int(10) UNSIGNED NOT NULL,
  `stickers` varchar(128) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `extendedcomm`
--

CREATE TABLE `extendedcomm` (
  `steam_id` varchar(32) NOT NULL DEFAULT '',
  `mute_type` int(4) NOT NULL DEFAULT '0',
  `mute_length` int(12) NOT NULL DEFAULT '0',
  `mute_admin` varchar(64) NOT NULL DEFAULT '',
  `mute_time` int(12) NOT NULL DEFAULT '0',
  `mute_reason` varchar(256) NOT NULL DEFAULT '',
  `mute_level` int(12) NOT NULL DEFAULT '0',
  `gag_type` int(4) NOT NULL DEFAULT '0',
  `gag_length` int(12) NOT NULL DEFAULT '0',
  `gag_admin` varchar(64) NOT NULL DEFAULT '',
  `gag_time` int(12) NOT NULL DEFAULT '0',
  `gag_reason` varchar(256) NOT NULL DEFAULT '',
  `gag_level` int(12) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `extendedcomm_version`
--

CREATE TABLE `extendedcomm_version` (
  `primary_key` int(4) NOT NULL DEFAULT '0',
  `current_version` varchar(8) NOT NULL DEFAULT '',
  `current_update` int(4) NOT NULL DEFAULT '0',
  `current_database` varchar(32) NOT NULL DEFAULT '',
  `last_prune` int(12) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `freevip_users`
--

CREATE TABLE `freevip_users` (
  `id` int(11) NOT NULL,
  `steamid` varchar(100) NOT NULL,
  `expired` text,
  `ip` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `gloves`
--

CREATE TABLE `gloves` (
  `steamid` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `t_group` int(5) NOT NULL DEFAULT '0',
  `t_glove` int(5) NOT NULL DEFAULT '0',
  `t_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `ct_group` int(5) NOT NULL DEFAULT '0',
  `ct_glove` int(5) NOT NULL DEFAULT '0',
  `ct_float` decimal(3,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `mapdata`
--

CREATE TABLE `mapdata` (
  `id` int(11) NOT NULL,
  `mapname` text CHARACTER SET utf8mb4 NOT NULL,
  `cellbutton` int(11) NOT NULL DEFAULT '0',
  `cellname` varchar(32) CHARACTER SET utf8mb4 NOT NULL DEFAULT 'celldoor',
  `kill01` int(11) DEFAULT '0',
  `kill02` int(11) NOT NULL DEFAULT '0',
  `kill03` int(11) NOT NULL DEFAULT '0',
  `openspot01` float NOT NULL DEFAULT '0',
  `openspot02` float NOT NULL DEFAULT '0',
  `openspot03` float NOT NULL DEFAULT '0',
  `roundsplayed` int(11) NOT NULL DEFAULT '0',
  `activerounds` int(11) NOT NULL DEFAULT '0',
  `wins_CT` int(11) DEFAULT '0',
  `wins_T` int(11) NOT NULL DEFAULT '0',
  `awins_CT` int(11) NOT NULL DEFAULT '0',
  `awins_T` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `mapnotes`
--

CREATE TABLE `mapnotes` (
  `id` int(11) NOT NULL,
  `sid` varchar(32) NOT NULL,
  `map` text NOT NULL,
  `text` text NOT NULL,
  `pos0` float NOT NULL,
  `pos1` float NOT NULL,
  `pos2` float NOT NULL,
  `color` text NOT NULL,
  `private` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `playtime`
--

CREATE TABLE `playtime` (
  `playername` varchar(128) NOT NULL,
  `steamid` varchar(32) NOT NULL,
  `last_accountuse` int(64) NOT NULL,
  `timeCT` int(16) DEFAULT NULL,
  `timeTT` int(16) DEFAULT NULL,
  `timeSPE` int(16) DEFAULT NULL,
  `total` int(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_adll`
--

CREATE TABLE `sm_adll` (
  `id` int(11) NOT NULL,
  `accountid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_admins`
--

CREATE TABLE `sm_admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `authtype` enum('steam','name','ip') NOT NULL,
  `identity` varchar(65) NOT NULL,
  `password` varchar(65) DEFAULT NULL,
  `flags` varchar(30) NOT NULL,
  `name` varchar(65) CHARACTER SET utf8mb4 NOT NULL,
  `immunity` int(10) UNSIGNED NOT NULL,
  `accountid` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_admins_groups`
--

CREATE TABLE `sm_admins_groups` (
  `admin_id` int(10) UNSIGNED NOT NULL,
  `group_id` int(10) UNSIGNED NOT NULL,
  `inherit_order` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_bans`
--

CREATE TABLE `sm_bans` (
  `id` int(8) NOT NULL,
  `accountid` int(32) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `reason` text NOT NULL,
  `added` int(64) NOT NULL,
  `expire` int(64) NOT NULL,
  `admin` int(32) NOT NULL,
  `unban` int(11) NOT NULL DEFAULT '0',
  `unban_admin` int(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_bombs`
--

CREATE TABLE `sm_bombs` (
  `id` int(11) NOT NULL,
  `accountid` int(11) NOT NULL,
  `name` text NOT NULL,
  `score` float NOT NULL,
  `damage` int(11) NOT NULL,
  `kills` int(11) NOT NULL,
  `players` int(11) NOT NULL,
  `alive_ct` int(11) NOT NULL,
  `alive_t` int(11) NOT NULL,
  `suicide` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `map` text NOT NULL,
  `cheat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_chat`
--

CREATE TABLE `sm_chat` (
  `id` int(11) NOT NULL,
  `accountid` int(10) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `map` varchar(32) CHARACTER SET utf8mb4 NOT NULL,
  `dead` tinyint(2) NOT NULL,
  `team` tinyint(2) NOT NULL,
  `teamchat` tinyint(2) NOT NULL,
  `shout` tinyint(2) NOT NULL DEFAULT '0',
  `message` text CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_chatlog`
--

CREATE TABLE `sm_chatlog` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `timestamp` text,
  `map` varchar(128) NOT NULL,
  `steamid` varchar(21) NOT NULL,
  `name` varchar(128) NOT NULL,
  `team` tinyint(2) DEFAULT '0',
  `player_team` tinyint(2) NOT NULL,
  `dead` tinyint(2) DEFAULT '0',
  `message` varchar(126) NOT NULL,
  `action` tinyint(2) NOT NULL,
  `attacker` varchar(128) DEFAULT NULL,
  `attacker_steamid` varchar(21) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_comms`
--

CREATE TABLE `sm_comms` (
  `id` int(8) NOT NULL,
  `accountid` int(32) NOT NULL,
  `reason` text NOT NULL,
  `added` int(64) NOT NULL,
  `expire` int(64) NOT NULL,
  `admin` int(32) DEFAULT NULL,
  `removed` int(4) NOT NULL DEFAULT '0',
  `removed_admin` int(32) DEFAULT NULL,
  `type` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_config`
--

CREATE TABLE `sm_config` (
  `cfg_key` varchar(32) NOT NULL,
  `cfg_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_cookies`
--

CREATE TABLE `sm_cookies` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `access` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_cookie_cache`
--

CREATE TABLE `sm_cookie_cache` (
  `player` varchar(65) NOT NULL,
  `cookie_id` int(10) NOT NULL,
  `value` varchar(100) DEFAULT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_ctlocks`
--

CREATE TABLE `sm_ctlocks` (
  `id` int(11) NOT NULL,
  `accountid` int(11) NOT NULL,
  `rounds_given` int(11) NOT NULL,
  `rounds_actual` int(11) NOT NULL DEFAULT '0',
  `time` int(64) NOT NULL,
  `admin_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin` int(11) NOT NULL DEFAULT '-1',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_dailypoints`
--

CREATE TABLE `sm_dailypoints` (
  `id` int(11) NOT NULL,
  `accountid` int(11) NOT NULL,
  `points` int(11) NOT NULL DEFAULT '0',
  `savedtime` int(11) NOT NULL DEFAULT '0',
  `lastweek` int(11) NOT NULL DEFAULT '-1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_groups`
--

CREATE TABLE `sm_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `flags` varchar(30) NOT NULL,
  `name` varchar(120) NOT NULL,
  `immunity_level` int(1) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_group_immunity`
--

CREATE TABLE `sm_group_immunity` (
  `group_id` int(10) UNSIGNED NOT NULL,
  `other_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_group_overrides`
--

CREATE TABLE `sm_group_overrides` (
  `group_id` int(10) UNSIGNED NOT NULL,
  `type` enum('command','group') NOT NULL,
  `name` varchar(32) NOT NULL,
  `access` enum('allow','deny') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_kviz`
--

CREATE TABLE `sm_kviz` (
  `id` int(11) NOT NULL,
  `category` text NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `option1` text NOT NULL,
  `option2` text NOT NULL,
  `option3` text NOT NULL,
  `displayed` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_kviz_kategorie`
--

CREATE TABLE `sm_kviz_kategorie` (
  `id` int(11) NOT NULL,
  `category` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_overrides`
--

CREATE TABLE `sm_overrides` (
  `type` enum('command','group') NOT NULL,
  `name` varchar(32) NOT NULL,
  `flags` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_playtime`
--

CREATE TABLE `sm_playtime` (
  `id` int(11) NOT NULL,
  `accountid` int(10) DEFAULT NULL,
  `lastaccountuse` int(64) NOT NULL,
  `t` int(11) NOT NULL DEFAULT '0',
  `ct` int(11) NOT NULL DEFAULT '0',
  `spec` int(11) NOT NULL DEFAULT '0',
  `unassigned` int(11) NOT NULL DEFAULT '0',
  `warden` int(11) NOT NULL DEFAULT '0',
  `freeday` int(11) NOT NULL DEFAULT '0',
  `rebel` int(11) NOT NULL DEFAULT '0',
  `warmup` int(11) NOT NULL DEFAULT '0',
  `clantag` int(11) NOT NULL DEFAULT '0',
  `alive` int(11) NOT NULL DEFAULT '0',
  `dead` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_raters`
--

CREATE TABLE `sm_raters` (
  `id` int(11) NOT NULL,
  `rater` int(11) NOT NULL,
  `warden` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_session`
--

CREATE TABLE `sm_session` (
  `id` int(11) NOT NULL,
  `accountid` int(10) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start` bigint(64) DEFAULT NULL,
  `end` bigint(64) DEFAULT NULL,
  `connected` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_talktime`
--

CREATE TABLE `sm_talktime` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `steamid` varchar(32) NOT NULL,
  `total` float NOT NULL DEFAULT '0',
  `alive` float NOT NULL DEFAULT '0',
  `dead` float NOT NULL DEFAULT '0',
  `t` float NOT NULL DEFAULT '0',
  `ct` float NOT NULL DEFAULT '0',
  `spec` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_upgrades`
--

CREATE TABLE `sm_upgrades` (
  `id` int(11) NOT NULL,
  `steamid` varchar(18) NOT NULL,
  `karma` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_wardens`
--

CREATE TABLE `sm_wardens` (
  `id` int(11) NOT NULL,
  `accountid` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `raters` int(11) NOT NULL,
  `lastwarden` int(11) NOT NULL,
  `wardencount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sm_warnings`
--

CREATE TABLE `sm_warnings` (
  `id` int(11) NOT NULL,
  `accountid` int(11) NOT NULL,
  `warninglevel` int(11) NOT NULL DEFAULT '0',
  `punishments` int(11) NOT NULL DEFAULT '0',
  `lastpunishment` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `specialdays`
--

CREATE TABLE `specialdays` (
  `id` int(11) NOT NULL,
  `accountid` int(11) NOT NULL,
  `day` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text NOT NULL,
  `played` int(11) NOT NULL DEFAULT '0',
  `wins` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `store_items`
--

CREATE TABLE `store_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `item_id` varchar(16) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `store_players`
--

CREATE TABLE `store_players` (
  `account_id` int(10) UNSIGNED NOT NULL,
  `lextenium` int(10) UNSIGNED NOT NULL,
  `tokens` int(10) UNSIGNED NOT NULL,
  `karma` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `store_skills`
--

CREATE TABLE `store_skills` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `skill_id` varchar(16) NOT NULL,
  `level` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `knownas` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `steamid` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` text NOT NULL,
  `firstjoin` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vip` int(11) NOT NULL DEFAULT '0',
  `fd_nr` int(11) NOT NULL,
  `fd` int(11) NOT NULL,
  `ct_kills` int(11) NOT NULL DEFAULT '0',
  `ct_kills_hs` int(11) NOT NULL DEFAULT '0',
  `ct_kills_ns` int(11) NOT NULL DEFAULT '0',
  `ct_deaths` int(11) NOT NULL DEFAULT '0',
  `t_kills` int(11) DEFAULT '0',
  `t_kills_hs` int(11) NOT NULL DEFAULT '0',
  `t_kills_ns` int(11) NOT NULL DEFAULT '0',
  `t_deaths` int(11) NOT NULL DEFAULT '0',
  `bombs` int(11) NOT NULL DEFAULT '0',
  `lg_claims` int(11) NOT NULL DEFAULT '0',
  `pp_claims` int(11) NOT NULL DEFAULT '0',
  `warden_count` int(11) NOT NULL DEFAULT '0',
  `prest_wins` int(11) NOT NULL DEFAULT '0',
  `oitc_wins` int(11) NOT NULL DEFAULT '0',
  `fd_count` int(11) NOT NULL DEFAULT '0',
  `accountid` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabulky `weapons`
--

CREATE TABLE `weapons` (
  `steamid` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `knife` int(4) NOT NULL DEFAULT '0',
  `awp` int(4) NOT NULL DEFAULT '0',
  `awp_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `awp_trak` int(1) NOT NULL DEFAULT '0',
  `awp_trak_count` int(10) NOT NULL DEFAULT '0',
  `awp_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `awp_seed` int(10) NOT NULL DEFAULT '-1',
  `ak47` int(4) NOT NULL DEFAULT '0',
  `ak47_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `ak47_trak` int(1) NOT NULL DEFAULT '0',
  `ak47_trak_count` int(10) NOT NULL DEFAULT '0',
  `ak47_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ak47_seed` int(10) NOT NULL DEFAULT '-1',
  `m4a1` int(4) NOT NULL DEFAULT '0',
  `m4a1_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `m4a1_trak` int(1) NOT NULL DEFAULT '0',
  `m4a1_trak_count` int(10) NOT NULL DEFAULT '0',
  `m4a1_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `m4a1_seed` int(10) NOT NULL DEFAULT '-1',
  `m4a1_silencer` int(4) NOT NULL DEFAULT '0',
  `m4a1_silencer_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `m4a1_silencer_trak` int(1) NOT NULL DEFAULT '0',
  `m4a1_silencer_trak_count` int(10) NOT NULL DEFAULT '0',
  `m4a1_silencer_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `m4a1_silencer_seed` int(10) NOT NULL DEFAULT '-1',
  `deagle` int(4) NOT NULL DEFAULT '0',
  `deagle_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `deagle_trak` int(1) NOT NULL DEFAULT '0',
  `deagle_trak_count` int(10) NOT NULL DEFAULT '0',
  `deagle_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `deagle_seed` int(10) NOT NULL DEFAULT '-1',
  `usp_silencer` int(4) NOT NULL DEFAULT '0',
  `usp_silencer_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `usp_silencer_trak` int(1) NOT NULL DEFAULT '0',
  `usp_silencer_trak_count` int(10) NOT NULL DEFAULT '0',
  `usp_silencer_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `usp_silencer_seed` int(10) NOT NULL DEFAULT '-1',
  `hkp2000` int(4) NOT NULL DEFAULT '0',
  `hkp2000_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `hkp2000_trak` int(1) NOT NULL DEFAULT '0',
  `hkp2000_trak_count` int(10) NOT NULL DEFAULT '0',
  `hkp2000_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `hkp2000_seed` int(10) NOT NULL DEFAULT '-1',
  `glock` int(4) NOT NULL DEFAULT '0',
  `glock_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `glock_trak` int(1) NOT NULL DEFAULT '0',
  `glock_trak_count` int(10) NOT NULL DEFAULT '0',
  `glock_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `glock_seed` int(10) NOT NULL DEFAULT '-1',
  `elite` int(4) NOT NULL DEFAULT '0',
  `elite_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `elite_trak` int(1) NOT NULL DEFAULT '0',
  `elite_trak_count` int(10) NOT NULL DEFAULT '0',
  `elite_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `elite_seed` int(10) NOT NULL DEFAULT '-1',
  `p250` int(4) NOT NULL DEFAULT '0',
  `p250_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `p250_trak` int(1) NOT NULL DEFAULT '0',
  `p250_trak_count` int(10) NOT NULL DEFAULT '0',
  `p250_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `p250_seed` int(10) NOT NULL DEFAULT '-1',
  `cz75a` int(4) NOT NULL DEFAULT '0',
  `cz75a_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `cz75a_trak` int(1) NOT NULL DEFAULT '0',
  `cz75a_trak_count` int(10) NOT NULL DEFAULT '0',
  `cz75a_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cz75a_seed` int(10) NOT NULL DEFAULT '-1',
  `fiveseven` int(4) NOT NULL DEFAULT '0',
  `fiveseven_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `fiveseven_trak` int(1) NOT NULL DEFAULT '0',
  `fiveseven_trak_count` int(10) NOT NULL DEFAULT '0',
  `fiveseven_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fiveseven_seed` int(10) NOT NULL DEFAULT '-1',
  `tec9` int(4) NOT NULL DEFAULT '0',
  `tec9_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `tec9_trak` int(1) NOT NULL DEFAULT '0',
  `tec9_trak_count` int(10) NOT NULL DEFAULT '0',
  `tec9_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tec9_seed` int(10) NOT NULL DEFAULT '-1',
  `revolver` int(4) NOT NULL DEFAULT '0',
  `revolver_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `revolver_trak` int(1) NOT NULL DEFAULT '0',
  `revolver_trak_count` int(10) NOT NULL DEFAULT '0',
  `revolver_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `revolver_seed` int(10) NOT NULL DEFAULT '-1',
  `nova` int(4) NOT NULL DEFAULT '0',
  `nova_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `nova_trak` int(1) NOT NULL DEFAULT '0',
  `nova_trak_count` int(10) NOT NULL DEFAULT '0',
  `nova_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nova_seed` int(10) NOT NULL DEFAULT '-1',
  `xm1014` int(4) NOT NULL DEFAULT '0',
  `xm1014_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `xm1014_trak` int(1) NOT NULL DEFAULT '0',
  `xm1014_trak_count` int(10) NOT NULL DEFAULT '0',
  `xm1014_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `xm1014_seed` int(10) NOT NULL DEFAULT '-1',
  `mag7` int(4) NOT NULL DEFAULT '0',
  `mag7_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `mag7_trak` int(1) NOT NULL DEFAULT '0',
  `mag7_trak_count` int(10) NOT NULL DEFAULT '0',
  `mag7_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `mag7_seed` int(10) NOT NULL DEFAULT '-1',
  `sawedoff` int(4) NOT NULL DEFAULT '0',
  `sawedoff_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `sawedoff_trak` int(1) NOT NULL DEFAULT '0',
  `sawedoff_trak_count` int(10) NOT NULL DEFAULT '0',
  `sawedoff_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sawedoff_seed` int(10) NOT NULL DEFAULT '-1',
  `m249` int(4) NOT NULL DEFAULT '0',
  `m249_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `m249_trak` int(1) NOT NULL DEFAULT '0',
  `m249_trak_count` int(10) NOT NULL DEFAULT '0',
  `m249_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `m249_seed` int(10) NOT NULL DEFAULT '-1',
  `negev` int(4) NOT NULL DEFAULT '0',
  `negev_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `negev_trak` int(1) NOT NULL DEFAULT '0',
  `negev_trak_count` int(10) NOT NULL DEFAULT '0',
  `negev_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `negev_seed` int(10) NOT NULL DEFAULT '-1',
  `mp9` int(4) NOT NULL DEFAULT '0',
  `mp9_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `mp9_trak` int(1) NOT NULL DEFAULT '0',
  `mp9_trak_count` int(10) NOT NULL DEFAULT '0',
  `mp9_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `mp9_seed` int(10) NOT NULL DEFAULT '-1',
  `mac10` int(4) NOT NULL DEFAULT '0',
  `mac10_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `mac10_trak` int(1) NOT NULL DEFAULT '0',
  `mac10_trak_count` int(10) NOT NULL DEFAULT '0',
  `mac10_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `mac10_seed` int(10) NOT NULL DEFAULT '-1',
  `mp7` int(4) NOT NULL DEFAULT '0',
  `mp7_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `mp7_trak` int(1) NOT NULL DEFAULT '0',
  `mp7_trak_count` int(10) NOT NULL DEFAULT '0',
  `mp7_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `mp7_seed` int(10) NOT NULL DEFAULT '-1',
  `ump45` int(4) NOT NULL DEFAULT '0',
  `ump45_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `ump45_trak` int(1) NOT NULL DEFAULT '0',
  `ump45_trak_count` int(10) NOT NULL DEFAULT '0',
  `ump45_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ump45_seed` int(10) NOT NULL DEFAULT '-1',
  `p90` int(4) NOT NULL DEFAULT '0',
  `p90_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `p90_trak` int(1) NOT NULL DEFAULT '0',
  `p90_trak_count` int(10) NOT NULL DEFAULT '0',
  `p90_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `p90_seed` int(10) NOT NULL DEFAULT '-1',
  `bizon` int(4) NOT NULL DEFAULT '0',
  `bizon_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `bizon_trak` int(1) NOT NULL DEFAULT '0',
  `bizon_trak_count` int(10) NOT NULL DEFAULT '0',
  `bizon_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `bizon_seed` int(10) NOT NULL DEFAULT '-1',
  `famas` int(4) NOT NULL DEFAULT '0',
  `famas_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `famas_trak` int(1) NOT NULL DEFAULT '0',
  `famas_trak_count` int(10) NOT NULL DEFAULT '0',
  `famas_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `famas_seed` int(10) NOT NULL DEFAULT '-1',
  `galilar` int(4) NOT NULL DEFAULT '0',
  `galilar_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `galilar_trak` int(1) NOT NULL DEFAULT '0',
  `galilar_trak_count` int(10) NOT NULL DEFAULT '0',
  `galilar_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `galilar_seed` int(10) NOT NULL DEFAULT '-1',
  `ssg08` int(4) NOT NULL DEFAULT '0',
  `ssg08_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `ssg08_trak` int(1) NOT NULL DEFAULT '0',
  `ssg08_trak_count` int(10) NOT NULL DEFAULT '0',
  `ssg08_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ssg08_seed` int(10) NOT NULL DEFAULT '-1',
  `aug` int(4) NOT NULL DEFAULT '0',
  `aug_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `aug_trak` int(1) NOT NULL DEFAULT '0',
  `aug_trak_count` int(10) NOT NULL DEFAULT '0',
  `aug_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `aug_seed` int(10) NOT NULL DEFAULT '-1',
  `sg556` int(4) NOT NULL DEFAULT '0',
  `sg556_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `sg556_trak` int(1) NOT NULL DEFAULT '0',
  `sg556_trak_count` int(10) NOT NULL DEFAULT '0',
  `sg556_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sg556_seed` int(10) NOT NULL DEFAULT '-1',
  `scar20` int(4) NOT NULL DEFAULT '0',
  `scar20_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `scar20_trak` int(1) NOT NULL DEFAULT '0',
  `scar20_trak_count` int(10) NOT NULL DEFAULT '0',
  `scar20_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `scar20_seed` int(10) NOT NULL DEFAULT '-1',
  `g3sg1` int(4) NOT NULL DEFAULT '0',
  `g3sg1_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `g3sg1_trak` int(1) NOT NULL DEFAULT '0',
  `g3sg1_trak_count` int(10) NOT NULL DEFAULT '0',
  `g3sg1_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `g3sg1_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_karambit` int(4) NOT NULL DEFAULT '0',
  `knife_karambit_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_karambit_trak` int(1) NOT NULL DEFAULT '0',
  `knife_karambit_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_karambit_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_karambit_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_m9_bayonet` int(4) NOT NULL DEFAULT '0',
  `knife_m9_bayonet_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_m9_bayonet_trak` int(1) NOT NULL DEFAULT '0',
  `knife_m9_bayonet_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_m9_bayonet_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_m9_bayonet_seed` int(10) NOT NULL DEFAULT '-1',
  `bayonet` int(4) NOT NULL DEFAULT '0',
  `bayonet_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `bayonet_trak` int(1) NOT NULL DEFAULT '0',
  `bayonet_trak_count` int(10) NOT NULL DEFAULT '0',
  `bayonet_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `bayonet_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_survival_bowie` int(4) NOT NULL DEFAULT '0',
  `knife_survival_bowie_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_survival_bowie_trak` int(1) NOT NULL DEFAULT '0',
  `knife_survival_bowie_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_survival_bowie_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_survival_bowie_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_butterfly` int(4) NOT NULL DEFAULT '0',
  `knife_butterfly_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_butterfly_trak` int(1) NOT NULL DEFAULT '0',
  `knife_butterfly_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_butterfly_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_butterfly_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_flip` int(4) NOT NULL DEFAULT '0',
  `knife_flip_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_flip_trak` int(1) NOT NULL DEFAULT '0',
  `knife_flip_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_flip_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_flip_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_push` int(4) NOT NULL DEFAULT '0',
  `knife_push_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_push_trak` int(1) NOT NULL DEFAULT '0',
  `knife_push_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_push_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_push_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_tactical` int(4) NOT NULL DEFAULT '0',
  `knife_tactical_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_tactical_trak` int(1) NOT NULL DEFAULT '0',
  `knife_tactical_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_tactical_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_tactical_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_falchion` int(4) NOT NULL DEFAULT '0',
  `knife_falchion_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_falchion_trak` int(1) NOT NULL DEFAULT '0',
  `knife_falchion_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_falchion_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_falchion_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_gut` int(4) NOT NULL DEFAULT '0',
  `knife_gut_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_gut_trak` int(1) NOT NULL DEFAULT '0',
  `knife_gut_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_gut_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_gut_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_ursus` int(4) NOT NULL DEFAULT '0',
  `knife_ursus_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_ursus_trak` int(1) NOT NULL DEFAULT '0',
  `knife_ursus_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_ursus_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_ursus_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_gypsy_jackknife` int(4) NOT NULL DEFAULT '0',
  `knife_gypsy_jackknife_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_gypsy_jackknife_trak` int(1) NOT NULL DEFAULT '0',
  `knife_gypsy_jackknife_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_gypsy_jackknife_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_gypsy_jackknife_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_stiletto` int(4) NOT NULL DEFAULT '0',
  `knife_stiletto_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_stiletto_trak` int(1) NOT NULL DEFAULT '0',
  `knife_stiletto_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_stiletto_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_stiletto_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_widowmaker` int(4) NOT NULL DEFAULT '0',
  `knife_widowmaker_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_widowmaker_trak` int(1) NOT NULL DEFAULT '0',
  `knife_widowmaker_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_widowmaker_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_widowmaker_seed` int(10) NOT NULL DEFAULT '-1',
  `mp5sd` int(4) NOT NULL DEFAULT '0',
  `mp5sd_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `mp5sd_trak` int(1) NOT NULL DEFAULT '0',
  `mp5sd_trak_count` int(10) NOT NULL DEFAULT '0',
  `mp5sd_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `mp5sd_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_css` int(4) NOT NULL DEFAULT '0',
  `knife_css_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_css_trak` int(1) NOT NULL DEFAULT '0',
  `knife_css_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_css_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_css_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_cord` int(4) NOT NULL DEFAULT '0',
  `knife_cord_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_cord_trak` int(1) NOT NULL DEFAULT '0',
  `knife_cord_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_cord_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_cord_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_canis` int(4) NOT NULL DEFAULT '0',
  `knife_canis_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_canis_trak` int(1) NOT NULL DEFAULT '0',
  `knife_canis_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_canis_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_canis_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_outdoor` int(4) NOT NULL DEFAULT '0',
  `knife_outdoor_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_outdoor_trak` int(1) NOT NULL DEFAULT '0',
  `knife_outdoor_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_outdoor_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_outdoor_seed` int(10) NOT NULL DEFAULT '-1',
  `knife_skeleton` int(4) NOT NULL DEFAULT '0',
  `knife_skeleton_float` decimal(3,2) NOT NULL DEFAULT '0.00',
  `knife_skeleton_trak` int(1) NOT NULL DEFAULT '0',
  `knife_skeleton_trak_count` int(10) NOT NULL DEFAULT '0',
  `knife_skeleton_tag` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `knife_skeleton_seed` int(10) NOT NULL DEFAULT '-1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `weapons_timestamps`
--

CREATE TABLE `weapons_timestamps` (
  `steamid` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_seen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `buy_tokens`
--
ALTER TABLE `buy_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `cidr_list`
--
ALTER TABLE `cidr_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cidr` (`cidr`),
  ADD KEY `cidr_2` (`cidr`);

--
-- Klíče pro tabulku `cidr_log`
--
ALTER TABLE `cidr_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `steamid` (`steamid`),
  ADD KEY `ip` (`ip`),
  ADD KEY `cidr` (`cidr`);

--
-- Klíče pro tabulku `cidr_whitelist`
--
ALTER TABLE `cidr_whitelist`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `estickers_users`
--
ALTER TABLE `estickers_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `steamid_unique` (`steamid`);

--
-- Klíče pro tabulku `estickers_user_stickers`
--
ALTER TABLE `estickers_user_stickers`
  ADD PRIMARY KEY (`fk_user`,`def_index`),
  ADD UNIQUE KEY `UNIQUE1` (`fk_user`,`def_index`);

--
-- Klíče pro tabulku `extendedcomm`
--
ALTER TABLE `extendedcomm`
  ADD PRIMARY KEY (`steam_id`);

--
-- Klíče pro tabulku `extendedcomm_version`
--
ALTER TABLE `extendedcomm_version`
  ADD PRIMARY KEY (`primary_key`);

--
-- Klíče pro tabulku `freevip_users`
--
ALTER TABLE `freevip_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `steamid` (`steamid`) USING BTREE;

--
-- Klíče pro tabulku `gloves`
--
ALTER TABLE `gloves`
  ADD PRIMARY KEY (`steamid`);

--
-- Klíče pro tabulku `mapdata`
--
ALTER TABLE `mapdata`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `mapnotes`
--
ALTER TABLE `mapnotes`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `playtime`
--
ALTER TABLE `playtime`
  ADD PRIMARY KEY (`steamid`),
  ADD UNIQUE KEY `steamid` (`steamid`) USING BTREE,
  ADD KEY `total` (`total`) USING BTREE;

--
-- Klíče pro tabulku `sm_adll`
--
ALTER TABLE `sm_adll`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `sm_admins`
--
ALTER TABLE `sm_admins`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `sm_admins_groups`
--
ALTER TABLE `sm_admins_groups`
  ADD PRIMARY KEY (`admin_id`,`group_id`);

--
-- Klíče pro tabulku `sm_bans`
--
ALTER TABLE `sm_bans`
  ADD PRIMARY KEY (`id`,`accountid`),
  ADD KEY `accountid` (`accountid`) USING BTREE,
  ADD KEY `admin` (`admin`) USING BTREE;

--
-- Klíče pro tabulku `sm_bombs`
--
ALTER TABLE `sm_bombs`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `sm_chat`
--
ALTER TABLE `sm_chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accountid` (`accountid`) USING BTREE;

--
-- Klíče pro tabulku `sm_chatlog`
--
ALTER TABLE `sm_chatlog`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Klíče pro tabulku `sm_comms`
--
ALTER TABLE `sm_comms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accountid` (`accountid`) USING BTREE,
  ADD KEY `admin` (`admin`) USING BTREE;

--
-- Klíče pro tabulku `sm_config`
--
ALTER TABLE `sm_config`
  ADD PRIMARY KEY (`cfg_key`);

--
-- Klíče pro tabulku `sm_cookies`
--
ALTER TABLE `sm_cookies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Klíče pro tabulku `sm_cookie_cache`
--
ALTER TABLE `sm_cookie_cache`
  ADD PRIMARY KEY (`player`,`cookie_id`);

--
-- Klíče pro tabulku `sm_ctlocks`
--
ALTER TABLE `sm_ctlocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accountid` (`accountid`) USING BTREE,
  ADD KEY `account_admin` (`admin`) USING BTREE;

--
-- Klíče pro tabulku `sm_dailypoints`
--
ALTER TABLE `sm_dailypoints`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `sm_groups`
--
ALTER TABLE `sm_groups`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `sm_group_immunity`
--
ALTER TABLE `sm_group_immunity`
  ADD PRIMARY KEY (`group_id`,`other_id`);

--
-- Klíče pro tabulku `sm_group_overrides`
--
ALTER TABLE `sm_group_overrides`
  ADD PRIMARY KEY (`group_id`,`type`,`name`);

--
-- Klíče pro tabulku `sm_kviz`
--
ALTER TABLE `sm_kviz`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `sm_kviz_kategorie`
--
ALTER TABLE `sm_kviz_kategorie`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `sm_overrides`
--
ALTER TABLE `sm_overrides`
  ADD PRIMARY KEY (`type`,`name`);

--
-- Klíče pro tabulku `sm_playtime`
--
ALTER TABLE `sm_playtime`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `sm_raters`
--
ALTER TABLE `sm_raters`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `sm_session`
--
ALTER TABLE `sm_session`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `sm_talktime`
--
ALTER TABLE `sm_talktime`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `sm_upgrades`
--
ALTER TABLE `sm_upgrades`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `sm_wardens`
--
ALTER TABLE `sm_wardens`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `sm_warnings`
--
ALTER TABLE `sm_warnings`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `specialdays`
--
ALTER TABLE `specialdays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accountid` (`accountid`,`day`) USING HASH;

--
-- Klíče pro tabulku `store_items`
--
ALTER TABLE `store_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_item_entry` (`account_id`,`item_id`);

--
-- Klíče pro tabulku `store_players`
--
ALTER TABLE `store_players`
  ADD PRIMARY KEY (`account_id`);

--
-- Klíče pro tabulku `store_skills`
--
ALTER TABLE `store_skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_skill_entry` (`account_id`,`skill_id`);

--
-- Klíče pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `steamid` (`steamid`) USING BTREE,
  ADD UNIQUE KEY `accountid` (`accountid`) USING BTREE,
  ADD KEY `vip` (`vip`) USING BTREE;

--
-- Klíče pro tabulku `weapons`
--
ALTER TABLE `weapons`
  ADD PRIMARY KEY (`steamid`);

--
-- Klíče pro tabulku `weapons_timestamps`
--
ALTER TABLE `weapons_timestamps`
  ADD PRIMARY KEY (`steamid`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `buy_tokens`
--
ALTER TABLE `buy_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pro tabulku `cidr_list`
--
ALTER TABLE `cidr_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4405;
--
-- AUTO_INCREMENT pro tabulku `cidr_log`
--
ALTER TABLE `cidr_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pro tabulku `cidr_whitelist`
--
ALTER TABLE `cidr_whitelist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pro tabulku `estickers_users`
--
ALTER TABLE `estickers_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169098;
--
-- AUTO_INCREMENT pro tabulku `freevip_users`
--
ALTER TABLE `freevip_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=398;
--
-- AUTO_INCREMENT pro tabulku `mapdata`
--
ALTER TABLE `mapdata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;
--
-- AUTO_INCREMENT pro tabulku `mapnotes`
--
ALTER TABLE `mapnotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;
--
-- AUTO_INCREMENT pro tabulku `sm_adll`
--
ALTER TABLE `sm_adll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;
--
-- AUTO_INCREMENT pro tabulku `sm_admins`
--
ALTER TABLE `sm_admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT pro tabulku `sm_bans`
--
ALTER TABLE `sm_bans`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1113;
--
-- AUTO_INCREMENT pro tabulku `sm_bombs`
--
ALTER TABLE `sm_bombs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35721;
--
-- AUTO_INCREMENT pro tabulku `sm_chat`
--
ALTER TABLE `sm_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=874111;
--
-- AUTO_INCREMENT pro tabulku `sm_chatlog`
--
ALTER TABLE `sm_chatlog`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=358604;
--
-- AUTO_INCREMENT pro tabulku `sm_comms`
--
ALTER TABLE `sm_comms`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=882;
--
-- AUTO_INCREMENT pro tabulku `sm_cookies`
--
ALTER TABLE `sm_cookies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18127;
--
-- AUTO_INCREMENT pro tabulku `sm_ctlocks`
--
ALTER TABLE `sm_ctlocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2316;
--
-- AUTO_INCREMENT pro tabulku `sm_dailypoints`
--
ALTER TABLE `sm_dailypoints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pro tabulku `sm_groups`
--
ALTER TABLE `sm_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pro tabulku `sm_kviz`
--
ALTER TABLE `sm_kviz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;
--
-- AUTO_INCREMENT pro tabulku `sm_kviz_kategorie`
--
ALTER TABLE `sm_kviz_kategorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pro tabulku `sm_playtime`
--
ALTER TABLE `sm_playtime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10145;
--
-- AUTO_INCREMENT pro tabulku `sm_raters`
--
ALTER TABLE `sm_raters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pro tabulku `sm_session`
--
ALTER TABLE `sm_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57916;
--
-- AUTO_INCREMENT pro tabulku `sm_talktime`
--
ALTER TABLE `sm_talktime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10722;
--
-- AUTO_INCREMENT pro tabulku `sm_upgrades`
--
ALTER TABLE `sm_upgrades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2474;
--
-- AUTO_INCREMENT pro tabulku `sm_wardens`
--
ALTER TABLE `sm_wardens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `sm_warnings`
--
ALTER TABLE `sm_warnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6637;
--
-- AUTO_INCREMENT pro tabulku `specialdays`
--
ALTER TABLE `specialdays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7483;
--
-- AUTO_INCREMENT pro tabulku `store_items`
--
ALTER TABLE `store_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9989;
--
-- AUTO_INCREMENT pro tabulku `store_skills`
--
ALTER TABLE `store_skills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;
--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11257;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
