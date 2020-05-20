# CI3-ELOQUENT

A simple eloquent for easier your life.

## HOW TO USE

- Step 1: Run this command in your codeigniter project

```
composer require maika-kanaka/ci3-eloquent
```

- Step 2: Extend your model & define your table name in property

```
class My_model_name extends Eloquent
{

  public $table = 'my_table_name';

  public function __construct()
  {
    parent::__construct();
  }

}
```

### METHOD

- table(alias)

Param | Type | Required | Description
alias | String | No | Given alias to your table, it's usefull when you're using join statement

Example 1: a simple query to get data

```
$data = $this->My_model_name->table()->get()->result();
```

Example 2: a simple query with join statement to get data

```
$data = $this->My_model_name->table('t1')
              ->join('my_other_table_name AS t2', 't1.id_fk = t2.id_fk')
              ->get()->result();
```

- insert(data)

Param | Type | Required | Description
data | Array | Yes | Data to insert

~ Example 1: Insert one row

```
$this->My_model_name->insert([
  'field_name_1' => 'test1', 
  'field_name_2' => 'Testing1'
]);
```

~ Example 2: Insert multiple row bulk

```
$insert = [];
$insert[] = ['field_name_1' => 'test1', 'field_name_2' => 'Testing1'];
$insert[] = ['field_name_1' => 'test2', 'field_name_2' => 'Testing2'];
$this->My_model_name->insert($insert);
```

- delete(where)

Param | Type | Required | Description
where | Array | Yes | Specific data to be deleted

```
$this->My_model_name->delete(['my_primary_key' => 'value']);
```

- primaryKeyInc()

If you're using this method. You must define your field primary key on property model

```
class My_model_name extends Eloquent
{

  public $table = 'my_table_name';
  public $primary_key = 'my_field_name_id';

  public function __construct()
  {
    parent::__construct();
  }

}
```

Then, you can using this method & get the increment

```
$increment = $this->My_model_name->primaryKeyInc();
```


### TODO LIST

- Adding primary key by format PREFIX(separator)YEARMONTH(separator)INCREMENT
  Example: INV-202001-0001

## LICENSE 

MIT
