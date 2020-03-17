<?php

namespace BoxyBird\EmailReminder;

class Setup
{
    public static function cronSchedules($new_schedules)
    {
        $new_schedules['every_minute'] = [
            'interval' => 60,
            'display'  => __('Every Minute'),
        ];

        return $new_schedules;
    }

    public static function pluginActionLinks($links)
    {
        $links[] = '<a href="' . admin_url('users.php?page=bb-email-reminder') . '">' . __('Settings') . '</a>';

        return $links;
    }

    public static function activation()
    {
        if (!wp_next_scheduled('bb_email_reminder_cron')) {
            wp_schedule_event(time(), 'hourly', 'bb_email_reminder_cron');
        }
    }

    public static function deactivation()
    {
        wp_clear_scheduled_hook('bb_email_reminder_cron');
    }
}
