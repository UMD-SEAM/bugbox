<?php $diagResults = array (
  'Client' => 'Mozilla/5.0 (X11; Linux i686; rv:13.0) Gecko/20100101 Firefox/13.0.1 Iceweasel/13.0.1',
  'Magic Quotes Enabled' => 'No',
  'GD Enabled' => 'Yes',
  'Upload Max Size' => '2M',
  'Memory Limit' => '128M',
  'Max execution time' => '30',
  'Safe Mode' => '0',
  'Safe Mode GID' => '0',
  'MCrypt Enabled' => 'Yes',
  'Serveur OS' => 'Linux',
  'PHP Version' => '5.4.4-14',
  'Locale' => 'C',
  'Directory Separator' => '/',
  'Upload Tmp Dir Writeable' => true,
  'PHP Upload Max Size' => 2097152,
  'AJXP Upload Max Size' => '0',
  'Users enabled' => 1,
  'Guest enabled' => 0,
  '[Server, logs, conf]' => '[1,1,1]',
  'Zlib Enabled' => 'Yes',
);$outputArray = array (
  0 => 
  array (
    'name' => 'AjaXplorer version',
    'result' => false,
    'level' => 'info',
    'info' => 'AJXP version : 2.5',
  ),
  1 => 
  array (
    'name' => 'Client Browser',
    'result' => false,
    'level' => 'info',
    'info' => 'Current client Mozilla/5.0 (X11; Linux i686; rv:13.0) Gecko/20100101 Firefox/13.0.1 Iceweasel/13.0.1',
  ),
  2 => 
  array (
    'name' => 'Magic quotes',
    'result' => false,
    'level' => 'info',
    'info' => 'Magic quotes enabled : ',
  ),
  3 => 
  array (
    'name' => 'PHP GD version',
    'result' => true,
    'level' => 'warning',
    'info' => 'GD is required for generating thumbnails',
  ),
  4 => 
  array (
    'name' => 'PHP Limits variables',
    'result' => false,
    'level' => 'info',
    'info' => '<b>Testing configs</b>
Upload Max Size=2M
Memory Limit=128M
Max execution time=30
Safe Mode=0
Safe Mode GID=0',
  ),
  5 => 
  array (
    'name' => 'MCrypt enabled',
    'result' => true,
    'level' => 'warning',
    'info' => 'MCrypt is required for generating publiclets',
  ),
  6 => 
  array (
    'name' => 'PHP operating system',
    'result' => false,
    'level' => 'info',
    'info' => 'Current operating system Linux',
  ),
  7 => 
  array (
    'name' => 'PHP version',
    'result' => true,
    'level' => 'error',
    'info' => 'Minimum required version is PHP4.2.0, PHP5.2 or higher recommended when using foreign language',
  ),
  8 => 
  array (
    'name' => 'Server charset encoding',
    'result' => false,
    'level' => 'warning',
    'info' => 'You must set a correct charset encoding in your locale definition in the form: en_us.UTF-8. Please refer to setlocale man page. Detected locale: C (using UTF-8)',
  ),
  9 => 
  array (
    'name' => 'Upload particularities',
    'result' => false,
    'level' => 'info',
    'info' => '<b>Testing configs</b>
Upload Tmp Dir Writeable=1
PHP Upload Max Size=2097152
AJXP Upload Max Size=0',
  ),
  10 => 
  array (
    'name' => 'Users Configuration',
    'result' => false,
    'level' => 'info',
    'info' => 'Current config for users',
  ),
  11 => 
  array (
    'name' => 'Required writeable folder',
    'result' => false,
    'level' => 'info',
    'info' => '[1,1,1]',
  ),
  12 => 
  array (
    'name' => 'Zlib extension (ZIP)',
    'result' => false,
    'level' => 'info',
    'info' => 'Extension enabled : 1',
  ),
  13 => 
  array (
    'name' => 'Filesystem Plugin
 Testing repository : Default Files',
    'result' => true,
    'level' => 'error',
    'info' => '',
  ),
); ?>