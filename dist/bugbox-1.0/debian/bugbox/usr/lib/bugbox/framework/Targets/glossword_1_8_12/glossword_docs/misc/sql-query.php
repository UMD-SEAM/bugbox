<?php
/**
 * Usage: 
 * 1. type a custom query into $oDb->sqlExec("SELECT VERSION()")
 * 2. upload to `/glossword`.
 * 3. open in browser `/glossword/sql-query.php`.
 */
include('db_config.php');
include('lib/class.func.php');
include('lib/class.db.mysql.php');
$oDb = new gwtkDb;

$arSql = $oDb->sqlExec("CHECK TABLE `gw_stat_search`");

prn_r( $arSql );
?>
<!--
> DROP TABLE IF EXISTS `gw_stat_search`;


-->
