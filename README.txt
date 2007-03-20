WHAT THIS MODULE DOES
----------------------
This module can replace Drupals standard system of storing passwords as MD5-hashes with a system using AES/Rijndael encryption. The benefit of this being that passwords can be decrypted and viewed by roles with the privilege to do so.

THINGS YOU SHOULD KNOW
----------------------
This module can't completely replace Drupals system of using MD5-hashes since Drupal is basically hard-coded to do so. It can't instantly convert the system to AES either since the only one who knows a users password in an MD5-system is the user.

So what does this mean? It means that this module will progressively convert your system over time as each user logs in. Until every user has logged in, you'll have a mixed MD5 and AES system. This is ok though, both will work at the same time. New users and users being given new passwords will also receive AES-passwords.

Another thing you should pay attention to (if you want any sort of security) is how you store your encryption key. You have the option of storing it in the database as a normal Drupal variable, this is also the default, but it's the default only because there is no good standard location to store it. Switching to a file-based storage is strongly encouraged since storing the key in the same database as your encrypted strings will sort of nullify the point of them being encrypted in the first place. Also make sure to set the permission on the keyfile to be as restrictive as possible, assuming you're on a unix-like system running apache, I recommend setting the ownership of the file to apache with the owner being the only one allowed to read and write to it (0600). Naturally this isn't ideal either, but I haven't been able to figure out a more secure way for now. If you got any ideas, please let me know.

And finally, if you're ever going to transfer all your users to another system also using this module, or load a backup of your database. Make sure you still have these 2 things: the key, and the initialization vector. Without both of those things, you can't decrypt the passwords. You can find the initialization vector in Drupals variables table.

