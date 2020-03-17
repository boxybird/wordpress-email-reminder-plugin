<?php

namespace BoxyBird\EmailReminder;

class HandleUserLogin
{
    public static function init()
    {
        add_action('wp_login', [HandleUserLogin::class, 'updateTimestamp'], 10, 2);
        add_action('wp_login', [HandleUserLogin::class, 'updateSentCount'], 10, 2);
    }

    public static function updateTimestamp($user_login, $user)
    {
        update_user_meta($user->ID, 'bb_email_reminder_last_login', date(BOXYBIRD_EMAIL_REMINDER_DATE_FORMAT));
    }

    public static function updateSentCount($user_login, $user)
    {
        update_user_meta($user->ID, 'bb_email_reminder_sent_count', 0);
    }
}
