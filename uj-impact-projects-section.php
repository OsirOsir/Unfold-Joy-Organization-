<?php
/**
 * Plugin Name: UJ Impact Projects Section
 * Plugin URI:  https://unfoldjoy.org/
 * Description: Custom shortcode section for Unfold Joy homepage Impact / Projects block with editable admin settings.
 * Version:     1.0.0
 * Author:      UJ
 * License:     GPL2+
 */

if (!defined('ABSPATH')) {
    exit;
}

class UJ_Impact_Projects_Section {
    private $option_name = 'uj_impact_projects_settings';

    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
        add_shortcode('uj_impact_projects', [$this, 'render_shortcode']);
    }

    public function add_admin_menu() {
        add_menu_page(
            'UJ Home Sections',
            'UJ Home Sections',
            'manage_options',
            'uj-home-sections',
            [$this, 'render_settings_page'],
            'dashicons-screenoptions',
            25
        );

        add_submenu_page(
            'uj-home-sections',
            'Impact / Projects',
            'Impact / Projects',
            'manage_options',
            'uj-impact-projects',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings() {
        register_setting(
            'uj_impact_projects_group',
            $this->option_name,
            [$this, 'sanitize_settings']
        );
    }

    public function sanitize_settings($input) {
        $output = [];

        $text_fields = [
            'badge_text',
            'section_title',
            'section_intro',
            'badge_image',
            'background_color',
            'section_top_padding',
            'section_bottom_padding',
        ];

        foreach ($text_fields as $field) {
            $output[$field] = isset($input[$field]) ? sanitize_text_field($input[$field]) : '';
        }

        for ($i = 1; $i <= 3; $i++) {
            $output["card_{$i}_image"] = isset($input["card_{$i}_image"]) ? esc_url_raw($input["card_{$i}_image"]) : '';
            $output["card_{$i}_title"] = isset($input["card_{$i}_title"]) ? sanitize_text_field($input["card_{$i}_title"]) : '';
            $output["card_{$i}_text"] = isset($input["card_{$i}_text"]) ? sanitize_textarea_field($input["card_{$i}_text"]) : '';
            $output["card_{$i}_button_text"] = isset($input["card_{$i}_button_text"]) ? sanitize_text_field($input["card_{$i}_button_text"]) : '';
            $output["card_{$i}_button_link"] = isset($input["card_{$i}_button_link"]) ? esc_url_raw($input["card_{$i}_button_link"]) : '';
        }

        return $output;
    }

    public function admin_assets($hook) {
        if (!in_array($hook, ['toplevel_page_uj-home-sections', 'uj-home-sections_page_uj-impact-projects'], true)) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        wp_add_inline_script('jquery-core', "
            jQuery(document).ready(function($){
                function ujBindUploader(buttonClass) {
                    $(document).on('click', buttonClass, function(e){
                        e.preventDefault();
                        var button = $(this);
                        var target = button.data('target');
                        var frame = wp.media({
                            title: 'Select or Upload Image',
                            button: { text: 'Use this image' },
                            multiple: false
                        });

                        frame.on('select', function() {
                            var attachment = frame.state().get('selection').first().toJSON();
                            $('#' + target).val(attachment.url);
                        });

                        frame.open();
                    });
                }

                ujBindUploader('.uj-upload-btn');

                $('.uj-color-field').wpColorPicker();
            });
        ");
    }

    private function get_defaults() {
        return [
            'badge_text'            => 'IMPACT',
            'section_title'         => 'Our Impact & Ongoing Projects',
            'section_intro'         => 'We implement community-driven projects that empower young people, women, and persons with disabilities through health programs, advocacy, mentorship, and sustainable community action.',
            'badge_image'           => '',
            'background_color'      => '#ffffff',
            'section_top_padding'   => '90',
            'section_bottom_padding'=> '90',

            'card_1_image'          => '',
            'card_1_title'          => 'Jitambue Ujilinde Program',
            'card_1_text'           => 'A community initiative focused on mental health awareness and sexual and reproductive health education among young people.',
            'card_1_button_text'    => 'Learn More',
            'card_1_button_link'    => '#',

            'card_2_image'          => '',
            'card_2_title'          => 'Gender Equality & Advocacy',
            'card_2_text'           => 'Promoting gender equality through outreach, education, and community advocacy that strengthens dignity, safety, and inclusion.',
            'card_2_button_text'    => 'View Project',
            'card_2_button_link'    => '#',

            'card_3_image'          => '',
            'card_3_title'          => 'Climate Action & Conservation',
            'card_3_text'           => 'Supporting sustainable environmental practices and climate awareness through practical community engagement and education.',
            'card_3_button_text'    => 'Explore More',
            'card_3_button_link'    => '#',
        ];
    }

    private function get_settings() {
        $saved = get_option($this->option_name, []);
        return wp_parse_args($saved, $this->get_defaults());
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $settings = $this->get_settings();
        ?>
        <div class="wrap">
            <h1>UJ Impact / Projects Section</h1>
            <p>Use shortcode: <code>[uj_impact_projects]</code></p>
            <p><strong>Recommended image size:</strong> 1200 x 900 px (4:3 ratio)</p>

            <form method="post" action="options.php">
                <?php settings_fields('uj_impact_projects_group'); ?>

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="badge_text">Badge Text</label></th>
                        <td>
                            <input type="text" id="badge_text" name="<?php echo esc_attr($this->option_name); ?>[badge_text]" value="<?php echo esc_attr($settings['badge_text']); ?>" class="regular-text">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="section_title">Section Title</label></th>
                        <td>
                            <input type="text" id="section_title" name="<?php echo esc_attr($this->option_name); ?>[section_title]" value="<?php echo esc_attr($settings['section_title']); ?>" class="regular-text">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="section_intro">Section Intro</label></th>
                        <td>
                            <textarea id="section_intro" name="<?php echo esc_attr($this->option_name); ?>[section_intro]" rows="4" class="large-text"><?php echo esc_textarea($settings['section_intro']); ?></textarea>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="badge_image">Badge Comment Image URL</label></th>
                        <td>
                            <input type="text" id="badge_image" name="<?php echo esc_attr($this->option_name); ?>[badge_image]" value="<?php echo esc_attr($settings['badge_image']); ?>" class="regular-text">
                            <button type="button" class="button uj-upload-btn" data-target="badge_image">Upload / Choose Image</button>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="background_color">Background Color</label></th>
                        <td>
                            <input type="text" id="background_color" name="<?php echo esc_attr($this->option_name); ?>[background_color]" value="<?php echo esc_attr($settings['background_color']); ?>" class="uj-color-field" data-default-color="#ffffff">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="section_top_padding">Top Padding (px)</label></th>
                        <td>
                            <input type="number" id="section_top_padding" name="<?php echo esc_attr($this->option_name); ?>[section_top_padding]" value="<?php echo esc_attr($settings['section_top_padding']); ?>" min="0">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="section_bottom_padding">Bottom Padding (px)</label></th>
                        <td>
                            <input type="number" id="section_bottom_padding" name="<?php echo esc_attr($this->option_name); ?>[section_bottom_padding]" value="<?php echo esc_attr($settings['section_bottom_padding']); ?>" min="0">
                        </td>
                    </tr>
                </table>

                <hr>

                <?php for ($i = 1; $i <= 3; $i++) : ?>
                    <h2>Card <?php echo (int) $i; ?></h2>
                    <table class="form-table" role="presentation">
                        <tr>
                            <th scope="row"><label for="card_<?php echo $i; ?>_image">Image URL</label></th>
                            <td>
                                <input type="text" id="card_<?php echo $i; ?>_image" name="<?php echo esc_attr($this->option_name); ?>[card_<?php echo $i; ?>_image]" value="<?php echo esc_attr($settings["card_{$i}_image"]); ?>" class="regular-text">
                                <button type="button" class="button uj-upload-btn" data-target="card_<?php echo $i; ?>_image">Upload / Choose Image</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="card_<?php echo $i; ?>_title">Title</label></th>
                            <td>
                                <input type="text" id="card_<?php echo $i; ?>_title" name="<?php echo esc_attr($this->option_name); ?>[card_<?php echo $i; ?>_title]" value="<?php echo esc_attr($settings["card_{$i}_title"]); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="card_<?php echo $i; ?>_text">Description</label></th>
                            <td>
                                <textarea id="card_<?php echo $i; ?>_text" name="<?php echo esc_attr($this->option_name); ?>[card_<?php echo $i; ?>_text]" rows="4" class="large-text"><?php echo esc_textarea($settings["card_{$i}_text"]); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="card_<?php echo $i; ?>_button_text">Button Text</label></th>
                            <td>
                                <input type="text" id="card_<?php echo $i; ?>_button_text" name="<?php echo esc_attr($this->option_name); ?>[card_<?php echo $i; ?>_button_text]" value="<?php echo esc_attr($settings["card_{$i}_button_text"]); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="card_<?php echo $i; ?>_button_link">Button Link</label></th>
                            <td>
                                <input type="url" id="card_<?php echo $i; ?>_button_link" name="<?php echo esc_attr($this->option_name); ?>[card_<?php echo $i; ?>_button_link]" value="<?php echo esc_attr($settings["card_{$i}_button_link"]); ?>" class="regular-text">
                            </td>
                        </tr>
                    </table>
                    <hr>
                <?php endfor; ?>

                <?php submit_button('Save Impact Section'); ?>
            </form>
        </div>
        <?php
    }

    public function render_shortcode() {
        $s = $this->get_settings();

        ob_start();
        ?>
        <section class="uj-impact-projects-section" style="
            background: <?php echo esc_attr($s['background_color']); ?>;
            padding-top: <?php echo (int) $s['section_top_padding']; ?>px;
            padding-bottom: <?php echo (int) $s['section_bottom_padding']; ?>px;
        ">
            <div class="uj-impact-container">
                <div class="uj-impact-header">
                    <div class="uj-impact-badge-wrap">
                        <div class="uj-impact-badge" <?php if (!empty($s['badge_image'])) : ?>style="background-image:url('<?php echo esc_url($s['badge_image']); ?>');"<?php endif; ?>>
                            <span><?php echo esc_html($s['badge_text']); ?></span>
                        </div>
                    </div>

                    <h2 class="uj-impact-title"><?php echo esc_html($s['section_title']); ?></h2>
                    <p class="uj-impact-intro"><?php echo esc_html($s['section_intro']); ?></p>
                </div>

                <div class="uj-impact-grid">
                    <?php for ($i = 1; $i <= 3; $i++) : ?>
                        <article class="uj-impact-card uj-impact-card-<?php echo (int) $i; ?>">
                            <?php if (!empty($s["card_{$i}_image"])) : ?>
                                <div class="uj-impact-card-image-wrap">
                                    <img
                                        src="<?php echo esc_url($s["card_{$i}_image"]); ?>"
                                        alt="<?php echo esc_attr($s["card_{$i}_title"]); ?>"
                                        class="uj-impact-card-image"
                                    >
                                </div>
                            <?php endif; ?>

                            <div class="uj-impact-card-content">
                                <h3 class="uj-impact-card-title"><?php echo esc_html($s["card_{$i}_title"]); ?></h3>
                                <p class="uj-impact-card-text"><?php echo esc_html($s["card_{$i}_text"]); ?></p>

                                <?php if (!empty($s["card_{$i}_button_text"])) : ?>
                                    <a class="uj-impact-btn" href="<?php echo esc_url($s["card_{$i}_button_link"]); ?>">
                                        <?php echo esc_html($s["card_{$i}_button_text"]); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endfor; ?>
                </div>
            </div>
        </section>

        <style>
            .uj-impact-projects-section {
                width: 100%;
            }

            .uj-impact-container {
                width: 100%;
                max-width: 1200px;
                margin: 0 auto;
                padding-left: 20px;
                padding-right: 20px;
                box-sizing: border-box;
            }

            .uj-impact-header {
                text-align: center;
                max-width: 760px;
                margin: 0 auto 50px;
            }

            .uj-impact-badge-wrap {
                margin-bottom: 18px;
            }

            .uj-impact-badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 130px;
                min-height: 58px;
                padding: 10px 24px;
                background-repeat: no-repeat;
                background-position: center;
                background-size: contain;
            }

            .uj-impact-badge span {
                color: #001738;
                font-size: 14px;
                font-weight: 700;
                letter-spacing: 0.4px;
                text-transform: uppercase;
            }

            .uj-impact-title {
                margin: 0 0 14px;
                color: #001738;
                font-size: 42px;
                line-height: 1.2;
                font-weight: 800;
            }

            .uj-impact-intro {
                margin: 0;
                color: #5f6b84;
                font-size: 17px;
                line-height: 1.8;
            }

            .uj-impact-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 28px;
            }

            .uj-impact-card {
                background: #ffffff;
                border-radius: 18px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0, 23, 56, 0.08);
                transition: transform 0.35s ease, box-shadow 0.35s ease;
                position: relative;
            }

            .uj-impact-card:before {
                content: "";
                display: block;
                width: 100%;
                height: 5px;
                background: #00c867;
            }

            .uj-impact-card-2:before {
                background: #ff3692;
            }

            .uj-impact-card-3:before {
                background: #00c867;
            }

            .uj-impact-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 18px 45px rgba(0, 23, 56, 0.14);
            }

            .uj-impact-card-image-wrap {
                overflow: hidden;
                height: 220px;
                background: #f4f7fb;
            }

            .uj-impact-card-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
                transition: transform 0.45s ease;
            }

            .uj-impact-card:hover .uj-impact-card-image {
                transform: scale(1.06);
            }

            .uj-impact-card-content {
                padding: 24px;
            }

            .uj-impact-card-title {
                margin: 0 0 12px;
                color: #001738;
                font-size: 24px;
                line-height: 1.3;
                font-weight: 800;
            }

            .uj-impact-card-text {
                margin: 0 0 22px;
                color: #5f6b84;
                font-size: 15px;
                line-height: 1.8;
            }

            .uj-impact-btn {
                display: inline-block;
                background: #ff3692;
                color: #ffffff !important;
                text-decoration: none !important;
                font-size: 14px;
                font-weight: 700;
                line-height: 1;
                padding: 14px 22px;
                border-radius: 6px;
                transition: all 0.3s ease;
            }

            .uj-impact-btn:hover {
                background: #00c867;
                color: #ffffff !important;
            }

            @media (max-width: 991px) {
                .uj-impact-title {
                    font-size: 34px;
                }

                .uj-impact-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 767px) {
                .uj-impact-projects-section {
                    overflow: hidden;
                }

                .uj-impact-title {
                    font-size: 28px;
                }

                .uj-impact-intro {
                    font-size: 15px;
                }

                .uj-impact-grid {
                    grid-template-columns: 1fr;
                    gap: 22px;
                }

                .uj-impact-card-image-wrap {
                    height: 210px;
                }

                .uj-impact-card-content {
                    padding: 20px;
                }

                .uj-impact-card-title {
                    font-size: 22px;
                }
            }
        </style>
        <?php
        return ob_get_clean();
    }
}

new UJ_Impact_Projects_Section();
