<?php
/**
 * Created by PhpStorm.
 * User: dennis
 * Date: 2/11/14
 * Time: 4:58 PM
 */

namespace Drupal\aes;

use Drupal\Core\Config\Config;

abstract class ACrypt implements ICrypt {

  protected $_config;

  protected $_key;

  protected $_customData = [];

  public function __construct(Config $config){
    $this->_config = $config;
    $this->_key = $this->_config->get('key');
  }

  public function encrypt($string){

  }

  public function decrypt($string){

  }

  public function __set($name, $value){
    $this->_customData[$name] = $value;
  }

} 