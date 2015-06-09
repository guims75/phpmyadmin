<?php

class DB
{
  /** @var string */
  private $dbname;
  /** @var MySQLi */
  private $sql;
  /** @var array {'string' => {DBTable}} */
  private $tables = array();

  /** @var DB */
  static private $instance = null;

  /**
   * @param string host
   * @param string username
   * @param string passwd
   * @param string dbname
   * @param int port
   * @param string socket
   */
  private function __construct($host, $username, $passwd, $dbname = '', $port = null, $socket = null)
  {
    $this->sql = new MySQLi($host, $username, $passwd, $dbname, $port, $socket);
    $this->dbname = $dbname;
  }

  final private function __clone()
  {}

  function getError()
  {
    return $this->sql->error;
  }

  function throwException($exceptionName = 'Exception', $previous = null)
  {
    throw new $exceptionName(
			     $this->sql->error,
			     $this->sql->errno,
			     $previous
			     );
  }

  static function getFactory($host = null, $username = null, $passwd = null, $dbname = '', $port = null, $socket = null)
  {
    if (!self::$instance)
      self::$instance = new self($host, $username, $passwd, $dbname, $port, $socket);
    return self::$instance;
  }

  public function query($query)
  {
    return $this->sql->query($query);
  }

  public function multiQuery($query)
  {
    return $this->sql->multi_query($query);
  }

  public function getMySQLi()
  {
    return $this->sql;
  }

  public function exec($query)
  {
    return $this->sql->real_query($query);
  }

  public function escape($value)
  {
    return $this->sql->real_escape_string($value);
  }

  protected function queryOnlyRow($query)
  {
    $ret = array();
    if (!($result = $this->sql->query($query)))
      $this->throwException();
    while ($row = $result->fetch_row())
      $ret[] = $row[0];
    return $ret;
  }

  /**
   * @return array {string}
   */
  public function getDBNames()
  {
    return $this->queryOnlyRow('SHOW DATABASES');
  }

  /**
   * @return string|null
   */
  function getDBName()
  {
    return $this->dbname;
  }

  /**
   * @return bool
   */
  function setDBName($dbname)
  {
    $this->dbname = $dbname;
    if (!isset($this->tables[$dbname]))
      $this->tables[$dbname] = array();
    return $this->sql->select_db($dbname);
  }

  /**
   * @return array {string}
   */
  function getTableNames()
  {
    return $this->queryOnlyRow('SHOW TABLES');
  }

  /**
   * @return bool
   */
  public function createBase($dbname)
  {
    return $this->sql->real_query("CREATE DATABASE `$dbname`");
  }

  /**
   * @return DBTable
   */
  protected function makeTable($name)
  {
    return new DBTable($name, $this);
  }

  /**
   * @param string $tablename
   * @param array $fiels
   * @return bool
   * @see DBTable::queryFieldsSpec
   */
  function createTable($tablename, array $fields)
  {
    return $this->sql->real_query("CREATE TABLE `$tablename`("
				  .(DBTable::queryFieldsSpec($fields)).")");
  }

  /**
   * @return bool
   */
  function deleteTable($name)
  {
    $ret = $this->sql->real_query('DROP TABLE '.$name);
    if ($ret && isset($this->tables[$this->dbname][$name]))
      unset($this->tables[$this->dbname][$name]);
    return $ret;
  }

  /**
   * @return bool
   */
  function deleteBase($dbname)
  {
    $ret = $this->sql->real_query('DROP DATABASE `'.$dbname.'`');
    if ($ret && $dbname === $this->dbname)
      $this->dbname = null;
    return $ret;
  }

  /**
   * @return bool
   */
  function freeTable($name)
  {
    return $this->sql->real_query("TRUNCATE TABLE `$name`");
  }

  /**
   * @return DBTable|null
   */
  function getTable($name)
  {
    if (!isset($this->tables[$this->dbname][$name]))
      return $this->tables[$this->dbname][$name] = $this->makeTable($name);
    return $this->tables[$this->dbname][$name];
  }

  /**
   * @return MySQLi_Result
   */
  function getStatus()
  {
    return $this->sql->query('SHOW TABLE STATUS FROM `'.$this->dbname.'`');
  }

  /**
   * @return string
   */
  static private function toStrFieldCreate(array $row)
  {
    return "\n  `"
      .$row['Field']
      .'` '
      .$row['Type']
      .($row['Null'] === 'NO' ? ' NOT NULL' : '')
      .self::toStrDefault($row)
      .($row['Extra'] ? ' '.$row['Extra'] : '');
  }

  /**
   * @return string
   */
  static private function toStrDefault(array $row)
  {
    if ($row['Null'] === 'NO' && $row['Default'] === null)
      return '';
    if ($row['Default'] === null)
      $default = 'NULL';
    else if (strpos($row['Type'], 'char') !== false ||
	     strpos($row['Type'], 'text') !== false ||
	     strpos($row['Type'], 'binary') !== false ||
	     strpos($row['Type'], 'blob') !== false ||
	     strpos($row['Type'], 'set') !== false ||
	     strpos($row['Type'], 'enum') !== false)
      $default = '\''.$row['Default'].'\'';
    else
      $default = $row['Default'];
    return (' DEFAULT '.$default);
  }

  static private function addIndexes(&$str, DBTable $table)
  {
    $indexes = $table->getInfoIndex();
    $unique_key = array();
    while ($row = $indexes->fetch_assoc())
      {
	if ($row['Key_name'] !== 'PRIMARY')
	  $unique_key[$row['Key_name']][] = $row['Column_name'];
      }
    if (!empty($unique_key))
      {
	foreach ($unique_key as $name => &$keys)
	  $keys = "`$name` (`".implode('`,`', $keys).'`)';
	$str .= ",\n  UNIQUE KEY ".implode(",\n  ", $unique_key);
      }
  }

  private function addEndQueryCreate(&$str)
  {
    $status = $this->getStatus();
    $row = $status->fetch_assoc();
    $charset = substr($row['Collation'], 0, strpos($row['Collation'], '_'));
    $str .= "\n) ENGINE={$row['Engine']} DEFAULT CHARSET=$charset ";
    $str .=  "COLLATE={$row['Collation']} AUTO_INCREMENT={$row['Auto_increment']}\n;\n";
  }

  static private function addQueryInsert(&$str, DBTable $table, array $fields)
  {
    $query = '';
    $result = $table->selectAll();
    while ($row = $result->fetch_row())
      {
	$query .= ($query ? ",\n" : "\n")
	  .'(\''.implode('\', \'', $row).'\')';
      }
    if ($query)
      {
	$str .= 'INSERT INTO '.$table
	  .' (`'.implode('`, `', $fields).'`) VALUES'.$query.";\n";
      }
  }

  /**
   * @param string format ce parametre n'est pas pris en compte
   * @return string
   */
  function import($format = 'sql')
  {
    $ret = '';
    foreach ($this->getTableNames() as $name)
      {
	$table = $this->getTable($name);
	$ret .= 'CREATE TABLE IF NOT EXISTS '.$table." (";
	$struct = $table->getStructure();
	$empty = true;
	$primary_key = null;
	$fields = array();
	while ($row = $struct->fetch_assoc())
	  {
	    $fields[] = $row['Field'];
	    if ($row['Key'] === 'PRI')
	      $primary_key = $row['Field'];
	    $ret .= ($empty ? '' : ',').self::toStrFieldCreate($row);
	    if ($empty)
	      $empty = false;
	  }
	if ($primary_key)
	  $ret .= ",\n  PRIMARY KEY ($primary_key)";
	self::addIndexes($ret, $table);
	$this->addEndQueryCreate($ret);
	self::addQueryInsert($ret, $table, $fields);
      }
    return $ret;
  }
}