<?php

namespace BoxyBird\EmailReminder;

class HandleUserLogin
{
    public static function init()
    {
        add_action('wp_login', [HandleUserLogin::class, 'updateMeta'], 10, 2);
    }

    public static function updateMeta($user_login, $user)
    {
        update_user_meta($user->ID, 'bb_email_reminder_sent_count', 0);
        update_user_meta($user->ID, 'bb_email_reminder_last_login', date(BOXYBIRD_EMAIL_REMINDER_DATE_FORMAT));

        if (empty(get_user_meta($user->ID, 'bb_email_reminder_sent_date', true))) {
            update_user_meta($user->ID, 'bb_email_reminder_sent_date', date(BOXYBIRD_EMAIL_REMINDER_DATE_FORMAT));
        }
    }
}
