<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/member.css">', 0);

?>
<style>
	<?if($ch['back']){?>
@media all and (min-width: 1001px) { 
	html			{
		background-image: url('<?=$ch['back']?>');
		background-size: cover;
		background-attachment: fixed;
		background-position: center;
	}
	/* 기본아보카도 적용 */
	html.single:before {
		background-image: url('<?=$ch['back']?>');
		background-size: cover;
		background-attachment: fixed;
		background-position: center;
	}
}

@media all and (max-width: 1000px) {
	html			{
		background-image: url('<?=$ch['back']?>');
		background-size: cover;
		background-attachment: fixed;
		background-position: center;
	}
	/* 기본아보카도 적용 */
	html.single:before {
		background-image: url('<?=$ch['back']?>');
		background-size: cover;
		background-attachment: fixed;
		background-position: center;
	}
}
@media all and (max-width: 800px) {
	.bottom_menu {
		display:none;
	}
}
<?}?>
#goto_top {display:none;}
</style>

<?if($ch['bgm']){?>
<style>
	.bgm-player {opacity:0;}
</style>

<div id="site_bgm_box2" style="display:none;">
		<iframe src="/bgm2.php?action=<?=$ch['bgm']?>" name="bgm_frame" id="bgm_frame" border="0" frameborder="0" marginheight="0" marginwidth="0" topmargin="0" scrolling="no" allowTransparency="true"></iframe>
</div>
<ul class="bgm-player2">
			<a href="<?=G5_URL?>/bgm2.php?action=<?=$ch['ch_bgm']?>" target="bgm_frame" class="play" id="play" onclick="return fn_control_bgm('play')">
				▶
			</a>
			<a href="<?=G5_URL?>/bgm2.php" target="bgm_frame" class="stop" onclick="return fn_control_bgm('stop')">
				■
			</a>
</ul>
<?}?>
<?if($ch['ch_type']!=='npc'){?>
<ul class="pair_au">
<? $cl_result = sql_query("select * from {$g5['character_table']} where mb_id = '{$ch['mb_id']}'");
    for($i=0; $row=sql_fetch_array($cl_result); $i++) {  ?>
      <li><a href="/member/viewer.php?ch_id=<?=$row['ch_id']?>" class="ship"><?if($row['ch_side']=='1') {echo '前';} else {echo '後';}?></a></li>
<? } ?>
</ul>
<?}?>

<style>
	#character_profile .profile_content .inner .stone {background-color: <?=$ch['color']?>;}
	#character_profile .en_name {	color: <?=$ch['color']?>;}
</style>

<div id="character_profile">

<div id="door" class="only-pc">
	<i class="fas fa-door-open"></i>
	<iframe src="<?=G5_URL?>/room/embed.php?mb_id=<?=$ch['mb_id']?>" style="width:1025px; height:600px;"></iframe>
</div>

<div class="pha kpub">“ <?=$ch['pha']?> ”</div>
<div class="ch_body"><img src="<?=$ch['ch_body']?>"></div>
<div class="grad"></div>
<div class="en_name ship animated fadeInUp"><?=$ch['en_name']?></div>
<div class="profile_bottom animated fadeInUp">
	<div class="side"><?=get_class_name($ch['ch_class'])?></div>
	<div><?=get_title_image($ch['ch_title'])?></div>
	<div class="kr_name neu"><?=$ch['ch_name']?></div>
	<div class="detail"><?=$ch['gender']?> · <?=$ch['age']?>세 · <?=$ch['height']?>CM / <?=$ch['weight']?>KG</div>
</div>

<?if($ch['look']){?>
<div class="profile_content">
	<h1 class="title ship">外觀<span class="kpub">외관</span></h1>
	<div class="inner kpub"><?=nl2br($ch['look'])?></div>
</div>
<?}?>

<div class="profile_content">
	<h1 class="title ship">脾氣<span class="kpub">성격</span></h1>
	<div class="inner kpub"><?=nl2br($ch['cha'])?></div>
</div>

<div class="profile_content">
	<h1 class="title ship">其他事項<span class="kpub">기타사항</span></h1>
	<div class="inner kpub">
		<div><span class="from neu"><?=$ch['from']?></span><span class="stone ship">靈</span></div>
		<?=nl2br($ch['etc'])?>
	</div>
</div>

<div class="profile_content">
	<h1 class="title ship">祕密設置<span class="kpub">비밀설정</span></h1>
	<div class="inner kpub">
		<? if($mb['mb_id'] == $member['mb_id']) { ?>
			<?=nl2br($ch['secret'])?>
		<? } else if ($is_admin) { ?>
			<?=nl2br($ch['secret'])?>
		<? } else { ?>
			<h1 class="txt-center txt-point" style=""><i class="fas fa-lock" style="margin-right:20px;"></i>열람할 수 없는 항목입니다.</h1>
		<? } ?>
	</div>
</div>


<div class="profile_content only-mo" id="title">
<h1 class="title ship">門<span class="kpub">방문</span></h1>
	<div class="title-list inner kpub">
	<iframe src="<?=G5_URL?>/room/embed.php?ch_id=<?=$ch_id?>" style="width:100%; height:600px; border:none;"></iframe>
	</div>
</div>
	
<div class="profile_content" id="title">
<h1 class="title ship">別號<span class="kpub">별호</span></h1>
	<div class="title-list inner kpub">
		<? for($i=0; $i < count($title); $i++) { ?>
			<img src="<?=$title[$i]['ti_img']?>" />
		<? }
			if($i == 0) { 
				echo "<div class='no-data'>보유중인 별호가 없습니다.</div>";
			}
		?>
	</div>
</div>
	

<div class="profile_content" id="inven">
<h1 class="title ship">囊<span class="kpub">주머니</span>
	<? if($article['ad_use_money']) { // 소지금 사용시 현재 보유 중인 소지금 출력 ?>
		<span style="float:right;font-size:1rem; margin-right: 10px; padding-top:15px;" class=" kpub">
			<em class="" style=""><?=$mb['mb_point']?></em><?=$config['cf_money_pice']?>
		</span>
	<? } ?>
	</h1>
	<div class="inner kpub">
		<? include(G5_PATH."/inventory/list.inc.php"); ?>
	</div>
</div>


<? if($ch['ch_state'] == '승인') { // 관계란 출력, 승인된 캐릭터만 출력됩니다. ?>
	<div class="profile_content" id="story">
	<h1 class="title ship">關係<span class="kpub">관계</span></h1>
	<div class="relation-box inner kpub">
		<ul class="relation-member-list">
			<?
				for($i=0; $i < count($relation); $i++) { 
					$re_ch = get_character($relation[$i]['re_ch_id']);
					if($relation[$i]['rm_memo'] == '') { continue; }
			?>
				<li>
					<div class="ui-thumb">
						<a href="<?=G5_URL?>/member/viewer.php?ch_id=<?=$re_ch['ch_id']?>" target="_blank">
							<img src="<?=$re_ch['ch_thumb']?>" />
						</a>
					</div>
					<div class="info">
						<div class="rm-name">
							<?=$re_ch['ch_name']?>
						</div>
						<div class="rm-like-style">
							<p>
								<? for($j=0; $j < 5; $j++) { 
									$class="";
									$style = "";
									if($j < $relation[$i]['rm_like']) {
										$class="txt-point";
									} else {
										$style="opacity: 0.2;";
									}

									echo "<i class='{$class}' style='{$style}'></i>";
								} ?>
							</p>
						</div>
					</div>
					<div class="memo  theme-box">
						<?=nl2br($relation[$i]['rm_memo'])?>
					</div>
					
					<ol>
						<?
							$relation[$i]['rm_link'] = nl2br($relation[$i]['rm_link']);
							$link_list = explode('<br />', $relation[$i]['rm_link']);
							for($j=0; $j < count($link_list); $j++) {
								$r_row = $link_list[$j];
								if(!$r_row) continue;
						?>
							<li>
								<a href="<?=$r_row?>" class="btn-log" target="_blank"></a>
							</li>
						<? } ?>
					</ol>
				</li>
			<? }?>
		</ul>
	</div>
	</div>
<? } ?>

	</div>
	<hr class="padding" />
	<hr class="padding" />
	
	<hr class="padding" />
	<hr class="padding" />
	
	<hr class="padding" />
	<hr class="padding" />
	
	<hr class="padding" />
	<hr class="padding" />
</div>


<?if($ch['bgm']){?>
<script>
	document.getElementById('pauseicon').click();
	$( window ).unload(function() {
		document.getElementById("playicon").click();
	});
</script>
<?}?>
<script>
	
$('#door').click( function() {
		$('#door iframe').fadeToggle();
} );
$('#inven .title').click( function() {
		$('#inven .inner').toggleClass('on');
} );
$('#title .title').click( function() {
		$('#title .inner').toggleClass('on');
} );
$('#story .title').click( function() {
		$('#story .inner').toggleClass('on');
} );
</script>