<?php
/**
 * Plugin Name: NASA Image Gallery
 * Description: A plugin to display NASA's Astronomy Picture of the Day.
 * Version: 1.0.0
 * Author: Rutger Pronk
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'settings.php';
require_once plugin_dir_path(__FILE__) . 'apod-display.php';

class NASA_APOD_Plugin {
    private $api_url = 'https://api.nasa.gov/planetary/apod';
    private $api_key = 'DEMO_KEY';

    public function __construct() {
        add_shortcode('nasa_apod', array($this, 'display_apod'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    public function fetch_apod_data($date = null) {
        $transient_key = 'nasa_apod_' . ($date ? $date : date('Y-m-d'));

        $cached_data = get_transient($transient_key);
        if ($cached_data !== false) {
            return $cached_data;
        }

        $api_key = get_option('nasa_apod_api_key', $this->api_key);

        $url = add_query_arg(array(
            'api_key' => $api_key,
            'date' => $date
        ), $this->api_url);

        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return array('error' => $response->get_error_message());
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['error'])) {
            return array('error' => $data['error']['message']);
        }

        set_transient($transient_key, $data, 12 * HOUR_IN_SECONDS);

        return $data;
    }

    public function display_apod($atts) {
        $atts = shortcode_atts(array(
            'date' => null,
            'width' => '100%',
            'show_title' => 'yes',
            'show_description' => 'yes',
            'show_date' => 'yes',
            'show_copyright' => 'yes'
        ), $atts);

        $data = $this->fetch_apod_data($atts['date']);

        return nasa_apod_display($data, $atts);
    }

    public function enqueue_styles() {
        wp_enqueue_style(
            'nasa-apod-styles',
            plugin_dir_url(__FILE__) . 'style.css',
            array(),
            '1.0.0'
        );
    }

    public function add_admin_menu() {
        add_options_page(
            'NASA APOD Settings',
            'NASA APOD',
            'manage_options',
            'nasa-apod-settings',
            'nasa_apod_settings_page'
        );
    }

    public function register_settings() {
        register_setting('nasa_apod_settings', 'nasa_apod_api_key');
    }
}

new NASA_APOD_Plugin();
