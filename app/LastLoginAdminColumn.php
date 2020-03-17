<?php

namespace BoxyBird\EmailReminder;

class LastLoginAdminColumn
{
    public static function init()
    {
        add_action('wp_login', [LastLoginAdminColumn::class, 'updateTimestamp'], 10, 2);
        add_filter('manage_users_columns', [LastLoginAdminColumn::class, 'addAdminColumn']);
        add_filter('manage_users_custom_column', [LastLoginAdminColumn::class, 'addTimestamp'], 10, 3);
    }

    public static function updateTimestamp($user_login, $user)
    {
        update_user_meta($user->ID, 'bb_email_reminder_last_login', date(BOXYBIRD_EMAIL_REMINDER_DATE_FORMAT));
    }

    public static function addAdminColumn($column)
    {
        $column['bb_email_reminder_last_login'] = 'Last Login';

        return $column;
    }

    public static function addTimestamp($value, $column_name, $user_id)
    {
        if ($column_name === 'bb_email_reminder_last_login') {
            $date = get_user_meta($user_id, 'bb_email_reminder_last_login', true);

            return !empty($date) ? $date : 'N/A';
        }

        return $value;
    }
}
