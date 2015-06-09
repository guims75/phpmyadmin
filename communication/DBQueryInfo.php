<?php

class DBQueryInfo
{
  static function is($query, $firstWord)
  {
    return self::_is($query, strtoupper($firstWord));
  }

  private static function _is($query, $firstWord)
  {
    $query = ltrim($query);
    $pos = strpos(' ', $query);
    $pos2 = strpos("\t", $query);
    if ($pos2 !== false)
      $pos = $pos === false ? $pos2 : min($pos, $pos2);
    if ($pos !== false)
      $query = substr($query, 0, $pos);
    return strtoupper($query) === $firstWord;
  }

  static function isDrop($query)
  {
    return self::_is($query, 'DROP');
  }

  static function isTruncate($query)
  {
    return self::_is($query, 'TRUNCATE');
  }

  static function isDelete($query)
  {
    return self::_is($query, 'DELETE');
  }
}