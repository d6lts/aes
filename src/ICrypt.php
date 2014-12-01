<?php
/**
 * Created by PhpStorm.
 * User: dennis
 * Date: 2/11/14
 * Time: 12:05 PM
 */


namespace Drupal\aes;

/**
 * Interface ICrypt
 * @package Drupal\aes\Plugin
 * @todo: describe
 */
interface ICrypt {

  public function encrypt($string);

  public function decrypt($string);
}
