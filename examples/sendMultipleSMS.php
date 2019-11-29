<?php


// Send Multiple SMS
// ----------------------


require_once('../autoload.php');

$apiClient = new SMSGatewayApi(AUTH_KEY, SERVER);


try {

    $mobile_numbers = array(
        '01737346122',
        '01303595747',
    );

    $response = $apiClient->sendMultipleSMS($mobile_numbers, 'Hi Mike, This is a test messsage', 'ARS-L22', 2, 'now');

    print_r($response);

} catch (Exception $e) {
    
    echo $e->getMessage();
}



/*


Output
---------


Array
(
    [status] => Success
    [msg] => 2 SMS send to queue for precessing
    [data] => Array
        (
            [0] => Array
                (
                    [schedule_at] => 2019-11-29 21:28:45
                    [queue_id] => 15750413253ec1
                    [device_model] => ARS-L22
                    [sim_id] => 2
                    [mobile_no] => 01737346122
                    [message] => Hi [contact_name] from  [site_name] at [common_date_time]
                    [created_at] => 2019-11-29 21:28:45
                )

            [1] => Array
                (
                    [schedule_at] => 2019-11-29 21:28:45
                    [queue_id] => 15750413250101
                    [device_model] => ARS-L22
                    [sim_id] => 2
                    [mobile_no] => 01303595747
                    [message] => Hi [contact_name] from  [site_name] at [common_date_time]
                    [created_at] => 2019-11-29 21:28:45
                )

        )

    [total] => 2
)

*/