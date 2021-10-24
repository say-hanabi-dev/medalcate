<?
if(!defined('IN_DISCUZ')){
	exit('Access Denied');
}

$sql = <<<EOF
DROP TABLE IF EXISTS pre_medalcate;
CREATE TABLE pre_medalcate(
	`cateid` smallint(3) NOT NULL AUTO_INCREMENT,
	`catename` varchar(32) NOT NULL,
	`medalid` varchar(255) NOT NULL DEFAULT '',
	`displayorder` smallint(3) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY(`cateid`,`catename`),
	KEY `catename` (`catename`)
)
EOF;

runquery($sql);
$finish = TRUE;