
WHAT THIS MODULE IS GOOD FOR
----------------------

This module can basically be useful in 2 ways:

1. For making your users passwords readable by admins.
2. As a very simple general purpose AES encryption API to use in other modules.

Note: While this module does AES encryption, it does NOT do integrity validation
with an HMAC. This means you can safely store or send encrypted data, but if you
provide a public end-point which can receive encrypted data and presents an
error message if it's not correctly padded, then it will be vulnerable to a
"Padding oracle attack":

  https://en.wikipedia.org/wiki/Padding_oracle_attack

REQUIREMENTS
----------------------

This module requires at least PHP 5.2 and the PHP Secure Communications Library
(phpseclib).

Just download the latest 1.x version from https://github.com/phpseclib/phpseclib
and extract it into a directory called "phpseclib" inside the aes directory.
Note that the zip file of the version of phpseclib that this module was
developed with doesn't create the phpseclib directory itself, it just extracts
its various directories directly into the location you unzip it, so create that
"phpseclib" directory first and then move the zip file into it, and unzip. The
complete path to the file which will be included by this module (AES.php) should
look like this:

  aes/phpseclib/phpseclib/Crypt/AES.php

That's it! Try installing/enabling the module again.

This module was developed using phpseclib version 1.5, but hopefully future
versions should work as well (and might contain security bug fixes, so always
get the latest). If you've got a version of phpseclib that's newer than 1.5 and
you're running into trouble, then please create an issue at
drupal.org/project/aes

For improved performance, install the mcrypt extension: http://php.net/mcrypt

ABOUT KEY STORAGE METHODS
----------------------

Something you should pay attention to (if you want any sort of security) is how
you store your encryption key. You have the option of storing it in the database
as a normal Drupal variable, this is also the default, but it's the default only
because there is no good standard location to store it. Switching to a
file-based storage is strongly encouraged since storing the key in the same
database as your encrypted strings will sort of nullify the point of them being
encrypted in the first place. Also make sure to set the permission on the
keyfile to be as restrictive as possible, assuming you're on a unix-like system
running apache, I recommend setting the ownership of the file to apache with the
owner being the only one allowed to read and write to it (0600). Above all make
sure that the file is not readable from the web! The easiest way to do that is
probably to place it somewhere below the webroot.

