<?php
if (!defined('ABSPATH')) {
    exit;
}

function nasa_apod_display($data, $atts) {
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
