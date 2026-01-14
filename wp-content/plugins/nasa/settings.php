<?php
if (!defined('ABSPATH')) {
    exit;
}

function nasa_apod_settings_page() {
    ?>
    <div class="wrap">
        <h1>NASA APOD Settings</h1>

        <form method="post" action="options.php">
            <?php settings_fields('nasa_apod_settings'); ?>
            <?php do_settings_sections('nasa_apod_settings'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="nasa_apod_api_url">API Base URL</label>
                    </th>
                    <td>
                        <input type="text"
                               id="nasa_apod_api_url"
                               name="nasa_apod_api_url"
                               value="<?php echo esc_attr(get_option('nasa_apod_api_url', 'https://api.nasa.gov')); ?>"
                               class="regular-text">
                        <p class="description">
                            Base URL for the NASA API. Use this field to configure a proxy server if needed.
                            <br>Default: <code>https://api.nasa.gov</code>
                            <br>The API path (<code>/planetary/apod</code>) will be automatically appended to this URL.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="nasa_apod_api_key">NASA API Key</label>
                    </th>
                    <td>
                        <input type="password"
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
                <tr>
                    <th scope="row">
                        <label for="nasa_apod_cache_duration">Cache Duration (hours)</label>
                    </th>
                    <td>
                        <input type="number"
                               id="nasa_apod_cache_duration"
                               name="nasa_apod_cache_duration"
                               value="<?php echo esc_attr(get_option('nasa_apod_cache_duration', '12')); ?>"
                               min="1"
                               max="168"
                               class="small-text">
                        <p class="description">
                            How long to cache API responses (1-168 hours). Default: 12 hours.
                            <br>Higher values reduce API calls but may show outdated data.
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
