<?php

namespace BoxyBird\EmailReminder\Admin;

class EmailCountColumn
{
    public static function init()
    {
        add_filter('manage_users_columns', [EmailCountColumn::class, 'addAdminColumn']);
        add_filter('manage_users_custom_column', [EmailCountColumn::class, 'addEmailCount'], 10, 3);
    }

    public static function addAdminColumn($column)
    {
        $column['bb_email_reminder_sent_count'] = 'Emails Sent';

        return $column;
    }

    public static function addEmailCount($value, $column_name, $user_id)
    {
        if ($column_name === 'bb_email_reminder_sent_count') {
            $date = get_user_meta($user_id, 'bb_email_reminder_sent_count', true);

            return !empty($date) ? $date : 0;
        }

        return $value;
    }
}
