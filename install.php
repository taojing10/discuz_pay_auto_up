<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

global $_G;
$tablePre = $_G['config']['db'][1]['tablepre'];

$sql = <<<EOF
create table if not exists `{$tablePre}intcdz_thread_ding`(
    did int(10) unsigned auto_increment primary key,
    tid int(10) unsigned default 0,
    fid mediumint(8) unsigned default 0,
	number smallint(6) unsigned default 0 comment '申请数量',
	minute tinyint(2) unsigned null comment '顶帖分钟',
	expire int(10) unsigned null comment '到期时间',
    uid int(10) unsigned default 0,
    username varchar(25) null,
	extcredit tinyint(2) unsigned null,
	pay smallint(6) unsigned default 0,
    dateline int(10) unsigned null
) ENGINE = MYISAM CHARACTER SET gbk COLLATE gbk_chinese_ci;
EOF;

runquery($sql);

$finish = TRUE;

?>
