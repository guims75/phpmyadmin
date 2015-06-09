<?php

class DBTable
{
  /** @var array */
  //private $structure = null;
  /** @var DB */
  private $db;
  /** @var string */
  private $table_name;

  function __construct($table_name, DB $db)
  {
    $this->table_name = '`'.$table_name.'`';
    $this->db = $db;
  }

  function getName()
  {
    return $this->table_name;
  }

  function getNotProtectName()
  {
    return substr($this->table_name, 1, -1);
  }

  function __toString()
  {
    return $this->table_name;
  }

  /**
   * @return array|null
   */
  function getStatus()
  {
    if ($result = $this->db->query('SHOW TABLE STATUS FROM '
				   .$this->db->getDBName().' WHERE Name="'
				   .$this->getNotProtectName().'"'))
      return $result->fetch_assoc();
    return null;
  }

  /**
   * @return MySQLi_Result
   */
  function getStructure()
  {
    return $this->db->query('DESC '.$this->table_name);
  }

  /**
   * @return MySQLi_Result
   */
  function getInfoIndex()
  {
    return $this->db->query('SHOW INDEX FROM '.$this->table_name);
  }

  /**
   * @return string
   */
  private function createSetValue(array $col, $separator)
  {
    $set = '';
    foreach($col as $name => $value) {
      if ($set)
	$set .= $separator;
      $set .= '`'.$name.'`="'.$this->db->escape($value).'"';
    }
    return $set;
  }

  /**
   * @return string
   */
  private function createWhereDelete(array $col)
  {
    return $this->createSetValue($col, ' AND ');
  }

  /**
   * @return int|false
   */
  private function _delete($where)
  {
    ///TODO retourne ?
    return $this->db->exec(
			   "DELETE\n FROM {$this->table_name}"
			   .($where ? "\n WEHRE $where" : ''))
      ? $this->db->mydbi_field_count() : false;
  }

  function deleteEntry(array $col)
  {
    return $this->_delete($this->createWhereDelete($col));
  }

  function deleteEntries(array $cols)
  {
    $where = '';
    foreach($cols as $col) {
      if ($where)
	$where .= "\nOR ";
      $where .= $this->createWhereDelete($col);
    }
    return $this->_delete($where);
  }

  /**
   * @return MySQLi_Result
   */
  function select($limit_begin = 0, $limit_len = 30)
  {
    return $this->db->query(
			    "SELECT *\nFROM {$this->table_name}\nLIMIT $limit_begin, $limit_len"
			    );
  }

  /**
   * @param array $def : {'type' => string[, 'null' => bool][, 'default' => string][, 'after' => string][, 'extra' =>string]}
   * @return string
   */
  static function queryFieldSpec($name, array $def)
  {
    $query = "$name {$def['type']}";
    if (!isset($def['null']) || !$def['null'])
      $query .= ' NOT NULL';
    if (isset($def['default']))
      $query .= ' DEFAULT '.$def['default'];
    if (isset($def['after']))
      $query .= ' AFTER `'.$def['after'].'`';
    if (isset($def['extra']))
      $query .= ' '.$def['extra'];
    return $query;
  }

  /**
   * @param array $fields : {'name_field' => $def}
   * @return string
   * @see DBTable::queryFieldSpec
   */
  static function queryFieldsSpec(array $fields)
  {
    $query = '';
    foreach ($fields as $name => $def)
      {
	if ($query)
	  $query .= ',';
	$query .= self::queryFieldSpec($name, $def);
      }
    return $query;
  }

  /**
   * @return bool
   * @see DBTable::queryFieldSpec
   */
  function createField($name, array $def)
  {
    return $this->db->exec("ALTER TABLE {$this->table_name} ADD "
			   .self::queryFieldSpec($name, $def));
  }

  /**
   * @return bool
   * @see DBTable::queryFieldSpec
   */
  function createFields(array $fields)
  {
    $query = '';
    foreach ($fields as $name => $def)
      {
	if ($query)
	  $query .= ',';
	$query .= self::queryFieldSpec($name, $def);
      }
    return $this->db->exec("ALTER TABLE {$this->table_name} ADD "
			   .self::queryFieldsSpec($fields));
  }

  /**
   * @return bool
   * @see DBTable::queryFieldSpec
   */
  function changeField($oldname, $newname, array $fields)
  {
    return $this->db->exec("ALTER TABLE {$this->table_name} CHANGE $oldname "
			   .self::queryFieldSpec($newname, $fields));
  }

  /**
   * @return bool
   */
  function deleteField($name)
  {
    return $this->db->exec("ALTER TABLE {$this->table_name} DROP `$name`");
  }

  /**
   * @return MySQLi_Result
   */
  function selectAll()
  {
    return $this->db->query("SELECT * FROM {$this->table_name}");
  }

  /**
   * @return MySQLi_Result
   */
  function update(array $values, array $update)
  {
    $where = $this->createWhereDelete($update);
    return $this->db->query(
			    'UPDATE '.$this->table_name
			    .' SET '.$this->createSetValue($values, ',')
			    .($where ? ' WHERE '.$where : '')
			    .' LIMIT 1'
			    );
  }


  /**
   * @return bool
   */
  function insert(array $values)
  {
    $query = '';
    foreach ($values as $value)
      {
	if ($query)
	  $query .= ',';
	$query .= '"'.$this->db->escape($value).'"';
      }
    return $this->db->exec('INSERT INTO '
			   .$this->table_name.'(`'
			   .implode('`,`', array_keys($values))
			   .'`) VALUES ('.$query.')'
			   );
  }

  /**
   * @return bool
   */
  function delete()
  {
    return $this->db->deleteTable($this->table_name);
  }

  /**
   * @return DB
   */
  function getDB()
  {
    return $this->db;
  }
}