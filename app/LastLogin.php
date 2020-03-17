<?php

namespace BoxyBird\EmailReminder;

class LastLogin
{
    public static function init()
    {
        add_action('wp_login', [LastLogin::class, 'updateTimestamp'], 10, 2);
        add_filter('manage_users_columns', [LastLogin::class, 'addAdminColumns']);
        add_filter('manage_users_custom_column', [LastLogin::class, 'addTimestamp'], 10, 3);
        add_filter('manage_users_sortable_columns', [LastLogin::class, 'makeSortable']);
    }

    public static function updateTimestamp($user_login, $user)
    {
        update_user_meta($user->ID, 'bb_last_login', date(BOXYBIRD_EMAIL_REMINDER_DATE_FORMAT));
    }

    public static function addAdminColumns($column)
    {
        $column['last_login'] = 'Last Login';

        return $column;
    }

    public static function addTimestamp($value, $column_name, $user_id)
    {
        if ($column_name === 'last_login') {
            $date = get_user_meta($user_id, 'bb_last_login', true);

            return !empty($date) ? $date : 'N/A';
        }

        return $value;
    }

    public static function makeSortable($columns)
    {
        $columns['last_login'] = 'last_login';

        return $columns;
    }
}
