<?php

/**
 *
 * @link              andrewrhyand.com
 * @since             0.1.0
 * @package           Boxybird_Email_Reminder
 *
 * @wordpress-plugin
 * Plugin Name:       Email Reminder
 * Plugin URI:        #
 * Description:       Sends email to User based on their last login, and adds timestamp and email sent count admin column for the last time they logged-in.
 * Version:           1.0.0
 * Author:            Andrew Rhyand
 * Author URI:        andrewrhyand.com
 * License:           MIT License
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       bb-email-reminder
 */

namespace BoxyBird\EmailReminder;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Composer Autoload
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Currently plugin version.
 */
define('BOXYBIRD_EMAIL_REMINDER', '0.1.0');
define('BOXYBIRD_EMAIL_REMINDER_DATE_FORMAT', 'Y-m-d H:i:s');

/**
 * Setup
 */
register_activation_hook(__FILE__, [Setup::class, 'activation']);
register_deactivation_hook(__FILE__, [Setup::class, 'deactivation']);
add_filter('cron_schedules', [Setup::class, 'cronSchedules']);
add_filter('plugin_action_links_' . plugin_basename(__FILE__), [Setup::class, 'pluginActionLinks']);

/**
 * Init core classes
 */
HandleUserLogin::init();
LastLoginAdminColumn::init();
EmailCountAdminColumn::init();
new AdminSettings(new Lib\WeDevs_Settings_API);

/**
 * Send emails based on cron schedule
 */
add_action('bb_email_reminder_cron', function () {
    EmailSender::run();
});
