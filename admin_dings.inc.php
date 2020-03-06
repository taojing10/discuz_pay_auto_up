<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$ident  = $plugin['identifier'];
$pmod	= getgpc('pmod','g');
$url	= 'action=plugins&operation=config&do='.$plugin['pluginid'].'&identifier='. $ident.'&pmod='.$pmod;
$mpurl  = 'admin.php?'.$url;

//
$pvars = $_G['cache']['plugin'][$ident];
$table = '#'.$ident.'#intcdz_thread_ding';

if( $_GET['act'] == 'dtdel' ){
	if(trim($_GET['formhash']) != FORMHASH) dexit();

	$dtid = $_GET['dt'];
	if( $dtid < 1 ) dexit();

	C::t($table)->delete( );

}else{
	//
	$page  = $_GET['page'] ? intval( $_GET['page'] ) : 1;
	$limit = 20;
	$start = ($page - 1) * $limit;

	//
	$username = trim( daddslashes($_POST['username']) );

	$dtList	= C::t($table)->fetch_all_by_username( $username, $start , $limit , 'desc' );
	foreach($dtList as $k=>$dt){
		$thread = C::t('forum_thread')->fetch( $dt['tid'] );
		$dtList[$k]['subject'] = $thread['subject'];
	}

	//
	$count  = C::t($table)->count_by_username( $username );
	$multipage = multi($count, $limit, $page, $mpurl);

	include template($ident.':admin_dings');
}
?>
