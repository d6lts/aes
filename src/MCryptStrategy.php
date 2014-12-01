<?php
/**
 * Created by PhpStorm.
 * User: dennis
 * Date: 2/11/14
 * Time: 12:52 PM
 */

namespace Drupal\aes;

use Drupal\Core\Config\Config;
use SebastianBergmann\Exporter\Exception;

/**
 * Class MCryptStrategy
 * possible custom parameters: key, iv, cipher
 *
 */
class MCryptStrategy extends ACrypt {
  protected $_cipher;
  protected $_iv;

  public function __construct(Config $config){
    parent::__construct($config);

    $this->_cipher = $this->_config->get('cipher');
    $this->_mcrypt_iv = $this->_config->get('mcrypt_iv');
  }

  /**
   * Encrypts a string.
   *
   * @param string $string
   *   The string to encrypt.
   *
   * @return bool|string
   *   The encrypted string on success, false on error.
   */
  public function encrypt($string) {

    if (empty($string)) {
      \Drupal::logger('aes')->warning("Tried to encrypt an empty string.");
      return false;
    }

    $cipher = isset($this->_customData['cipher'])
      ? $this->_customData['cipher']
      : $this->_cipher;

    $iv = isset($this->_customData['iv'])
      ? $this->_customData['iv']
      : $this->_iv;
    $iv = base64_decode($iv);
    // @todo: call private to aes_make_iv() if empty
    /*if (empty($iv)) {
      aes_make_iv();
      $iv = base64_decode($config->get("mcrypt_iv"));
      \Drupal::logger('aes')->warning("No initialization vector found while trying to encrypt! This could be a bit of a pain since you might have to reset all the passwords for all users. I've created a new one now and will try to carry on as normal.");
    }*/

    $key = isset($this->_customData['key'])
      ? $this->_customData['key']
      : $this->_key;

    $base64 = isset($this->_customData['base64'])
      ? $this->_customData['base64']
      : FALSE;

    try {
      $td = mcrypt_module_open($cipher, "", MCRYPT_MODE_CBC, "");
      $ks = mcrypt_enc_get_key_size($td);
      $key = substr(sha1($key), 0, $ks);
      mcrypt_generic_init($td, $key, $iv);
      $encrypted = mcrypt_generic($td, $string);
      mcrypt_generic_deinit($td);
      mcrypt_module_close($td);

      return $base64 ? base64_encode($encrypted) : $encrypted;

    }
    catch(Exception $e) {
      \Drupal::logger('aes')->error("Exception: " . $e->getMessage());
      return FALSE;
    }
  }

  public function decrypt($string) {

  }
} 