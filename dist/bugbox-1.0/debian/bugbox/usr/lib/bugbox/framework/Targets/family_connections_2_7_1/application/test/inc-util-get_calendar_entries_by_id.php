#!/usr/bin/php -q
<?php
require_once 'lib/Test-More.php';
require_once 'lib/utils.php';
require_once '../inc/config_inc.php';
require_once '../inc/utils.php';

diag('getCalendarEntriesById');

plan(7);

connectDatabase();

$bad_id  = getCalendarEntriesById(0);
$good_id = getCalendarEntriesById(1);
$good_id_count   = getCalendarEntriesById(1, 'count');
$good_id_percent = getCalendarEntriesById(1, 'percent');
$good_id_array   = getCalendarEntriesById(1, 'array');

is($bad_id, '0 (0%)', 'bad id');
ok(preg_match('/^\d+ \(\d+\.?\d?%\)$/', $good_id), 'good id');
ok(is_numeric($good_id_count), 'good id count');
ok(preg_match('/^\d+\.?\d?%$/', $good_id_percent), 'good id percent');
ok(is_array($good_id_array), 'good id array');
ok(is_numeric($good_id_array['count']), 'good id array count');
ok(preg_match('/^\d+\.?\d?%$/', $good_id_array['percent']), 'good id array percent');
