<?php

/*

Plugin Name: Loyalty Customer Sync

Description: Plugin to sync customer group from Loyalty Lion and Big Commerce

Version: 1.0

Author: Nam Thanh

*/

use Bigcommerce\Api\Client;

class Loyalty_Customer_Sync {
    const ROLE_MAPPING_LL_TO_WP = [
        'Level 1' => 'level1',
        'Level 2' => 'level2',
        'Level 3' => 'level3',
        'Level 4' => 'level4'
    ];

    const CUSTOMER_GROUP_MAPPING_LL_TO_BC = [
        'Level 1' => '8',
        'Level 2' => '9',
        'Level 3' => '10',
        'Level 4' => '11'

    ];

    const BIGCOMMERCE_STORE_ID = '67f4785fo8';
    const BIGCOMMERCE_STORE_TOKEN = 'rm9fxj4i427zli70o7v5jesx49fk540';

    /**
     * Hook in the basic early actions
     *
     * @since 1.1.0
     */
    public function __construct() {
        add_role('level1', 'Level 1');
        add_role('level2', 'Level 2');
        add_role('level3', 'Level 3');
        add_role('level4', 'Level 4');

        add_action( 'rest_api_init', function () {
            register_rest_route( 'loyalty/v2', '/updatecustomer', array(
                'methods' => 'POST',
                'callback' => array($this, 'get_webhook_loyalty_response')
            ) );
        } );

    }

    /**
     * EXECUTE FUNCTION FOR WEBHOOK
     *
     * @param $response
     * @return WP_REST_Response
     */
    public function get_webhook_loyalty_response($response) {
        try {
            $customerEmail = $response['payload']['customer']['email'];
            $loyaltyGroup = $response['payload']['customer']['loyalty_tier_membership']['loyalty_tier']['name'];
            $user = get_user_by('email', $customerEmail);

            error_log("$customerEmail", 3, "/nas/content/live/ogtransfer1/wp-content/wphb-logs/wordpress2.log");
            if ($user) {
                if(!in_array("administrator", $user->roles)) {
                    $user->set_role(self::ROLE_MAPPING_LL_TO_WP[$loyaltyGroup]);
                }
                $bcCustomerId = $this->getCustomerIdFromBC($customerEmail);
                if ($bcCustomerId) {
                    $this->updateCustomerGroup($bcCustomerId, self::CUSTOMER_GROUP_MAPPING_LL_TO_BC[$loyaltyGroup]);
                }
            }
        } catch ( Exception $e ) {
            $this->write_log($e->getMessage());
            $this->write_log($e->getTraceAsString());
        }

        $message = __( 'Entry updated successfully', 'gravityforms' );

        return new WP_REST_Response( $message, 200 );
    }

    /**
     * SEND API TO BIG-COMMERCE TO UPDATE CUSTOMER GROUP ID
     *
     * @param $bcCustomerId
     * @param $loyaltyGroup
     * @return WP_REST_Response
     */
    public function updateCustomerGroup($bcCustomerId, $loyaltyGroup)
    {
        $curl = curl_init();

        //$this->write_log('updateCustomertesting: '.$bcCustomerId);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.bigcommerce.com/stores/" . self::BIGCOMMERCE_STORE_ID . "/v3/customers",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => '[{"customer_group_id": ' . $loyaltyGroup . ',"id":' . $bcCustomerId .'}]',
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                "x-auth-token: " . self::BIGCOMMERCE_STORE_TOKEN
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $this->write_log('updateCustomerGroup error: '.$err);
        }
    }

    /**
     * GET CUSTOMER ID FROM BIG-COMMERCE TO SEND UPDATE API
     *
     * @param $customerEmail
     * @return null
     */
    public function getCustomerIdFromBC($customerEmail)
    {
        $curl = curl_init();
        $query = '?email%3Ain=' . $customerEmail .'&limit=1&page=1';
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.bigcommerce.com/stores/" . self::BIGCOMMERCE_STORE_ID . "/v3/customers" . $query,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "x-auth-token: " . self::BIGCOMMERCE_STORE_TOKEN
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $this->write_log('$err: '.$err);
            return null;
        } else {
            $data = json_decode($response)->data;

            return $data ? $data[0]->id : null;
        }
    }

    /**
     * WRITE LOG IN DEBUG.LOG
     *
     * @param $log
     */
    public function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}
new Loyalty_Customer_Sync();

