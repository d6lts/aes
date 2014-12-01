<?php
/**
 * Created by PhpStorm.
 * User: dennis
 * Date: 2/11/14
 * Time: 12:03 PM
 */

namespace Drupal\aes;

use Drupal\Core\Config\ConfigFactoryInterface;
use SebastianBergmann\Exporter\Exception;


use \Doctrine\Common\Util\Debug;

class AesCryptManager implements ICrypt{

  /**
   * The contact settings config object.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  protected $implementation;

  /**
   * @var $strategy ICrypt
   */
  protected $strategy;

  /**
   * Constructs a ContactPageAccess instance.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config) {
    $this->config = $config->get('aes.settings');
    $this->implementation = $this->config->get('implementation');
    //Debug::dump($this->implementation);
    $strategies = $this->getStrategies();
    $class_name = $strategies[$this->implementation];
    if (!class_exists($class_name)) {
      // @todo: log to watchdog
      throw new Exception("No class found " . $class_name . " for implementation " . $this->implementation);
    }
    $this->strategy = new $class_name($this->config);
    //  Debug::dump($this->strategy);

    // @todo: process aes_get_key()
  }

  public function __set($name, $value){
    $this->strategy->$name = $value;
  }

  public function getPlugins() {
    return [
      'mcrypt' => 'mCrypt',
      'phpseclib' => 'PHPSecLib',
    ];
  }

  public function encrypt($string) {
    return $this->strategy->encrypt($string);
  }

  public function decrypt($string) {
    return $this->strategy->decrypt($string);
  }

  private function getStrategies() {
    return [
      'mcrypt' => 'Drupal\aes\MCryptStrategy',
      'phpseclib' => 'PhpSecLibStrategy',
    ];
  }

} 