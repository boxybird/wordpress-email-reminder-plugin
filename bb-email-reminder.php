<?php

/**
 *
 * @link              andrewrhyand.com
 * @since             1.0.0
 * @package           Boxybird_Email_Reminder
 *
 * @wordpress-plugin
 * Plugin Name:       Email Reminder
 * Plugin URI:        #
 * Description:       Sends email to User based on their last login, and adds timestamp admin column for the last time they logged-in.
 * Version:           1.0.0
 * Author:            Andrew Rhyand
 * Author URI:        andrewrhyand.com
 * License:           MIT License
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       bb-email-reminder
 * Domain Path:       /languages
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
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('BOXYBIRD_EMAIL_REMINDER', '1.0.0');
define('BOXYBIRD_EMAIL_REMINDER_DATE_FORMAT', 'Y-m-d H:i:s');

/**
 * Registration hooks
 */
register_activation_hook(__FILE__, [Setup::class, 'activation']);
register_deactivation_hook(__FILE__, [Setup::class, 'deactivation']);

/**
 * Init core classes
 */
LastLogin::init();
EmailSender::init();
new AdminSettings(new WeDevs_Settings_API);
