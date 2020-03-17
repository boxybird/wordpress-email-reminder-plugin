<?php

namespace BoxyBird\EmailReminder;

class EmailSender
{
    protected static $db;

    protected static $users;

    protected static $active;

    protected static $subject;

    protected static $message;

    protected static $from_name;

    protected static $from_email;

    protected static $day_since_login;

    protected static $email_send_limit;

    public static function init()
    {
        global $wpdb;

        self::$db = $wpdb;

        self::setProperties();
        self::setUsers();

        // add_action('ddd', [EmailSender::class, 'sendEmails']);
        add_filter('wp_mail_from', [EmailSender::class, 'modifyMailFrom']);
        add_filter('wp_mail_from_name', [EmailSender::class, 'modifyFromName']);
    }

    public static function sendEmails()
    {
        if (self::$active === 'on' && self::$subject && self::$message) {
            foreach (self::$users as $user) {
                $subject_replace = [
                    '{{DISPLAY_NAME}}' => $user['display_name'],
                    '{{USERNAME}}'     => $user['user_nicename'],
                ];

                $message_replace = array_merge($subject_replace, [
                    '{{SITE_URL}}'  => get_bloginfo('url'),
                    '{{SITE_NAME}}' => get_bloginfo('name'),
                ]);

                $prepared_subject = str_replace(array_keys($subject_replace), array_values($subject_replace), self::$subject);
                $prepared_message = str_replace(array_keys($message_replace), array_values($message_replace), self::$message);
                $headers          = ['Content-Type: text/html; charset=UTF-8'];

                $mail = wp_mail($user['user_email'], $prepared_subject, $prepared_message, $headers);

                if ($mail) {
                    self::incrementSentCount($user);
                    self::updateLastSentDate($user);
                }
            }
        }
    }

    public static function modifyFromName($from_name)
    {
        if (!empty(self::$from_name)) {
            $from_name = self::$from_name;
        }

        return $from_name;
    }

    public static function modifyMailFrom($from_email)
    {
        if (!empty(self::$from_email) && is_email(self::$from_email)) {
            $from_email = self::$from_email;
        }

        return $from_email;
    }

    protected static function setProperties()
    {
        $options = get_option('wedevs_basics');

        self::$active           = !empty($options['active']) ? $options['active'] : 'off';
        self::$subject          = !empty($options['subject']) ? $options['subject'] : '';
        self::$message          = !empty($options['message']) ? $options['message'] : '';
        self::$from_name        = !empty($options['from_name']) ? $options['from_name'] : '';
        self::$from_email       = !empty($options['from_email']) ? $options['from_email'] : '';
        self::$day_since_login  = !empty($options['since_login']) ? (int) $options['since_login'] : 7;
        self::$email_send_limit = !empty($options['email_send_limit']) ? (int) $options['email_send_limit'] : 3;
    }

    protected static function setUsers()
    {
        try {
            $db = self::$db;

            self::$users = $db->get_results(
                $db->prepare(
                    "SELECT `ID`, `user_nicename`, `user_email`, `display_name` FROM `{$db->prefix}users`
                     WHERE `ID` IN (
                        SELECT `user_id` FROM `{$db->prefix}usermeta` 
                        WHERE `meta_key` = 'bb_last_login'
                        AND DATE(`meta_value`) < DATE_SUB(NOW(), INTERVAL %d DAY)
                        AND `user_id` IN (
                            SELECT `user_id` FROM `{$db->prefix}usermeta`
                            WHERE `meta_key` = 'bb_email_sent_count'
                            AND `meta_value` <= %d
                            AND `user_id` = `user_id`
                        )
                    )
                    LIMIT 10
                    ",
                    [
                        self::$day_since_login,
                        self::$email_send_limit,
                    ]
                ),
                ARRAY_A
            );
        } catch (Exception $e) {
            self::$users = [];
        }
    }

    protected static function incrementSentCount($user)
    {
        $count = get_user_meta($user['ID'], 'bb_email_sent_count', true);
        $count = !empty($count) ? (int) $count : 0;
        $count++;

        update_user_meta($user['ID'], 'bb_email_sent_count', $count);
    }

    protected static function updateLastSentDate($user)
    {
        update_user_meta($user['ID'], 'bb_email_sent_date', date(BOXYBIRD_EMAIL_REMINDER_DATE_FORMAT));
    }
}
