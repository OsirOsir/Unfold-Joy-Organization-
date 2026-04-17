<?php
/**
 * Plugin Name: Unfold Joy Home Sections
 * Description: Custom editable homepage sections for Unfold Joy Organization with shortcode support.
 * Version: 1.0.0
 * Author: OpenAI
 */

if (!defined('ABSPATH')) exit;

class UJ_Home_Sections_Plugin {
    private $option_name = 'uj_home_sections_options';

    public function __construct() {
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
        add_action('wp_enqueue_scripts', [$this, 'frontend_assets']);
        add_shortcode('uj_home_sections', [$this, 'shortcode']);
    }

    public function defaults() {
        return [
            'impact_badge' => 'IMPACT',
            'impact_title' => 'Our Impact & Ongoing Projects',
            'impact_text' => 'We implement community-driven projects that empower young people, women, and persons with disabilities through health programs, mentorship, advocacy, and sustainable development initiatives.',

            'impact_1_title' => 'Jitambue Ujilinde Program',
            'impact_1_text' => 'A community initiative focused on mental health awareness and sexual and reproductive health education among young people.',
            'impact_1_image' => '',
            'impact_1_link' => '',
            'impact_1_button' => 'Learn More',

            'impact_2_title' => 'Gender Equality & Advocacy',
            'impact_2_text' => 'Promoting gender equality through community outreach, education, and advocacy to address gender-based challenges.',
            'impact_2_image' => '',
            'impact_2_link' => '',
            'impact_2_button' => 'Learn More',

            'impact_3_title' => 'Climate Action & Environmental Conservation',
            'impact_3_text' => 'Supporting sustainable environmental practices and climate awareness through community engagement and education.',
            'impact_3_image' => '',
            'impact_3_link' => '',
            'impact_3_button' => 'Learn More',

            'cta_badge' => 'GET INVOLVED',
            'cta_title' => 'Be Part of the Change',
            'cta_text' => 'Join us in empowering communities and creating lasting impact through volunteering, partnership, and support.',
            'cta_button_1_text' => 'Volunteer With Us',
            'cta_button_1_link' => '/volunteer/',
            'cta_button_2_text' => 'Support Our Work',
            'cta_button_2_link' => '/donate/',

            'events_badge' => 'ACTIVITIES',
            'events_title' => 'Community Activities',
            'events_text' => 'Explore outreach programs, awareness campaigns, training sessions, and community events that drive our mission forward.',
            'events_1_title' => 'Youth Mentorship Session',
            'events_1_text' => 'Practical mentorship sessions equipping young people with confidence, guidance, and life skills.',
            'events_1_image' => '',
            'events_1_meta' => 'Kisumu, Kenya',
            'events_1_link' => '',
            'events_1_button' => 'View Activity',
            'events_2_title' => 'Mental Health Awareness Outreach',
            'events_2_text' => 'Community outreach creating awareness on mental well-being and access to support.',
            'events_2_image' => '',
            'events_2_meta' => 'Community Outreach',
            'events_2_link' => '',
            'events_2_button' => 'View Activity',
            'events_3_title' => 'Women Empowerment Forum',
            'events_3_text' => 'A safe and inspiring space for women to grow in leadership, confidence, and advocacy.',
            'events_3_image' => '',
            'events_3_meta' => 'Empowerment Forum',
            'events_3_link' => '',
            'events_3_button' => 'View Activity',

            'news_badge' => 'UPDATES',
            'news_title' => 'Latest Updates',
            'news_text' => 'Read recent updates on our programs, outreach activities, and community impact.',
            'news_1_title' => 'Advancing Mental Health Awareness',
            'news_1_text' => 'Highlights from our ongoing work to promote mental well-being among young people and communities.',
            'news_1_image' => '',
            'news_1_date' => 'Recent Update',
            'news_1_link' => '',
            'news_1_button' => 'Read Update',
            'news_2_title' => 'Promoting Gender Equality',
            'news_2_text' => 'Snapshots of our advocacy and community engagement for dignity, fairness, and equal opportunity.',
            'news_2_image' => '',
            'news_2_date' => 'Recent Update',
            'news_2_link' => '',
            'news_2_button' => 'Read Update',
            'news_3_title' => 'Driving Climate Action',
            'news_3_text' => 'Stories from our environmental conservation and sustainable community action initiatives.',
            'news_3_image' => '',
            'news_3_date' => 'Recent Update',
            'news_3_link' => '',
            'news_3_button' => 'Read Update',
        ];
    }

    public function get_options() {
        return wp_parse_args(get_option($this->option_name, []), $this->defaults());
    }

    public function admin_menu() {
        add_menu_page(
            'Unfold Joy Home Sections',
            'UJ Home Sections',
            'manage_options',
            'uj-home-sections',
            [$this, 'settings_page'],
            'dashicons-layout',
            58
        );
    }

    public function register_settings() {
        register_setting('uj_home_sections_group', $this->option_name, [$this, 'sanitize']);
    }

    public function sanitize($input) {
        $clean = [];
        $defaults = $this->defaults();
        foreach ($defaults as $key => $default) {
            $value = isset($input[$key]) ? $input[$key] : $default;
            if (strpos($key, '_image') !== false || strpos($key, '_link') !== false) {
                $clean[$key] = esc_url_raw($value);
            } else {
                $clean[$key] = wp_kses_post($value);
            }
        }
        return $clean;
    }

    public function admin_assets($hook) {
        if ($hook !== 'toplevel_page_uj-home-sections') return;
        wp_enqueue_media();
        wp_enqueue_style('uj-admin', false);
        wp_add_inline_style('uj-admin', $this->admin_css());
        wp_enqueue_script('uj-admin-js', false, ['jquery'], null, true);
        wp_add_inline_script('uj-admin-js', $this->admin_js());
    }

    public function frontend_assets() {
        wp_register_style('uj-home-sections', false);
        wp_enqueue_style('uj-home-sections');
        wp_add_inline_style('uj-home-sections', $this->frontend_css());
    }

    private function admin_css() {
        return '
        .uj-wrap{max-width:1200px}
        .uj-card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:20px;margin:0 0 24px;box-shadow:0 8px 24px rgba(0,0,0,.04)}
        .uj-card h2{margin-top:0;color:#001738}
        .uj-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
        .uj-grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
        .uj-field{margin-bottom:14px}
        .uj-field label{display:block;font-weight:600;margin:0 0 6px;color:#001738}
        .uj-field input[type=text], .uj-field input[type=url], .uj-field textarea{width:100%;max-width:none}
        .uj-image-wrap{display:flex;gap:10px;align-items:center}
        .uj-image-preview{width:72px;height:72px;border-radius:10px;background:#f3f4f6;background-size:cover;background-position:center;border:1px solid #ddd}
        .uj-note{color:#475569;margin:0 0 16px}
        @media (max-width: 900px){.uj-grid,.uj-grid-3{grid-template-columns:1fr}}
        ';
    }

    private function admin_js() {
        return "jQuery(function($){
            $(document).on('click', '.uj-upload-btn', function(e){
                e.preventDefault();
                var button = $(this);
                var target = $('#' + button.data('target'));
                var preview = $('#' + button.data('preview'));
                var frame = wp.media({title: 'Select image', multiple: false, library: {type: 'image'}, button: {text: 'Use image'}});
                frame.on('select', function(){
                    var attachment = frame.state().get('selection').first().toJSON();
                    target.val(attachment.url);
                    preview.css('background-image', 'url(' + attachment.url + ')');
                });
                frame.open();
            });
            $(document).on('click', '.uj-remove-btn', function(e){
                e.preventDefault();
                var button = $(this);
                $('#' + button.data('target')).val('');
                $('#' + button.data('preview')).css('background-image', 'none');
            });
        });";
    }

    public function settings_page() {
        $o = $this->get_options();
        ?>
        <div class="wrap uj-wrap">
            <h1>Unfold Joy Home Sections</h1>
            <p class="uj-note">Use shortcode <code>[uj_home_sections]</code> on the homepage where you want the custom sections to appear.</p>
            <div class="uj-card">
                <h2>Image Size Guide</h2>
                <p class="uj-note"><strong>Impact / Projects:</strong> 1200 × 800 px (3:2 ratio).<br><strong>Community Activities:</strong> 1200 × 800 px (3:2 ratio).<br><strong>Latest Updates:</strong> 1200 × 800 px (3:2 ratio).<br><strong>Best practice:</strong> keep people centered because the card images use <code>object-fit: cover</code>. Leave breathing room on the left and right edges so no faces get cropped awkwardly. For group photos, use landscape images, not portrait shots.</p>
            </div>
            <form method="post" action="options.php">
                <?php settings_fields('uj_home_sections_group'); ?>

                <div class="uj-card">
                    <h2>Impact / Projects Section</h2>
                    <div class="uj-grid">
                        <?php $this->text_field('impact_badge', 'Badge', $o); ?>
                        <?php $this->text_field('impact_title', 'Title', $o); ?>
                    </div>
                    <?php $this->textarea_field('impact_text', 'Intro Text', $o); ?>
                    <div class="uj-grid-3">
                        <?php $this->card_fields('impact_1', 'Card 1', $o); ?>
                        <?php $this->card_fields('impact_2', 'Card 2', $o); ?>
                        <?php $this->card_fields('impact_3', 'Card 3', $o); ?>
                    </div>
                </div>

                <div class="uj-card">
                    <h2>CTA Section</h2>
                    <div class="uj-grid">
                        <?php $this->text_field('cta_badge', 'Badge', $o); ?>
                        <?php $this->text_field('cta_title', 'Title', $o); ?>
                    </div>
                    <?php $this->textarea_field('cta_text', 'Text', $o); ?>
                    <div class="uj-grid">
                        <?php $this->text_field('cta_button_1_text', 'Button 1 Text', $o); ?>
                        <?php $this->text_field('cta_button_1_link', 'Button 1 Link', $o, 'url'); ?>
                        <?php $this->text_field('cta_button_2_text', 'Button 2 Text', $o); ?>
                        <?php $this->text_field('cta_button_2_link', 'Button 2 Link', $o, 'url'); ?>
                    </div>
                </div>

                <div class="uj-card">
                    <h2>Community Activities Section</h2>
                    <div class="uj-grid">
                        <?php $this->text_field('events_badge', 'Badge', $o); ?>
                        <?php $this->text_field('events_title', 'Title', $o); ?>
                    </div>
                    <?php $this->textarea_field('events_text', 'Intro Text', $o); ?>
                    <div class="uj-grid-3">
                        <?php $this->news_fields('events_1', 'Activity 1', $o, true); ?>
                        <?php $this->news_fields('events_2', 'Activity 2', $o, true); ?>
                        <?php $this->news_fields('events_3', 'Activity 3', $o, true); ?>
                    </div>
                </div>

                <div class="uj-card">
                    <h2>Latest Updates Section</h2>
                    <div class="uj-grid">
                        <?php $this->text_field('news_badge', 'Badge', $o); ?>
                        <?php $this->text_field('news_title', 'Title', $o); ?>
                    </div>
                    <?php $this->textarea_field('news_text', 'Intro Text', $o); ?>
                    <div class="uj-grid-3">
                        <?php $this->news_fields('news_1', 'Update 1', $o, false); ?>
                        <?php $this->news_fields('news_2', 'Update 2', $o, false); ?>
                        <?php $this->news_fields('news_3', 'Update 3', $o, false); ?>
                    </div>
                </div>

                <?php submit_button('Save Home Sections'); ?>
            </form>
        </div>
        <?php
    }

    private function text_field($key, $label, $o, $type = 'text') {
        $name = $this->option_name . '[' . $key . ']';
        echo '<div class="uj-field"><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label><input type="' . esc_attr($type) . '" id="' . esc_attr($key) . '" name="' . esc_attr($name) . '" value="' . esc_attr($o[$key]) . '"></div>';
    }

    private function textarea_field($key, $label, $o) {
        $name = $this->option_name . '[' . $key . ']';
        echo '<div class="uj-field"><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label><textarea rows="4" id="' . esc_attr($key) . '" name="' . esc_attr($name) . '">' . esc_textarea($o[$key]) . '</textarea></div>';
    }

    private function image_field($key, $label, $o) {
        $name = $this->option_name . '[' . $key . ']';
        $preview_id = $key . '_preview';
        $url = esc_url($o[$key]);
        echo '<div class="uj-field"><label>' . esc_html($label) . '</label><div class="uj-image-wrap"><div id="' . esc_attr($preview_id) . '" class="uj-image-preview" style="background-image:url(' . $url . ')"></div><div><input type="url" id="' . esc_attr($key) . '" name="' . esc_attr($name) . '" value="' . $url . '" placeholder="Image URL"><p><button class="button uj-upload-btn" data-target="' . esc_attr($key) . '" data-preview="' . esc_attr($preview_id) . '">Upload / Select</button> <button class="button uj-remove-btn" data-target="' . esc_attr($key) . '" data-preview="' . esc_attr($preview_id) . '">Remove</button></p></div></div></div>';
    }

    private function card_fields($prefix, $title, $o) {
        echo '<div class="uj-card"><h3>' . esc_html($title) . '</h3>';
        $this->text_field($prefix . '_title', 'Title', $o);
        $this->textarea_field($prefix . '_text', 'Text', $o);
        $this->image_field($prefix . '_image', 'Image', $o);
        $this->text_field($prefix . '_link', 'Link', $o, 'url');
        $this->text_field($prefix . '_button', 'Button Label', $o);
        echo '</div>';
    }

    private function news_fields($prefix, $title, $o, $use_meta) {
        echo '<div class="uj-card"><h3>' . esc_html($title) . '</h3>';
        $this->text_field($prefix . '_title', 'Title', $o);
        $this->textarea_field($prefix . '_text', 'Text', $o);
        $this->image_field($prefix . '_image', 'Image', $o);
        $this->text_field($prefix . ($use_meta ? '_meta' : '_date'), $use_meta ? 'Meta' : 'Date Label', $o);
        $this->text_field($prefix . '_link', 'Link', $o, 'url');
        $this->text_field($prefix . '_button', 'Button Label', $o);
        echo '</div>';
    }

    public function shortcode() {
        $o = $this->get_options();
        ob_start(); ?>
        <div class="uj-home-sections">
            <?php echo $this->render_impact($o); ?>
            <?php echo $this->render_cta($o); ?>
            <?php echo $this->render_events($o); ?>
            <?php echo $this->render_news($o); ?>
        </div>
        <?php
        return ob_get_clean();
    }

    private function badge($text) {
        return '<div class="uj-badge"><span>' . esc_html($text) . '</span></div>';
    }

    private function render_impact($o) {
        ob_start(); ?>
        <section class="uj-section uj-impact">
            <div class="uj-container">
                <div class="uj-section-head">
                    <?php echo $this->badge($o['impact_badge']); ?>
                    <h2><?php echo esc_html($o['impact_title']); ?></h2>
                    <p><?php echo esc_html($o['impact_text']); ?></p>
                </div>
                <div class="uj-grid uj-grid-3col">
                    <?php for ($i=1; $i<=3; $i++): ?>
                        <article class="uj-card-item uj-project-card">
                            <?php if (!empty($o['impact_'.$i.'_image'])): ?>
                                <div class="uj-card-image"><img src="<?php echo esc_url($o['impact_'.$i.'_image']); ?>" alt="<?php echo esc_attr($o['impact_'.$i.'_title']); ?>"></div>
                            <?php endif; ?>
                            <div class="uj-card-body">
                                <h3><?php echo esc_html($o['impact_'.$i.'_title']); ?></h3>
                                <p><?php echo esc_html($o['impact_'.$i.'_text']); ?></p>
                                <?php if (!empty($o['impact_'.$i.'_link'])): ?><a class="uj-btn uj-btn-card" href="<?php echo esc_url($o['impact_'.$i.'_link']); ?>"><?php echo esc_html($o['impact_'.$i.'_button']); ?></a><?php endif; ?>
                            </div>
                        </article>
                    <?php endfor; ?>
                </div>
            </div>
        </section>
        <?php return ob_get_clean();
    }

    private function render_cta($o) {
        ob_start(); ?>
        <section class="uj-section uj-cta">
            <div class="uj-container uj-cta-inner">
                <div class="uj-cta-text">
                    <?php echo $this->badge($o['cta_badge']); ?>
                    <h2><?php echo esc_html($o['cta_title']); ?></h2>
                    <p><?php echo esc_html($o['cta_text']); ?></p>
                </div>
                <div class="uj-cta-actions">
                    <?php if (!empty($o['cta_button_1_text'])): ?><a class="uj-btn uj-btn-primary" href="<?php echo esc_url($o['cta_button_1_link']); ?>"><?php echo esc_html($o['cta_button_1_text']); ?></a><?php endif; ?>
                    <?php if (!empty($o['cta_button_2_text'])): ?><a class="uj-btn uj-btn-secondary" href="<?php echo esc_url($o['cta_button_2_link']); ?>"><?php echo esc_html($o['cta_button_2_text']); ?></a><?php endif; ?>
                </div>
            </div>
        </section>
        <?php return ob_get_clean();
    }

    private function render_events($o) {
        ob_start(); ?>
        <section class="uj-section uj-events">
            <div class="uj-container">
                <div class="uj-section-head">
                    <?php echo $this->badge($o['events_badge']); ?>
                    <h2><?php echo esc_html($o['events_title']); ?></h2>
                    <p><?php echo esc_html($o['events_text']); ?></p>
                </div>
                <div class="uj-grid uj-grid-3col">
                    <?php for ($i=1; $i<=3; $i++): ?>
                        <article class="uj-card-item uj-media-card">
                            <?php if (!empty($o['events_'.$i.'_image'])): ?>
                                <div class="uj-card-image"><img src="<?php echo esc_url($o['events_'.$i.'_image']); ?>" alt="<?php echo esc_attr($o['events_'.$i.'_title']); ?>"></div>
                            <?php endif; ?>
                            <div class="uj-card-body">
                                <div class="uj-meta"><?php echo esc_html($o['events_'.$i.'_meta']); ?></div>
                                <h3><?php echo esc_html($o['events_'.$i.'_title']); ?></h3>
                                <p><?php echo esc_html($o['events_'.$i.'_text']); ?></p>
                                <?php if (!empty($o['events_'.$i.'_link'])): ?><a class="uj-btn uj-btn-card" href="<?php echo esc_url($o['events_'.$i.'_link']); ?>"><?php echo esc_html($o['events_'.$i.'_button']); ?></a><?php endif; ?>
                            </div>
                        </article>
                    <?php endfor; ?>
                </div>
            </div>
        </section>
        <?php return ob_get_clean();
    }

    private function render_news($o) {
        ob_start(); ?>
        <section class="uj-section uj-news">
            <div class="uj-container">
                <div class="uj-section-head">
                    <?php echo $this->badge($o['news_badge']); ?>
                    <h2><?php echo esc_html($o['news_title']); ?></h2>
                    <p><?php echo esc_html($o['news_text']); ?></p>
                </div>
                <div class="uj-grid uj-grid-3col">
                    <?php for ($i=1; $i<=3; $i++): ?>
                        <article class="uj-card-item uj-news-card">
                            <?php if (!empty($o['news_'.$i.'_image'])): ?>
                                <div class="uj-card-image"><img src="<?php echo esc_url($o['news_'.$i.'_image']); ?>" alt="<?php echo esc_attr($o['news_'.$i.'_title']); ?>"></div>
                            <?php endif; ?>
                            <div class="uj-card-body">
                                <div class="uj-date"><?php echo esc_html($o['news_'.$i.'_date']); ?></div>
                                <h3><?php echo esc_html($o['news_'.$i.'_title']); ?></h3>
                                <p><?php echo esc_html($o['news_'.$i.'_text']); ?></p>
                                <?php if (!empty($o['news_'.$i.'_link'])): ?><a class="uj-btn uj-btn-card" href="<?php echo esc_url($o['news_'.$i.'_link']); ?>"><?php echo esc_html($o['news_'.$i.'_button']); ?></a><?php endif; ?>
                            </div>
                        </article>
                    <?php endfor; ?>
                </div>
            </div>
        </section>
        <?php return ob_get_clean();
    }

    private function frontend_css() {
        return '
        .uj-home-sections{--uj-pink:#ff3692;--uj-green:#00c867;--uj-blue:#001738;--uj-white:#ffffff;--uj-text:#334155;--uj-light:#f8fafc;--uj-soft-pink:#fff1f7;--uj-soft-green:#ecfff5}
        .uj-home-sections *{box-sizing:border-box}
        .uj-home-sections .uj-container{width:min(1200px,92%);margin:0 auto}
        .uj-home-sections .uj-section{padding:96px 0;position:relative}
        .uj-home-sections .uj-section:before{content:"";position:absolute;inset:auto auto 24px 4%;width:110px;height:110px;background:radial-gradient(circle,var(--uj-soft-pink) 0%, rgba(255,255,255,0) 72%);pointer-events:none}
        .uj-home-sections .uj-section-head{text-align:center;max-width:860px;margin:0 auto 48px}
        .uj-home-sections .uj-section-head h2{font-size:clamp(30px,4vw,48px);line-height:1.12;margin:16px 0 12px;color:var(--uj-blue);font-weight:800}
        .uj-home-sections .uj-section-head p{font-size:16px;line-height:1.8;color:var(--uj-text);margin:0 auto}
        .uj-home-sections .uj-badge{display:inline-flex;justify-content:center;align-items:center;min-width:165px;height:58px;padding:0 28px;border:2px solid var(--uj-pink);border-radius:999px;position:relative;background:rgba(255,255,255,.86);backdrop-filter:blur(4px)}
        .uj-home-sections .uj-badge:before{content:"";position:absolute;inset:-5px auto auto -8px;width:calc(100% + 16px);height:calc(100% + 10px);border:2px solid var(--uj-green);border-radius:999px;transform:rotate(-5deg);opacity:.95}
        .uj-home-sections .uj-badge:after{content:"";position:absolute;right:22px;bottom:-9px;width:16px;height:16px;border-right:2px solid var(--uj-pink);border-bottom:2px solid var(--uj-pink);background:#fff;transform:rotate(45deg)}
        .uj-home-sections .uj-badge span{position:relative;z-index:1;font-size:13px;font-weight:800;letter-spacing:.04em;color:var(--uj-blue);text-transform:uppercase}
        .uj-home-sections .uj-grid{display:grid;gap:28px}
        .uj-home-sections .uj-grid-3col{grid-template-columns:repeat(3,minmax(0,1fr))}
        .uj-home-sections .uj-card-item{background:#fff;border:1px solid rgba(0,23,56,.07);border-radius:24px;overflow:hidden;box-shadow:0 18px 40px rgba(0,23,56,.08);height:100%;display:flex;flex-direction:column;transition:transform .35s ease, box-shadow .35s ease;border-top:4px solid var(--uj-green)}
        .uj-home-sections .uj-card-item:hover{transform:translateY(-8px);box-shadow:0 26px 54px rgba(0,23,56,.14)}
        .uj-home-sections .uj-card-image{aspect-ratio:16/10;background:#eef2f7;overflow:hidden;position:relative}
        .uj-home-sections .uj-card-image:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,0) 55%, rgba(0,23,56,.18) 100%)}
        .uj-home-sections .uj-card-image img{width:100%;height:100%;object-fit:cover;display:block;transform:scale(1.02);transition:transform .4s ease}
        .uj-home-sections .uj-card-item:hover .uj-card-image img{transform:scale(1.07)}
        .uj-home-sections .uj-card-body{padding:26px;display:flex;flex-direction:column;flex:1}
        .uj-home-sections .uj-card-body h3{margin:0 0 10px;color:var(--uj-blue);font-size:24px;line-height:1.25}
        .uj-home-sections .uj-card-body p{margin:0;color:var(--uj-text);line-height:1.75;font-size:15px}
        .uj-home-sections .uj-impact{background:linear-gradient(180deg,#ffffff 0%, #f8fcff 100%)}
        .uj-home-sections .uj-events{background:linear-gradient(180deg,#f8fafc 0%, #ffffff 100%)}
        .uj-home-sections .uj-news{background:#fff}
        .uj-home-sections .uj-project-card:nth-child(2){margin-top:28px}
        .uj-home-sections .uj-project-card:nth-child(3){margin-top:56px}
        .uj-home-sections .uj-cta{background:linear-gradient(135deg,var(--uj-blue) 0%, #03224f 70%, #0a315e 100%);position:relative;overflow:hidden}
        .uj-home-sections .uj-cta:before{content:"";position:absolute;width:320px;height:320px;border-radius:50%;background:rgba(0,200,103,.12);right:-80px;top:-90px}
        .uj-home-sections .uj-cta:after{content:"";position:absolute;left:-60px;bottom:-80px;width:260px;height:260px;border-radius:50%;background:rgba(255,54,146,.12)}
        .uj-home-sections .uj-cta-inner{display:flex;align-items:center;justify-content:space-between;gap:30px;position:relative;z-index:1}
        .uj-home-sections .uj-cta .uj-badge span,.uj-home-sections .uj-cta h2,.uj-home-sections .uj-cta p{color:#fff}
        .uj-home-sections .uj-cta .uj-badge:after{background:var(--uj-blue)}
        .uj-home-sections .uj-cta h2{font-size:clamp(28px,4vw,46px);margin:16px 0 12px;line-height:1.15}
        .uj-home-sections .uj-cta p{max-width:720px;line-height:1.8}
        .uj-home-sections .uj-cta-actions{display:flex;gap:14px;flex-wrap:wrap}
        .uj-home-sections .uj-btn{display:inline-flex;align-items:center;justify-content:center;padding:16px 26px;border-radius:10px;text-decoration:none;font-weight:800;transition:all .3s ease;border:2px solid transparent;box-shadow:none}
        .uj-home-sections .uj-btn-primary{background:var(--uj-pink);color:#fff}
        .uj-home-sections .uj-btn-primary:hover{background:#18b760;color:#fff;transform:translateY(-2px)}
        .uj-home-sections .uj-btn-secondary{background:transparent;color:#fff;border-color:#fff}
        .uj-home-sections .uj-btn-secondary:hover{background:#18b760;border-color:#18b760;color:#fff;transform:translateY(-2px)}
        .uj-home-sections .uj-btn-card{margin-top:18px;background:var(--uj-pink);color:#fff;align-self:flex-start;padding:13px 20px;border-radius:10px}
        .uj-home-sections .uj-btn-card:hover{background:#18b760;color:#fff;transform:translateY(-2px)}
        .uj-home-sections .uj-meta,.uj-home-sections .uj-date{display:inline-flex;margin:0 0 12px;padding:7px 12px;border-radius:999px;background:#eefcf5;color:#18b760;font-weight:700;font-size:13px}
        .uj-home-sections .uj-news-card .uj-date{background:var(--uj-soft-pink);color:var(--uj-pink)}
        @media (max-width: 991px){
            .uj-home-sections .uj-grid-3col{grid-template-columns:1fr 1fr}
            .uj-home-sections .uj-cta-inner{flex-direction:column;align-items:flex-start}
            .uj-home-sections .uj-project-card:nth-child(2), .uj-home-sections .uj-project-card:nth-child(3){margin-top:0}
        }
        @media (max-width: 680px){
            .uj-home-sections .uj-section{padding:72px 0}
            .uj-home-sections .uj-grid-3col{grid-template-columns:1fr}
            .uj-home-sections .uj-card-body h3{font-size:22px}
            .uj-home-sections .uj-badge{min-width:150px;height:54px}
            .uj-home-sections .uj-btn, .uj-home-sections .uj-btn-card{width:100%}
        }
        ';
    }
}

new UJ_Home_Sections_Plugin();
