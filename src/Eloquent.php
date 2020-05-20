<?php namespace Eloquent;

class Eloquent extends \CI_Model 
{

    // field name for primary key 
    public $primary_key;

    // config for multiple connection database
    public $db_using = 'default';

    public function __construct()
    {
        parent::__construct();
    }

    public function table($as = '')
    {
      $table  = $this->table;
      $table .= !empty($as) ? " $as " : "";
      return $this->_getdb()->from($table);
    }

    public function insert($input)
    {
      if( isset($input[0]) && is_array($input[0]) )
      {
        // bulk insert
        return $this->_getdb()->insert_batch($this->table, $input);
      }
      else
      {
        // insert
        return $this->_getdb()->insert($this->table, $input);
      }
    }

    public function detail($where)
    {
      $detail = $this->table()->where($where)->get()->row();
      return $detail;
    }

    public function primaryKeyInc($primary_key ='')
    {
      $data = $this->table()
                      ->order_by($primary_key, 'desc')
                      ->get()
                      ->row();

      if(empty($data)){
        return 1;
      }

      return $data->$primary_key + 1;
    }

    private function _getdb()
    {
      if($this->db_using == 'default'){
        return $this->db;
      }else{
        $this->$this->db_using = $this->load->database($this->db_using, TRUE);
      }
    }

}