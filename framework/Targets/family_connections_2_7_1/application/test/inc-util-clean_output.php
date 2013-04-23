#!/usr/bin/php -q
<?php
require_once 'lib/Test-More.php';
require_once '../inc/config_inc.php';
require_once '../inc/utils.php';

diag('cleanOutput');

plan(2);

$js_in  = '<script type="text/javascript">alert("hey")</script>';
$js_out = cleanOutput($js_in);

is($js_out, 'alert(&quot;hey&quot;)', 'javascript');


$js_html_in  = '<script type="text/javascript">alert("hey")</script>';
$js_html_out = cleanOutput($js_html_in, 'html');

is($js_html_out, '&lt;script type=&quot;text/javascript&quot;&gt;alert(&quot;hey&quot;)&lt;/script&gt;', 'javascript html');
