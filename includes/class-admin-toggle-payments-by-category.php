<?php

defined('ABSPATH') || exit;

class Toggle_Payments_By_Category_Admin
{

    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_post_tpbc_update_settings', array($this, 'update_settings'));
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_style('tpbc_admin_style', TPBC_PLUGIN_URL . 'assets/css/admin.css', array(), TPBC_VERSION);
        wp_enqueue_script('tpbc_admin_scripts', TPBC_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), TPBC_VERSION, true);
    }




    public function admin_menu()
    {
        add_menu_page(
            'Toggle Payments by Category',
            'Toggle Payments',
            'manage_options',
            'toggle-payments-by-category',
            array($this, 'admin_page'),
            'dashicons-info',
            '58'

        );
    }


    public function admin_page() {

        ?>
        <div class="wrap">
            <h2>Toggle Payments by Category</h2>
            <button type="button" class="add-new-button">Add New</button>
            <form class="product-catalog-mode-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php
                // Fetch product categories
                $categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => false,
                ));
                $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
                ?>
                <div class="form-container">
                    <table id="payment-table" class="form-table">
                        <thead>
                        <tr>
                            <th>Category</th>
                            <th>Payment Method</th>
                            <th>Payment Visibility</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        <!-- Template Row (Hidden) -->
                        <tr class="template-row" style="display:none;">
                            <td>
                                <select name="tpbc_category[]">
                                    <?php foreach ($categories as $category) { ?>
                                        <option value="<?php echo esc_attr($category->term_id); ?>">
                                            <?php echo esc_html($category->name); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <select name="payment_method[]">
                                    <?php foreach ($available_gateways as $gateway) { ?>
                                        <option value="<?php echo esc_attr($gateway->id); ?>">
                                            <?php echo esc_html($gateway->get_title()); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <select name="payment_visibility[]">
                                    <option value="show">Show Payment</option>
                                    <option value="hide">Hide Payment</option>
                                </select>
                            </td>
                            <td>
                                <button type="button" class="delete-button">Delete</button>
                            </td>
                        </tr>
                        <!-- Existing Settings Rows (if any) -->
                        <?php
                        $saved_settings = get_option('tpbc_payment_settings', array());
                        if (!empty($saved_settings)) {
                            foreach ($saved_settings as $setting) {
                                ?>
                                <tr>
                                    <td>
                                        <select name="tpbc_category[]">
                                            <?php foreach ($categories as $category) { ?>
                                                <option value="<?php echo esc_attr($category->term_id); ?>"
                                                    <?php selected($category->term_id, $setting['category']); ?>>
                                                    <?php echo esc_html($category->name); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="payment_method[]">
                                            <?php foreach ($available_gateways as $gateway) { ?>
                                                <option value="<?php echo esc_attr($gateway->id); ?>"
                                                    <?php selected($gateway->id, $setting['method']); ?>>
                                                    <?php echo esc_html($gateway->get_title()); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="payment_visibility[]">
                                            <option value="show" <?php selected('show', $setting['visibility']); ?>>Show Payment</option>
                                            <option value="hide" <?php selected('hide', $setting['visibility']); ?>>Hide Payment</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="delete-button">Delete</button>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>

                    </table>

                    <!-- Save Button (or any additional controls) -->
                    <div class="save-container">
                        <?php wp_nonce_field('tpbc_update_settings'); ?>
                        <input type="hidden" name="action" value="tpbc_update_settings">
                        <?php submit_button(__('Save Settings', 'toggle-payment-by-category')); ?>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }





    public function update_settings() {
        if (!check_admin_referer('tpbc_update_settings')) {
            wp_die(esc_html__('Security check failed', 'toggle-payment-by-category'));
        }
        // Check if arrays are set
        $categories = isset($_POST['tpbc_category']) ? array_map('sanitize_text_field', $_POST['tpbc_category']) : array();
        $methods = isset($_POST['payment_method']) ? array_map('sanitize_text_field', $_POST['payment_method']) : array();
        $visibilities = isset($_POST['payment_visibility']) ? array_map('sanitize_text_field', $_POST['payment_visibility']) : array();
        $payment_settings = array();
        // Loop through the arrays to create an array of settings
        for ($i = 0; $i < count($categories); $i++) {
            $payment_settings[] = array(
                'category' => isset($categories[$i]) ? $categories[$i] : '',
                'method' => isset($methods[$i]) ? $methods[$i] : '',
                'visibility' => isset($visibilities[$i]) ? $visibilities[$i] : ''
            );
        }
        // Save the array of settings in the options table
        update_option('tpbc_payment_settings', $payment_settings);
        // Redirect back to the referrer
        wp_safe_redirect(wp_get_referer());
        exit;
    }





}