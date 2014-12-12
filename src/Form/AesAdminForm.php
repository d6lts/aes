<?php
/**
 * @file
 * Contains \Drupal\aes\Form\AesAdminForm.
 */

namespace Drupal\aes\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a fields form controller.
 */
class AesAdminForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'aes_admin';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // $config = $this->config('aes.settings')->getRawData();
    $fileStorage = \Drupal\Core\Config\FileStorageFactory::getActive();
    $config = $fileStorage->read('aes.settings');

    $phpsec_loaded = aes_load_phpsec(FALSE);

    $form = array();

    $form['aes'] = array(
      '#type' => 'fieldset',
      '#title' => t('AES settings'),
      '#collapsible' => FALSE,
    );

    $encryption_implementations = array();
    if ($phpsec_loaded) {
      $encryption_implementations['phpseclib'] = t('PHP Secure Communications Library (phpseclib)');
    }
    if (extension_loaded('mcrypt')) {
      $encryption_implementations['mcrypt'] = t('Mcrypt extension');
    }

    if (!empty($encryption_implementations['mcrypt']) && !empty($encryption_implementations['phpseclib'])) {
      $implementations_description = t('The Mcrypt implementation is the (only) implementation this module used until support for phpseclib was added. The Mcrypt implementation is faster than phpseclib and also lets you define the cipher to be used, other than that, the two implementations are equivalent.');
    }
    elseif (!empty($encryption_implementations['mcrypt']) && empty($encryption_implementations['phpseclib'])) {
      $implementations_description = t('The Mcrypt extension is the only installed implementation.') . $phpseclib_error_msg;
    }
    elseif (empty($encryption_implementations['mcrypt']) && !empty($encryption_implementations['phpseclib'])) {
      $implementations_description = t('PHP Secure Communications Library is the only installed implementation.');
    }

    if (empty($encryption_implementations)) {
      drupal_set_message(t('You do not have an AES implementation installed! For correct AES work you need an encryption library, like PhpSecLib or MCrypt. Consult REAMDE.txt for more details.'), 'error');
      return array();
    }

    $form['aes']['implementation'] = array(
      '#type' => 'select',
      '#title' => t('AES implementation'),
      '#options' => $encryption_implementations,
      '#default_value' => $config['implementation'],
      '#description' => $implementations_description,
    );

    if ($config['implementation'] == 'phpseclib') {
      $cipher_select_value = 'rijndael-128';
      $cipher_select_disabled = TRUE;
      $cipher_description = t('Cipher is locked to Rijndael 128 when using the phpseclib implementation.');
    }
    else {
      $cipher_select_value = $config['cipher'];
      $cipher_select_disabled = FALSE;
      $cipher_description = '';
    }

    $form['aes']['cipher'] = array(
      '#type' => 'select',
      '#title' => t('Cipher'),
      '#options' => array(
        'rijndael-128' => 'Rijndael 128',
        'rijndael-192' => 'Rijndael 192',
        'rijndael-256' => 'Rijndael 256',
      ),
      '#default_value' => $cipher_select_value,
      '#disabled' => $cipher_select_disabled,
      '#description' => $cipher_description,
    );

    $form['aes']['key'] = array(
      '#type' => 'textfield',
      '#title' => t('Key'),
      '#description' => t("The key for your encryption system. You normally don't need to worry about this since this module will generate a key for you if none is specified. However you have the option of using your own custom key here."),
      '#required' => TRUE,
      '#default_value' => $config['key'],
    );

    $form['aes']['key_confirm'] = array(
      '#type' => 'textfield',
      '#title' => t('Confirm key'),
      '#required' => TRUE,
      '#default_value' => $config['key'],
    );

    $form['aes']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('key') != $form_state->getValue('key_confirm')) {
      $form_state->setErrorByName('key', t("The encryption keys didn't match."));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::logger('aes')->notice('Saving config...');
    // $config = $this->config('aes.settings')->getRawData();
    $fileStorage = \Drupal\Core\Config\FileStorageFactory::getActive();
    $config = $fileStorage->read('aes.settings');

    // If the cipher has changed...
    $old_cipher = $config['cipher'];
    $new_cipher = $form_state->getValue('cipher');
    if ($form_state->getValue('cipher') != $config['cipher']) {
      $config['cipher'] = $form_state->getValue('cipher');
      \Drupal\Core\Config\FileStorageFactory::getActive()->write('aes.settings', $config);

      // Get the old iv.
      $old_iv = $config['mcrypt_iv'];
      // create a new iv to match the new cipher
      aes_make_iv();
      // get the new iv
      $config = $fileStorage->read('aes.settings');
      $new_iv = $config['mcrypt_iv'];
    }

    // If the key has changed...
    if ($form_state->getValue('key') != $config['key']) {
      $config['key'] = $form_state->getValue('key');
      \Drupal\Core\Config\FileStorageFactory::getActive()->write('aes.settings', $config);

      drupal_set_message(t('Key changed.'));
      // @todo: invoke hook?
    }

    // If the implementation has changed...
    if ($form_state->getValue('implementation') != $config['implementation']) {

      $config['implementation'] = $form_state->getValue('implementation');
      \Drupal\Core\Config\FileStorageFactory::getActive()->write('aes.settings', $config);

      if ($form_state->getValue('implementation') == 'phpseclib') {
        // If we have switched to phpseclib implementation, set the cipher to 128, since it's the only one phpseclib supports.
        $config['cipher'] = 'rijndael-128';
        \Drupal\Core\Config\FileStorageFactory::getActive()->write('aes.settings', $config);

        // Create a new IV, this IV won't actually be used by phpseclib, but it's needed if the implementation is switched back to mcrypt.
        aes_make_iv(TRUE);
      }
    }
  }
}
