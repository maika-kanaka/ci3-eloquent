# CI3-ELOQUENT

A query builder++ on codeigniter3 for easier your life.

## HOW TO USE

- Step 1: Run this command in your codeigniter project

```
composer require maika-kanaka/ci3-eloquent
```

- Step 2: Extend your model & define your table name in property

```
<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Eloquent\Eloquent;

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

| Param | Type | Required | Description |
| --- | --- | --- | --- |
| alias | String | No | Given alias to your table, it's usefull when you're using join statement |

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

- find(where)

| Param | Type | Required | Description |
| --- | --- | --- | --- |
| where | Array or Integer or String | No | Get the first row with where clause |

~ Example 1: get the first row with primary key

```
$data = $this->My_model_name->find('INV-202010-0001');
```

~ Example 1: get the first row with other column

```
$data = $this->My_model_name->find(['status => 'active', 'is_stok' => 'Y']);
```

- find_or_404(where)

This method is exactly same as the find() method but if the return value is null then it will appear 404 page

- insert(data)

| Param | Type | Required | Description |
| --- | --- | --- | --- |
| data | Array | Yes | Data to insert |

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

| Param | Type | Required | Description |
| --- | --- | --- | --- |
| where | Array | Yes | Specific data to be deleted |

```
$this->My_model_name->delete(['my_primary_key' => 'value']);
```

- primaryKeyInc()

If you're using this method. You must define your field primary key on property model

```
<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Eloquent\Eloquent;

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

- primaryKey([opts])

Firstly, The same as the primaryKeyInc() method, you must define your field primary key on property model 

| Param | Type | Required | Description |
| --- | --- | --- | --- |
| format | String | Yes | Prefix for generate code, take it for example: If you want the return value is INV/202010/0001 so the format is **INV** |
| separator | String | No | Separator for generate code, take it for example: If you want the return value is INV-202010-0001 so the separator is **-** |
| digit_inc | Integer | No | Digit increment, Default value: 4
| reset_monthly | Boolean | No | Fill yearmonth in return value & reset the increment every month changed. Default value: TRUE |

~ Example 1: Generate code & reset increment every month

```
  $pk = $this->My_model_name->primaryKey([
    "format" => "INV", 
    "separator" => "-",
    "digit_inc" => 5
  ]);

  # return value is: INV-202010-00001
```

~ Example 2: Generate code & non reset increment

```
  $pk = $this->My_model_name->primaryKey([
    "format" => "KTG", 
    "separator" => "",
    "digit_inc" => 6
  ]);

  # return value is: KTG000001
```

## LICENSE 

MIT
