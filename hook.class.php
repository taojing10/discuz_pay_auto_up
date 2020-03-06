<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_zd_ding {
	protected $_pvars = array();
	protected $_ident = 'zd_ding';

	private $_cache_name = 'thread_ding_time';

	public function __construct(){
		global $_G;
		$this->_pvars = $_G['cache']['plugin'][$this->_ident];
	}

	protected function _get_table($table){
		return '#'.$this->_ident.'#intcdz_'.$table;
	}

	protected function _write_cache( $data = 0 ){
		if( !function_exists('writetocache') ){
			include libfile('function/cache');
		}

		$cacheStr	= '$'. $this->_cache_name.' = \'' . $data ."';\r\n";
		writetocache('intcdz_' . $this->_cache_name, $cacheStr );

		unset( $data , $cacheStr );
	}

	protected function _read_cache( ){
		include_once ('./data/sysdata/cache_intcdz_'. $this->_cache_name.'.php');
		$varname = $this->_cache_name;
		return $$varname;
	}
}

class plugin_zd_ding_forum extends plugin_zd_ding{

	function viewthread_title_extra() {
		global $_G;

		if($_G['thread']['authorid'] == $_G['uid']){
			$log = C::t( $this->_get_table('thread_ding') )->fetch_by_tid( $_G['tid'] );
			if($log['expire'] > $_G['timestamp']){
				$str = '<span class="pipe">|</span>自动顶帖'.$log['number'].'小时；到期时间'.dgmdate($log['expire'],'n月d日 H:i' );

			}else{
				$str  = '<link rel="stylesheet" type="text/css" href="./source/plugin/'.$this->_ident.'/template/style.css">';
				$str .= '<a href="javascript:;" class="redbtn ml10 a_to_but" onclick="showWindow(\'dtWin\',\'plugin.php?id='.$this->_ident.':ajax&formhash='.FORMHASH.'&thread='.$_G['thread']['tid'] .'\')">自动顶贴</a>';
			}

			return $str;
		}
	}

	function forumdisplay_top(){
		global $_G;
		$minute = date('i' , $_G['timestamp']);
		$minute_cache = $this->_read_cache();


		if( $minute == $minute_cache ){
			return ;
		}


		$fid = $_G['fid'];

		$logs = C::t($this->_get_table('thread_ding'))->fetch_all_by_fid_minute_expire($fid,0,$_G['timestamp']);

		foreach($logs as $log){

			if( ($_G['timestamp'] - $log['dateline']) < 60 ){
				return;
			}


			$time = dgmdate($_G['timestamp'], 'Y-m-d H' ).':'. $log['minute'] .':' . dgmdate($_G['timestamp'], 's' );
			$time = strtotime( $time );


			if( $log['minute'] > $minute ){
				$time = $time - 3600;
			}


			$data = array(
					'lastpost' => $time + rand(-10, 10)
			);

			C::t('forum_thread')->update( $log['tid'] , $data );
		}

		$this->_write_cache( $minute );
	}

}

?>
