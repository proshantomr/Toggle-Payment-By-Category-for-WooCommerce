<?php

defined('ABSPATH') || exit;

class Toggle_Payments_By_Category {

    public string $file;
    public string $version;

    public function __construct($file, $version = "1.0.0") {
        $this->file = $file;
        $this->version = $version;
        $this->define_constants();
        $this->inithooks();

        register_activation_hook($file, array($this, 'activate'));
        register_deactivation_hook($file, array($this, 'deactivate'));
    }

    public function define_constants() {
        define('TPBC_VERSION', $this->version);
        define('TPBC_FILE', $this->file);
        define('TPBC_PLUGIN_DIR', plugin_dir_path($this->file));
        define('TPBC_PLUGIN_URL', plugin_dir_url($this->file));
        define('TPBC_PLUGIN_BASENAME', plugin_basename($this->file));
    }

    public function inithooks() {
        add_action('init', array($this, 'init'));
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
        add_filter('woocommerce_available_payment_gateways', array($this, 'checkout_payment'));
    }

    public function activate() {
        // Activation logic here if needed
    }

    public function deactivate() {
        // Deactivation logic here if needed
    }

    public function init() {
        new Toggle_Payments_By_Category_Admin();
    }

    public function load_plugin_textdomain() {
        load_plugin_textdomain('replace-variable-price-with-active-variation', false, basename(dirname(__FILE__)) . '/languages/');
    }

    public function checkout_payment($available_gateways) {

        if (!function_exists('WC') || !WC()->cart) {
            return $available_gateways;
        }

        $payment_settings = get_option('tpbc_payment_settings', array());
        $cart_categories = array();

        // Gather product categories from the cart
        foreach (WC()->cart->get_cart() as $cart_item) {
            $product_id = $cart_item['product_id'];
            $product_categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
            $cart_categories = array_merge($cart_categories, $product_categories);
        }

        $cart_categories = array_unique($cart_categories);

        foreach ($payment_settings as $setting) {
            // Check if the category from settings is in the cart categories
            if (in_array($setting['category'], $cart_categories)) {
                if ($setting['visibility'] === 'hide') {
                    unset($available_gateways[$setting['method']]);
                }
            }
        }

        return $available_gateways;
    }
}
