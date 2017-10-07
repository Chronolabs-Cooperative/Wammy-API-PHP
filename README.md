# Chronolabs Cooperative ~ http://wammy.snails.email

## Wammy Anti: Terror/Spam Services REST API (Version 4.x.x)

### Author: Simon Antony Roberts <wishcraft@users.sourceforge.net>

This PHP library is for use in conjunction with spam-assassin and it training to ensure that the best detection of ham is possible, you don't always hear a good wrap for Spam Assassin but this is often by people that never do any training with it; SA has a powerful Artifical Intelligence that is multilingual and will be and can be used for example to train all the terrorism content as ham and the rest as spam to filter for ham when it exist over API across large expanses.

This is designed to be used with PHP7 + Ubuntu 17.04+

# Environmental Setup

You must install spam assassin to use this tool the follow command will do that in Ubuntu terminal

     $ sudo apt-get install spamassassin spamc imagemagick gocr php-curl -y

execute this and your environment requirements for this are installed.

# Chronological Tasks (Crons) / Schedule Tasks

There is a number of crons that need to be executed by placing the following lines in executing the following in Ubuntu or Debian: $ sudo crontab -e

    */5 * * * * /usr/bin/php -q /path/to/crons/training-forgot-images.php
    */5 * * * * /usr/bin/php -q /path/to/crons/training-forgot-textual.php
    */5 * * * * /usr/bin/php -q /path/to/crons/training-ham-images.php
    */5 * * * * /usr/bin/php -q /path/to/crons/training-ham-textual.php
    */5 * * * * /usr/bin/php -q /path/to/crons/training-spam-images.php
    */5 * * * * /usr/bin/php -q /path/to/crons/training-spam-textual.php
    */2 * * * * /usr/bin/php -q /path/to/crons/callback.php
    */3 * * * * /usr/bin/php -q /path/to/crons/callback.php

In the example above '/path/to' = API_ROOT_PATH!