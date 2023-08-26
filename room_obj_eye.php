<?php
include_once('./_common.php');

$ro = sql_fetch("select * from {$g5['room_table']} where ro_id = '{$ro_id}'");

if($ro['ro_id']) {
	if($ro['ro_eye']=='1') {
	sql_query(" update {$g5['room_table']} set ro_eye = '0' where ro_id = '{$ro_id}'");
	echo "no";
	}
	if($ro['ro_eye']=='0') {
		sql_query(" update {$g5['room_table']} set ro_eye = '1' where ro_id = '{$ro_id}'");
		echo "see";
	}
}
?>
