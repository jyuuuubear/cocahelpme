<?php
include_once('./_common.php');

$ro = sql_fetch("select * from {$g5['room_table']} where ro_id = '{$ro_id}'");

if($ro['ro_id']) {
	if($ro['ro_flip']=='1') {
	sql_query(" update {$g5['room_table']} set ro_flip = '0' where ro_id = '{$ro_id}'");
	echo "no";
	}
	if($ro['ro_flip']=='0') {
		sql_query(" update {$g5['room_table']} set ro_flip = '1' where ro_id = '{$ro_id}'");
		echo "flip";
	}
}
?>
