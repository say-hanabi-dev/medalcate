<?
if(!defined('IN_DISCUZ')){
	exit('Access Denied');
}

$sql = <<<EOF
DROP TABLE IF EXISTS pre_medalcate;
EOF;

runquery($sql);
$finish = TRUE;