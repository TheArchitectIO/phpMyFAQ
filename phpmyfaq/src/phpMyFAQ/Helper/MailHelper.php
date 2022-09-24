<?php

/**
 * This class is a helper class for sending mails.
 *
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 *
 * @package   phpMyFAQ
 * @author    Thorsten Rinne <thorsten@phpmyfaq.de>
 * @copyright 2020-2022 phpMyFAQ Team
 * @license   http://www.mozilla.org/MPL/2.0/ Mozilla Public License Version 2.0
 * @link      https://www.phpmyfaq.de
 * @since     2020-02-23
 */

namespace phpMyFAQ\Helper;

use phpMyFAQ\Configuration;
use phpMyFAQ\Core\Exception;
use phpMyFAQ\Mail;
use phpMyFAQ\User;
use phpMyFAQ\Utils;

/**
 * Class MailHelper
 *
 * @package phpMyFAQ\Helper
 */
class MailHelper
{
    /** @var Configuration */
    private Configuration $config;

    /** @var Mail */
    private Mail $mail;

    /**
     * MailHelper constructor.
     *
     * @param Configuration $config
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
        $this->mail = new Mail($this->config);
    }

    /**
     * @param User   $user
     * @param string $password
     * @return bool
     * @throws Exception
     */
    public function sendMailToNewUser(User $user, string $password): bool
    {
        global $PMF_LANG;

        $text = sprintf(
            "You have been registered as a new user:" .
            "\n\nName: %s\nLogin name: %s\nPassword: %s\n\n" . 'Check it out here: %s',
            $user->getUserData('display_name'),
            $user->getLogin(),
            $password,
            $this->config->getDefaultUrl()
        );

        $this->mail->addTo($user->getUserData('email'), $user->getUserData('display_name'));
        $this->mail->subject = Utils::resolveMarkers($PMF_LANG['emailRegSubject'], $this->config);
        $this->mail->message = $text;

        return (bool) $this->mail->send();
    }
}
