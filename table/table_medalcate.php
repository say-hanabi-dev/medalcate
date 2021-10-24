<?
if(!defined('IN_DISCUZ')){
	exit('Access Denied');
}

class table_medalcate extends discuz_table{
	public function __construct(){
		$this->_table = 'medalcate';
		$this->_pk = '';
	}
	
	public function fetch_all(){
		return DB::fetch_all("SELECT * FROM %t ORDER BY displayorder", array($this->_table));
	}
	
	public function fetch_by_cateid($cateid){
		return DB::fetch_all("SELECT * FROM %t WHERE cateid=%d", array($this->_table, $cateid));
	}

	public function delete_by_cateid($cateid){
		return DB::query("DELETE FROM %t WHERE cateid=%d", array($this->_table, $cateid));
	}
	
	public function update_by_cateid($cateid, $catename, $medalid, $displayorder){
		return DB::query("UPDATE %t SET catename=%s, medalid=%s, displayorder=%d WHERE cateid=%d", array($this->_table, $catename, $medalid, $displayorder, $cateid));
	}

	public function insert_by_catename($catename){
		return DB::query("INSERT INTO %t (catename, medalid) VALUES (%s, %s)", array($this->_table, $catename, ''));
	}
}