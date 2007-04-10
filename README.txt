; $Id $

WHAT THIS MODULE IS GOOD FOR
----------------------
This module can basically be useful in 2 ways:
1. For making your users passwords viewable by admins.
2. As a very simple general purpose AES encryption system to use in other modules.

ABOUT KEY STORAGE METHODS
----------------------
Something you should pay attention to (if you want any sort of security) is how you store your encryption key. You have the option of storing it in the database as a normal Drupal variable, this is also the default, but it's the default only because there is no good standard location to store it. Switching to a file-based storage is strongly encouraged since storing the key in the same database as your encrypted strings will sort of nullify the point of them being encrypted in the first place. Also make sure to set the permission on the keyfile to be as restrictive as possible, assuming you're on a unix-like system running apache, I recommend setting the ownership of the file to apache with the owner being the only one allowed to read and write to it (0600). Naturally this isn't ideal either, but I haven't been able to figure out a more secure way for now. If you got any ideas, please let me know.
