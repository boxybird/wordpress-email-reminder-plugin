<?php

namespace BoxyBird\EmailReminder;

class Setup
{
    public static function activation()
    {
        if (!wp_next_scheduled('ddd')) {
            wp_schedule_event(time(), 'every_minute', 'ddd');
        }
    }

    public static function deactivation()
    {
        wp_clear_scheduled_hook('ddd');
    }
}

// add_filter('cron_schedules', function ($new_schedules) {
//     $new_schedules['every_minute'] = [
//         'interval' => 60,
//         'display'  => __('Every Minute'),
//     ];

//     return $new_schedules;
// });
