<?php
/**
 * Templateberg Connect to Gutentor
 *
 * @since 1.0.0
 */

if (! class_exists('Templateberg_Gutentor_Connect')) {
    /**
     * Class Templateberg_Gutentor_Connect.
     */
    class Templateberg_Gutentor_Connect
    {

        /**
         * Main Templateberg_Gutentor_Connect Instance
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         * @return object $instance Templateberg_Gutentor_Connect Instance
         */
        public static function instance()
        {

            // Store the instance locally to avoid private static replication
            static $instance = null;

            // Only run these methods if they haven't been ran previously
            if (null === $instance) {
                $instance              = new Templateberg_Gutentor_Connect();
            }

            // Always return the instance
            return $instance;
        }

        /**
         * Add Gutentor args
         *
         * @since 1.1.4
         */
        public function add_args($args)
        {

            if ($this->has_valid_license()) {
                $args['gutentor'] = array(
                    'license' =>gutentor_pro_license_init()->get_license_key(),
                    'url' =>home_url(),
                    'environment' => function_exists('wp_get_environment_type') ?
                        wp_get_environment_type() : 'production',
                );
            }
            return $args;
        }

        /**
         * If gutentor pro license is valid
         *
         * @since 1.1.4
         *
         * return bool
         */
        public function has_valid_license()
        {

            $license_data = templateberg_gutentor_connect()->get_license_info();
            if (!$license_data || !isset($license_data->license) || 'valid' !== $license_data->license) {
                return false;
            }

            return true;
        }

        /**
         * If gutentor pro license info
         *
         * @since 1.1.4
         *
         * return bool||object
         */
        public static function get_license_info()
        {
            // Store the $license_data locally
            static $license_data = null;

            // Only set the license they haven't been set previously
            if (null === $license_data) {
                if (!function_exists('gutentor_pro_license_init') ||
                    !function_exists('gutentor_pro_edd_plugin_installer')) {
                    $license_data = false;
                } else {
                    if (!gutentor_pro_license_init()->menu_slug) {
                        gutentor_pro_license_init()->run();
                    }
                    $license_data = gutentor_pro_edd_plugin_installer()->check_license();
                }
            }

            // Always return the $license_data
            return $license_data;
        }

        /**
         * Get Gutentor Remaining Template Kits
         *
         * @since 1.1.4
         */
        public function get_total_template_kits()
        {

            $license_data =  templateberg_gutentor_connect()->get_license_info();

            if (!$license_data || !isset($license_data->license) || 'valid' !== $license_data->license) {
                return false;
            }

            if (1 === absint($license_data->license_limit)) {
                return 12;
            } elseif (5 === absint($license_data->license_limit)) {
                return 33;
            } elseif (25 === absint($license_data->license_limit)) {
                return 55;
            } else {
                return -1;
            }
        }

        /**
         * Get Gutentor Remaining Template Kits
         *
         * @since 1.1.4
         */
        public function get_imported_template_kits()
        {
            if (!metadata_exists('user', get_current_user_id(), 'templateberg-gutentor-imd-tmls')) {
                return array();
            }
            $imported =  get_user_meta(get_current_user_id(), 'templateberg-gutentor-imd-tmls', true);
            $imported =  json_decode($imported, true);
            return array_unique($imported);
        }

        /**
         * Set Gutentor Imported Template Kits
         *
         * @since 1.1.4
         */
        public function add_imported_template_kits($gutentor_info)
        {
            if (!isset($gutentor_info['template_kits_used']) ||
                !is_array($gutentor_info['template_kits_used']) ||
                empty($gutentor_info['template_kits_used'])
            ) {
                return;
            }
            update_user_meta(
                get_current_user_id(),
                'templateberg-gutentor-imd-tmls',
                wp_json_encode(array_unique($gutentor_info))
            );
        }

        /**
         * Get remaining tempalte kits.
         *
         * @since 1.1.4
         */
        function get_remaining_template_kits()
        {
            $total_num = templateberg_gutentor_connect()->get_total_template_kits();
            $used_template_kits = templateberg_gutentor_connect()->get_imported_template_kits();
            $used_num = count($used_template_kits);

            return $total_num - $used_num;
        }
    }
}

/**
 * Begins execution of the hooks.
 *
 * @since    1.0.0
 */
function templateberg_gutentor_connect()
{
    return Templateberg_Gutentor_Connect::instance();
}
