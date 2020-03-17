<?php

namespace BoxyBird\EmailReminder;

class AdminSettings
{
    private $settings_api;

    public function __construct(WeDevs_Settings_API $settings_api)
    {
        $this->settings_api = $settings_api;

        add_action('admin_init', [$this, 'admin_init']);
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    public function admin_init()
    {
        //set the settings
        $this->settings_api->set_sections($this->get_settings_sections());
        $this->settings_api->set_fields($this->get_settings_fields());

        //initialize settings
        $this->settings_api->admin_init();
    }

    public function admin_menu()
    {
        add_submenu_page('users.php', 'Settings API', 'Settings API', 'activate_plugins', 'settings_api_test', [$this, 'plugin_page']);
    }

    public function get_settings_sections()
    {
        $sections = [
            [
                'id'    => 'wedevs_basics',
                'title' => __('Basic Settings', 'bb-email-reminder')
            ]
        ];

        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    public function get_settings_fields()
    {
        $settings_fields = [
            'wedevs_basics' => [
                [
                    'name'  => 'active',
                    'label' => __('Active', 'bb-email-reminder'),
                    'type'  => 'checkbox'
                ],
                [
                    'name'              => 'since_login',
                    'label'             => __('Since Last Login', 'bb-email-reminder'),
                    'desc'              => __('Number of days past since User last logged-in before sending email.', 'bb-email-reminder'),
                    'placeholder'       => __(7, 'bb-email-reminder'),
                    'min'               => 1,
                    'max'               => 365,
                    'step'              => 1,
                    'type'              => 'number',
                    'default'           => 7,
                    'sanitize_callback' => 'absint'
                ],
                [
                    'name'     => 'from_name',
                    'label'    => __('From Name', 'bb-email-reminder'),
                    'desc'     => __('Who\'s sending the email. (required)', 'bb-email-reminder'),
                    'default'  => get_bloginfo('name'),
                    'required' => true,
                    'type'     => 'text',
                ],
                [
                    'name'              => 'from_email',
                    'label'             => __('From Email', 'bb-email-reminder'),
                    'desc'              => __('Email address of the sender. (required)', 'bb-email-reminder'),
                    'default'           => !empty(parse_url(home_url())['host']) ? parse_url(home_url())['host'] : 'wordpress@example.com',
                    'type'              => 'text',
                    'input_type'        => 'email',
                    'required'          => true,
                    'sanitize_callback' => 'sanitize_email'
                ],
                [
                    'name'     => 'subject',
                    'label'    => __('Subject', 'bb-email-reminder'),
                    'desc'     => __('Subject line of the email.', 'bb-email-reminder'),
                    'sub_desc' => __('Available tags: <code>{{USERNAME}}</code> <code>{{DISPLAY_NAME}}</code>', 'bb-email-reminder'),
                    'default'  => 'Hello, {{USERNAME}}',
                    'type'     => 'text',
                ],
                [
                    'name'        => 'message',
                    'label'       => __('Body', 'bb-email-reminder'),
                    'desc'        => __('Body of the email.', 'bb-email-reminder'),
                    'sub_desc'    => __('Available tags: <code>{{USERNAME}}</code> <code>{{DISPLAY_NAME}}</code> <code>{{SITE_URL}}</code> <code>{{SITE_NAME}}</code>', 'bb-email-reminder'),
                    'placeholder' => __('Hey, {{DISPLAY_NAME}}. It looks like you haven\'t logged-in to your account for a while.', 'bb-email-reminder'),
                    'type'        => 'wysiwyg',
                    'size'        => 'large'
                ]
            ]
        ];

        return $settings_fields;
    }

    public function plugin_page()
    {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    public function get_pages()
    {
        $pages         = get_pages();
        $pages_options = [];

        if ($pages) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }
}
