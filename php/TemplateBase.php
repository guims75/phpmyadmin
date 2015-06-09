<?php

class TemplateBase
{
  private $_method;
  private $_values;
  private $_text_submit;
  private $_has_reset;

  public $error = '';

  function __construct($method = 'POST', $text_submit = null,
		       $has_reset = true)
  {
    if (strtoupper($method) === 'GET')
      {
	$this->_values = &$_GET;
	$this->_method = 'GET';
      }
    else
      {
	$this->_values = &$_POST;
	$this->_method = 'POST';
      }
    $this->_text_submit = $text_submit;
    $this->_has_reset = $has_reset;
  }

  function __get($value)
  {
    return isset($this->_values[$value]) ? $this->_values[$value] : '';
  }

  function isExist($field)
  {
    return isset($this->_values[$field]);
  }

  function getField($value)
  {
    return $this->_values[$value];
  }

  function getMethod()
  {
    return $this->_method;
  }

  function isSubmit($tab_field = array())
  {
    if (!isset($this->_values['submit']))
      return false;
    foreach($tab_field as $field_name)
      {
	if (!isset($this->_values[$field_name]))
	  return false;
      }
    return true;
  }

  function showError()
  {
    echo '<p>'.$this->error.'</p>';
  }

  function showSubmit()
  {
    echo '<p><input type="submit" name="submit" '
      .($this->_text_submit ? 'value="'.$this->_text_submit.'"' : '').' />'
      .($this->_has_reset ? '<input type="reset" />' : '').'</p>';
  }

  static function displayField($name, $type, $value = null, $text = null)
  {
    echo '<p>';
    if ($text)
      self::displayLabel($name, $text);
    self::displayInput($name, $type, $value);
    echo '</p>';
  }

  static function displayLabel($name, $text)
  {
    echo '<label for="'.$name.'">'.$text.'</label>';
  }

  static function displayInput($name, $type, $value)
  {
    echo '<input type="'.$type.'" name="'.$name.'" id="'.$name.'"';
    if ($value)
      echo ' value="'.htmlspecialchars($value, ENT_COMPAT, 'UTF-8').'"';
    echo ' />';
  }

  function showField($name, $type = 'text', $text = null)
  {
    self::displayField($name, $type, isset($this->_values[$name])
		       ? $this->_values[$name] : null, $text);
  }

  function showInput($name, $type = 'text')
  {
    self::displayInput($name, $type, isset($this->_values[$name])
		       ? $this->_values[$name] : null);
  }
}
