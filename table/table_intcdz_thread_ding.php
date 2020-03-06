<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_intcdz_thread_ding extends discuz_table
{
	public function __construct() {
		$this->_table = 'intcdz_thread_ding';
		$this->_pk    = 'did';

		parent::__construct();
	}

	public function fetch_by_tid($tid=0){
		if( $tid < 1 ) return;

		$sql = 'select * from %t where tid=%d order by %i';
		$arg = array(
				$this->_table,
				$tid,
				DB::order($this->_pk, 'desc')
		);
		return DB::fetch_first($sql , $arg);
	}

	public function fetch_all_by_fid_minute_expire($fid = 0 ,$minute = 0 ,$expire = 0, $start=0 ,$limit=0 ,$sort = 'desc'){
		$sql = 'select * from %t where %i and %i and %i order by %i %i';
		$arg = array(
				$this->_table,
				$fid ? DB::field('fid', $fid) : 1,
				$minute ? DB::field('minute', $minute) : 1,
				$expire ? DB::field('expire', $expire, '>') : 1,
				DB::order($this->_pk, $sort),
				DB::limit($start, $limit)
		);
		return DB::fetch_all($sql , $arg , $this->_pk);
	}

	public function fetch_all_by_username($username='' , $star=0 , $limit=0 , $sort = 'desc'){
		$sql = 'select * from %t where %i order by %i %i';
		$arg = array(
				$this->_table,
				$username ? DB::field('username', $username) : 1,
				DB::order($this->_pk, $sort),
				DB::limit($start, $limit)
			);
		return DB::fetch_all($sql , $arg , $this->_pk);
	}
	public function count_by_username($username=''){
		$sql = 'select count(*) from %t where %i';
		$arg = array(
				$this->_table,
				$username ? DB::field('username', $username) : 1
		);
		return DB::result_first( $sql , $arg );
	}

}


?>
