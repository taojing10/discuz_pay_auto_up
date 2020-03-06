<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(trim($_GET['formhash']) != FORMHASH){ exit; }

$ident = 'zd_ding';
$table = '#'.$ident.'#intcdz_thread_ding';
$act = trim($_GET['act']);
$act = $act ? $act : 'dt';
$pvars = $_G['cache']['plugin'][$ident];


if($act == 'dt'){
	if( !$_G['uid'] ) dexit();
	$tid = intval($_GET['thread']);
	if( $tid < 1 ) dexit();

	$thread = C::t('forum_thread')->fetch( $tid );
	if( empty($thread) ) dexit();

	$userCredit = getuserprofile('extcredits'.$pvars['extcredit']);
	$timelength_options = range(1,100);

	if( $_GET['dtgo'] == 1 ){
		$dtTime = intval( $_GET['dttime'] );
		$pay = $dtTime * $pvars['price'];

		if($dtTime < 1 || $dtTime > 100){
			jsalert('alert("ʱ��ѡ������޷��������");');
		}

		if($userCredit < $pay){
			jsalert('alert("���㣬�޷��������");');
		}


		$log = C::t($table)->fetch_by_tid( $tid );
		if($log['expire'] > $_G['timestamp']){
			jsalert('alert("�Ѿ����룬�޷��������");');
		}

		$data_T = array(
				'tid' => $tid,
				'fid' => $thread['fid'],
				'number' => $dtTime,
				'minute' => date('i' , $_G['timestamp']) ,
				'expire' => $_G['timestamp'] + (3600 * $dtTime) ,
				'uid' => $_G['uid'],
				'username' => $_G['username'],
				'extcredit' => $pvars['extcredit'],
				'pay' => $pay ,
				'dateline' => $_G['timestamp']
		);
		if( C::t($table)->insert( $data_T , true ) ){

			$data[$pvars['extcredit']] = $pay * -1;
			updatemembercount($_G['uid'], $data , true , '' , $tid , '' , '�Զ�����', $thread['subject'] );


			jsalert('alert("�����Զ������ɹ�");hideWindow("dtWin");window.location.reload();');
		}

	}else{
		if($tid){

			$creditName = $_G['setting']['extcredits'][ $pvars['extcredit'] ]['title'];

			include template('common/header_ajax');
			include template($ident.':ajax_dt');
			include template('common/footer_ajax');
		}
	}
}

function jsalert($string=''){
	if(!is_string($string)) return;

	$string = '<script type="text/javascript" reload="1">'.$string.'</script>';
	Ajax_e( $string );
}

function Ajax_e( $string='' ){
	if(!is_string($string)) return;

	include template('common/header_ajax');
	echo $string;
	include template('common/footer_ajax');
}
?>
