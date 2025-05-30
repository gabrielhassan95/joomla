<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_actionlogs
 *
 * @copyright   (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Actionlogs\Administrator\Model;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Mail\Exception\MailDisabledException;
use Joomla\CMS\Mail\MailTemplate;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\User\UserFactoryAwareInterface;
use Joomla\CMS\User\UserFactoryAwareTrait;
use Joomla\Component\Actionlogs\Administrator\Helper\ActionlogsHelper;
use Joomla\Utilities\IpHelper;
use PHPMailer\PHPMailer\Exception as phpMailerException;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Methods supporting a list of Actionlog records.
 *
 * @since  3.9.0
 */
class ActionlogModel extends BaseDatabaseModel implements UserFactoryAwareInterface
{
    use UserFactoryAwareTrait;

    /**
     * Function to add logs to the database
     * This method adds a record to #__action_logs contains (message_language_key, message, date, context, user)
     *
     * @param   array    $messages            The contents of the messages to be logged
     * @param   string   $messageLanguageKey  The language key of the message
     * @param   string   $context             The context of the content passed to the plugin
     * @param   integer  $userId              ID of user perform the action, usually ID of current logged in user
     *
     * @return  void
     *
     * @since   3.9.0
     */
    public function addLog($messages, $messageLanguageKey, $context, $userId = 0)
    {
        if (!is_numeric($userId)) {
            @trigger_error(\sprintf('User ID must be an integer in %s.', __METHOD__), E_USER_DEPRECATED);
        }

        try {
            $user = $userId ? $this->getUserFactory()->loadUserById($userId) : $this->getCurrentUser();
        } catch (\UnexpectedValueException $e) {
            @trigger_error('UserFactory must be set, this will not be caught anymore in 7.0.', E_USER_DEPRECATED);
            $user = Factory::getUser($userId);
        }

        $db     = $this->getDatabase();
        $date   = Factory::getDate();
        $params = ComponentHelper::getComponent('com_actionlogs')->getParams();

        if ($params->get('ip_logging', 0)) {
            $ip = IpHelper::getIp();

            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                $ip = 'COM_ACTIONLOGS_IP_INVALID';
            }
        } else {
            $ip = 'COM_ACTIONLOGS_DISABLED';
        }

        $loggedMessages = [];

        foreach ($messages as $message) {
            $logMessage                       = new \stdClass();
            $logMessage->message_language_key = $messageLanguageKey;
            $logMessage->message              = json_encode($message);
            $logMessage->log_date             = (string) $date;
            $logMessage->extension            = $context;
            $logMessage->user_id              = $user->id;
            $logMessage->ip_address           = $ip;
            $logMessage->item_id              = isset($message['id']) ? (int) $message['id'] : 0;

            try {
                $db->insertObject('#__action_logs', $logMessage);
                $loggedMessages[] = $logMessage;
            } catch (\RuntimeException) {
                // Ignore it
            }
        }

        try {
            // Send notification email to users who choose to be notified about the action logs
            $this->sendNotificationEmails($loggedMessages, $user->name, $context);
        } catch (MailDisabledException | phpMailerException) {
            // Ignore it
        }
    }

    /**
     * Send notification emails about the action log
     *
     * @param   array   $messages  The logged messages
     * @param   string  $username  The username
     * @param   string  $context   The Context
     *
     * @return  void
     *
     * @since   3.9.0
     *
     * @throws  MailDisabledException  if mail is disabled
     * @throws  phpmailerException     if sending mail failed
     */
    protected function sendNotificationEmails($messages, $username, $context)
    {
        $app   = Factory::getApplication();
        $lang  = $app->getLanguage();
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

        $query
            ->select($db->quoteName(['u.email', 'l.extensions']))
            ->from($db->quoteName('#__users', 'u'))
            ->where($db->quoteName('u.block') . ' = 0')
            ->join(
                'INNER',
                $db->quoteName('#__action_logs_users', 'l') . ' ON ( ' . $db->quoteName('l.notify') . ' = 1 AND '
                . $db->quoteName('l.user_id') . ' = ' . $db->quoteName('u.id') . ')'
            );

        $db->setQuery($query);

        $users = $db->loadObjectList();

        $recipients = [];

        foreach ($users as $user) {
            $extensions = json_decode($user->extensions, true);

            if ($extensions && \in_array(strtok($context, '.'), $extensions)) {
                $recipients[] = $user->email;
            }
        }

        if (empty($recipients)) {
            return;
        }

        $extension = strtok($context, '.');
        $lang->load('com_actionlogs', JPATH_ADMINISTRATOR);
        ActionlogsHelper::loadTranslationFiles($extension);
        $temp      = [];
        $tempPlain = [];

        foreach ($messages as $message) {
            $m              = [];
            $m['extension'] = Text::_($extension);
            $m['message']   = ActionlogsHelper::getHumanReadableLogMessage($message);
<<<<<<< HEAD
            $tzOffset       = Factory::getApplication()->get('offset');
            $m['date']      = HTMLHelper::_('date', $message->log_date, 'Y-m-d H:i:s T', $tzOffset);
=======
            $m['date']      = HTMLHelper::_('date', $message->log_date, 'Y-m-d H:i:s T', 'UTC');
>>>>>>> 954ffb7afcbc309937911469236d138fc7d262f9
            $m['username']  = $username;
            $temp[]         = $m;

            // copy replacement tags array and set non-HTML message.
            $mPlain            = array_merge([], $m);
            $mPlain['message'] = ActionlogsHelper::getHumanReadableLogMessage($message, false);
            $tempPlain[]       = $mPlain;
        }

        $templateData = [
            'messages' => $temp,
        ];
        $templateDataPlain = [
            'messages' => $tempPlain,
        ];

        $mailer = new MailTemplate('com_actionlogs.notification', $app->getLanguage()->getTag());
        $mailer->addTemplateData($templateData);
        $mailer->addTemplateData($templateDataPlain, true);

        foreach ($recipients as $recipient) {
            $mailer->addRecipient($recipient);
        }

        $mailer->send();
    }
}
