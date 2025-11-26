<?php
/**
 * Plugin Name: NASA Image Gallery
 * Description: A plugin to display NASA's Astronomy Picture of the Day.
 * Version: 1.0.0
 * Author: Rutger Pronk
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class NASA_APOD_Plugin {

    private $api_url = 'https://api.nasa.gov/planetary/apod';
    private $api_key = 'DEMO_KEY'; // Replace with your NASA API key from https://api.nasa.gov

    public function __construct() {
        // Register shortcode
        add_shortcode('nasa_apod', array($this, 'display_apod'));

        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));

        // Register settings
        add_action('admin_init', array($this, 'register_settings'));

        // Enqueue styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    /**
     * Fetch data from NASA APOD API
     */
    public function fetch_apod_data($date = null) {
        $transient_key = 'nasa_apod_' . ($date ? $date : date('Y-m-d'));

        // Check if we have cached data
        $cached_data = get_transient($transient_key);
        if ($cached_data !== false) {
            return $cached_data;
        }

        // Get API key from settings or use default
        $api_key = get_option('nasa_apod_api_key', $this->api_key);

        // Build API URL
        $url = add_query_arg(array(
            'api_key' => $api_key,
            'date' => $date
        ), $this->api_url);

        // Fetch data
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return array('error' => $response->get_error_message());
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['error'])) {
            return array('error' => $data['error']['message']);
        }

        // Cache for 12 hours
        set_transient($transient_key, $data, 12 * HOUR_IN_SECONDS);

        return $data;
    }

    /**
     * Display APOD with shortcode [nasa_apod]
     */
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

        if (isset($data['error'])) {
            return '<div class="nasa-apod-error">Error: ' . esc_html($data['error']) . '</div>';
        }

        ob_start();
        ?>
        <div class="nasa-apod-container">
            <?php if ($atts['show_title'] === 'yes' && !empty($data['title'])): ?>
                <h2 class="nasa-apod-title"><?php echo esc_html($data['title']); ?></h2>
            <?php endif; ?>

            <?php if ($atts['show_date'] === 'yes' && !empty($data['date'])): ?>
                <p class="nasa-apod-date">
                    <strong>Date:</strong> <?php echo esc_html(date('F j, Y', strtotime($data['date']))); ?>
                </p>
            <?php endif; ?>

            <div class="nasa-apod-media" style="max-width: <?php echo esc_attr($atts['width']); ?>;">
                <?php if ($data['media_type'] === 'image'): ?>
                    <img src="<?php echo esc_url($data['url']); ?>"
                         alt="<?php echo esc_attr($data['title']); ?>"
                         class="nasa-apod-image">
                    <?php if (!empty($data['hdurl'])): ?>
                        <p class="nasa-apod-hd-link">
                            <a href="<?php echo esc_url($data['hdurl']); ?>" target="_blank">
                                View HD Version
                            </a>
                        </p>
                    <?php endif; ?>
                <?php elseif ($data['media_type'] === 'video'): ?>
                    <div class="nasa-apod-video">
                        <iframe src="<?php echo esc_url($data['url']); ?>"
                                frameborder="0"
                                allowfullscreen
                                class="nasa-apod-iframe"></iframe>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($atts['show_description'] === 'yes' && !empty($data['explanation'])): ?>
                <div class="nasa-apod-explanation">
                    <h3>Explanation</h3>
                    <p><?php echo nl2br(esc_html($data['explanation'])); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($atts['show_copyright'] === 'yes' && !empty($data['copyright'])): ?>
                <p class="nasa-apod-copyright">
                    <strong>Copyright:</strong> <?php echo esc_html($data['copyright']); ?>
                </p>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Enqueue frontend styles
     */
    public function enqueue_styles() {
        wp_add_inline_style('wp-block-library', '
            .nasa-apod-container {
                margin: 20px 0;
                padding: 20px;
                background: #f9f9f9;
                border-radius: 8px;
            }
            .nasa-apod-title {
                color: #0B3D91;
                margin-top: 0;
            }
            .nasa-apod-date {
                color: #666;
                font-size: 14px;
            }
            .nasa-apod-media {
                margin: 20px auto;
            }
            .nasa-apod-image {
                width: 100%;
                height: auto;
                border-radius: 4px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            .nasa-apod-video,
            .nasa-apod-iframe {
                width: 100%;
                aspect-ratio: 16/9;
                border-radius: 4px;
            }
            .nasa-apod-hd-link {
                text-align: center;
                margin-top: 10px;
            }
            .nasa-apod-hd-link a {
                background: #0B3D91;
                color: white;
                padding: 8px 16px;
                text-decoration: none;
                border-radius: 4px;
                display: inline-block;
            }
            .nasa-apod-hd-link a:hover {
                background: #062554;
            }
            .nasa-apod-explanation {
                margin-top: 20px;
                line-height: 1.6;
            }
            .nasa-apod-explanation h3 {
                color: #0B3D91;
            }
            .nasa-apod-copyright {
                font-size: 12px;
                color: #666;
                font-style: italic;
                margin-top: 15px;
            }
            .nasa-apod-error {
                background: #f8d7da;
                color: #721c24;
                padding: 12px;
                border-radius: 4px;
                border: 1px solid #f5c6cb;
            }
        ');
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            'NASA APOD Settings',
            'NASA APOD',
            'manage_options',
            'nasa-apod-settings',
            array($this, 'settings_page')
        );
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('nasa_apod_settings', 'nasa_apod_api_key');
    }

    /**
     * Settings page
     */
    public function settings_page() {
        ?>
        <div class="wrap">
            <h1>NASA APOD Settings</h1>

            <form method="post" action="options.php">
                <?php settings_fields('nasa_apod_settings'); ?>
                <?php do_settings_sections('nasa_apod_settings'); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="nasa_apod_api_key">NASA API Key</label>
                        </th>
                        <td>
                            <input type="text"
                                   id="nasa_apod_api_key"
                                   name="nasa_apod_api_key"
                                   value="<?php echo esc_attr(get_option('nasa_apod_api_key', 'DEMO_KEY')); ?>"
                                   class="regular-text">
                            <p class="description">
                                Get your free API key at <a href="https://api.nasa.gov" target="_blank">https://api.nasa.gov</a>
                                <br>Using DEMO_KEY is limited to 30 requests per hour per IP address.
                            </p>
                        </td>
                    </tr>
                </table>

                <?php submit_button(); ?>
            </form>

            <hr>

            <h2>How to Use</h2>
            <p>Use the shortcode <code>[nasa_apod]</code> in any post or page to display today's Astronomy Picture of the Day.</p>

            <h3>Shortcode Parameters:</h3>
            <ul>
                <li><code>[nasa_apod]</code> - Display today's APOD</li>
                <li><code>[nasa_apod date="2024-01-01"]</code> - Display APOD from a specific date</li>
                <li><code>[nasa_apod width="800px"]</code> - Set custom width</li>
                <li><code>[nasa_apod show_title="no"]</code> - Hide title</li>
                <li><code>[nasa_apod show_description="no"]</code> - Hide description</li>
                <li><code>[nasa_apod show_date="no"]</code> - Hide date</li>
                <li><code>[nasa_apod show_copyright="no"]</code> - Hide copyright</li>
            </ul>

            <h3>Preview:</h3>
            <div style="max-width: 800px;">
                <?php
                $plugin = new NASA_APOD_Plugin();
                echo $plugin->display_apod(array());
                ?>
            </div>
        </div>
        <?php
    }
}

// Initialize the plugin
new NASA_APOD_Plugin();
