<?php namespace Eloquent;

class Eloquent extends \CI_Model
{

    // required property: table name
    public $table;

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
        if (isset($input[0]) && is_array($input[0])) {
            // bulk insert
            return $this->_getdb()->insert_batch($this->table, $input);
        } else {
            // insert
            return $this->_getdb()->insert($this->table, $input);
        }
    }

    public function find($val_or_where)
    {
        if( !is_array($val_or_where) ){
            $where = [$this->primary_key => $val_or_where];
        }
        else{
            $where = $val_or_where;
        }

        $detail = $this->table()->where($where)->get()->row();
        return $detail;
    }

    public function find_or_404($val_or_where)
    {
      $detail = $this->find($val_or_where);

      if(empty($detail)){
        show_404();
        exit;
      }else{
        return $detail;
      }
    }

    public function update($where, $data)
    {
        $this->_getdb()->where($where)
                     ->update($this->table, $data);
    }

    public function updateIncrement($where, $column)
    {
        return $this->_getdb()->where($where)
                    ->set($column, "$column + 1", FALSE)
                    ->update($this->table);
    }

    public function updateDecrement($where, $column)
    {
        return $this->_getdb()->where($where)
                    ->set($column, "$column - 1", FALSE)
                    ->update($this->table);
    }

    public function primaryKeyInc()
    {
        $data = $this->table()
                      ->order_by($this->primary_key, 'desc')
                      ->get()
                      ->row();

        if (empty($data)) {
            return 1;
        }

        return $data->$this->primary_key + 1;
    }

    /**
     * * Return example 1: INV/202012/0001
     * * Return example 2: KTG-00001
     */
    public function primaryKey($param = array())
    {
        // param
        if (empty($param['format'])) return false;
        if (empty($this->primary_key)) return false;
        if (!isset($param['separator'])) $param['separator'] = "/";
        if (empty($param['digit_inc'])) $param['digit_inc'] = 4;
        $param['digit_inc']--;
        if (!isset($param['reset_monthly'])) $param['reset_monthly'] = TRUE;

        // buat format
        $format = $param['format'] . $param['separator'];

        if( $param['reset_monthly'] ){
            $thn = date("Y");
            $bln = date("m");
            $format = $format . $thn.$bln . $param['separator'];
        }

        // ambil no urut terakhir
        $data_last = $this->_getdb()->from($this->table)
                            ->select($this->primary_key);

        if( $param['reset_monthly'] )
        {
            $data_last->like($this->primary_key, $format, "AFTER");
        }

        $data_last = $data_last->order_by($this->primary_key, "DESC")
                            ->get()
                            ->row();

        // jika yg pertama
        if (empty($data_last)) {
            return $format . str_repeat("0", $param['digit_inc']) . "1";
        }

        // Ambil string field name
        $field_primary_key = $this->primary_key;

        if( $param['reset_monthly'] )
        {
            // JIKA BULAN & TAHUN SUDAH BERGANTI MAKA ALANGKAH BAIK NYA KITA MULAI DARI AWAL LAGI, MELUNCUR ...
            $month = trim($data_last->$field_primary_key);
            if (substr($month, 0, strlen($format)) !== $format) {
                return $format . str_repeat("0", $param['digit_inc']) . "1";
            }
        }

        // JIKA BULAN & TAHUN BELUM BERGANTI MAKA BUAT INCREMENT ...
        $increment = (int) substr(
            trim($data_last->$field_primary_key), 
            strlen($format),
            ($param['digit_inc'] + 1)
        );
        $increment++;

        # persatukan increment dan format dengan pendekatan humanis
        $primaryKey = $format . str_repeat("0", (($param['digit_inc'] + 1) - strlen($increment)) ) . $increment;

        // MELUNCUR ..
        return $primaryKey;
    }

    private function _getdb()
    {
        if ($this->db_using == 'default') {
            return $this->db;
        } else {
            $this->$this->db_using = $this->load->database($this->db_using, true);
        }
    }
}
