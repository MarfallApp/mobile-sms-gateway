<?php
define('AUTH_KEY', 'qQR1dW8YTOcV5izxboXNL2AGCU9GH4i0ayacy3nd3');
define('SERVER', 'https://sms.ntechpark.com/10/admin/index.php?auth_key='.AUTH_KEY.'&route=api');

/**
 * @param string     $mobile_no  The mobile number where you want to send message.
 * @param string     $message The message you want to send.
 * @param string     $device_model The model of the device you want to use to send this message.
 * @param int|string $sim_id  The id  of the sim you want to use to send this message.
 *
 * @return array     Returns The array containing information about the message.
 * @throws Exception If there is an error while sending a message.
 */
function sendSMS($mobile_no, $message, $device_model, $sim_id = 1)
{
    $url = SERVER . "/sms/send";
    $postData = array('mobile_no' => $mobile_no, 'message' => $message, 'device_model' => $device_model, 'sim_id' => $sim_id);
    return sendRequest($url, $postData);
}

/**
 * @param array         $mobile_numbers  The mobile number where you want to send message.
 * @param string        $message The message you want to send.
 * @param string        $device_model The model of the device you want to use to send this message.
 * @param int|string    $sim_id  The id  of the sim you want to use to send this message.
 * @param string        now | tomorrow | after_5_days | 30 | 60 | 120 | 300 | 1800 | 3600 | 18000
 *
 * @return array     Returns The array containing information about the message.
 * @throws Exception If there is an error while sending a message.
 */
function sendMultipleSMS($mobile_numbers, $message, $device_model, $sim_id = 1, $send_at = 'now')
{
    $url = SERVER . "/sms/sendMultipleSms";
    $postData = array('mobile_numbers' => $mobile_numbers, 'message' => $message, 'device_model' => $device_model, 'sim_id' => $sim_id, 'send_at' => $send_at);
    return sendRequest($url, $postData);
}

/**
 * @param int $filter_data The data for filtering sms in queue you want to retrieve.
 *
 * @return array     The array containing a message.
 * @throws Exception If there is an error while getting a message.
 *
 *
 * Example of $filter_data
 *
 * $data = array(
 *  'filterby_id' => $id,
 *  'filterby_queue_id' => $queue_id,
 *  'filterby_mobile_no' => $mobile_no,
 *  'filterby_device' => $device_model,
 *  'filterby_sim_id' => $sim_id,
 *  'filterby_status' => $status,
 *  'filterby_schedule_from' => '2019-11-11 00:00:00',
 *  'filterby_schedule_to' => '2019-11-12 00:00:00',
 *  'filterby_from' => '2019-11-11 00:00:00',
 *  'filterby_to' => '2019-11-12 00:00:00',
 *  'limit' => $limit,
 * );
 */
function getSmsInQueue($filter_data) {
    $url = SERVER . "/sms/getSmsInQueue";
    return sendRequest($url, $filter_data);
}

/**
 * @param int $filter_data The data for filtering sms history you want to retrieve.
 *
 * @return array     The array containing a message.
 * @throws Exception If there is an error while getting a message.
 *
 *
 * Example of $filter_data
 *
 * $data = array(
 *  'filterby_id' => $id,
 *  'filterby_status' => $status,
 *  'filterby_device' => $device_model,
 *  'filterby_sim_id' => $sim_id,
 *  'filterby_mobile_no' => $mobile_no,
 *  'filterby_from' => '2019-11-11 00:00:00',
 *  'filterby_to' => '2019-11-12 00:00:00',
 *  'limit' => $limit,
 * );
 */
function getHistory($filter_data) {
    $url = SERVER . "/sms/getHistory";
    return sendRequest($url, $filter_data);
}

/**
 * @param string $url The url of request destination
 * @param array $postData The array of post data
 * @return mixed     The result containing mixed data
 * @throws Exception If there is an error while sending request.
 */
function sendRequest($url, $postData)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }
    curl_close($ch);
    if ($httpCode == 200) {
        $json = json_decode($response, true);
        if ($json == false) {
            if (empty($response)) {
                throw new Exception("Missing data in request. Please provide all the required information to send messages.");
            } else {
                throw new Exception($response);
            }
        } else {
            if ($json["status"] == 'Success') {
                return $json;
            } else {
                throw new Exception($json["errorMsg"]);
            }
        }
    } else {
        throw new Exception("HTTP Error Code : {$httpCode}");
    }
}





// Send Single SMS
// try {

//     $response = sendSMS('01737346122', 'This SMS from API at localhost', 'ARS-L22', 2);
//     print_r($response);

// } catch (Exception $e) {
    
//     echo $e->getMessage();
// }



// Output

/*
Array
(
    [status] => Success
    [data] => Array
        (
            [0] => Array
                (
                    [response] => Array
                        (
                            [multicast_id] => 9.1266008746554E+17
                            [success] => 1
                            [failure] => 0
                            [canonical_ids] => 0
                            [results] => Array
                                (
                                    [0] => Array
                                        (
                                            [message_id] => 0:1575039780719480%26645a09f9fd7ecd
                                        )

                                )

                        )

                    [payload] => Array
                        (
                            [username] => admin.sms@ntechpark.com
                            [firebase_access_key] => 
                            [device_model] => ARS-L22
                            [device_token] => 
                            [sim_id] => 2
                            [created_by] => 1
                            [bulk] => yes
                            [message] => This SMS from API at localhost
                            [created_at] => 2019-11-29 21:03:00
                            [mobile_no] => 01737346122
                            [msgID] => 23
                        )

                )

        )

)
*/




// Send Multiple SMS


// try {

//     $mobile_numbers = array(
//         '01737346122',
//         '01303595747',
//     );

//     $response = sendMultipleSMS($mobile_numbers, 'Hi Mike, This is a test messsage', 'ARS-L22', 2, 'now');

//     print_r($response);

// } catch (Exception $e) {
    
//     echo $e->getMessage();
// }



// Output

/*
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





// Get SMS in Queue

try {
    
    $sms_in_queue = getSmsInQueue(array('filterby_device' => 'ARS-L22', 'filterby_from' => '2019-11-29 12:00:00', 'filterby_to' => '2019-11-29 23:59:00'));
    print_r($sms_in_queue);
} catch (Exception $e) {
    
    echo $e->getMessage();
}

// Output

/*
Array
(
    [status] => Success
    [data] => Array
        (
            [0] => Array
                (
                    [id] => 15
                    [schedule_at] => 2019-11-29 23:57:00
                    [queue_id] => 1575050220eee1
                    [mobile_no] => 01737346122
                    [device_model] => ARS-L22
                    [sim_id] => 2
                    [username] => admin.sms@ntechpark.com
                    [message] => Hi Mike, This is a test messsage
                    [process_status] => 0
                    [total_try] => 0
                    [response_text] => 
                    [delivery_status] => pending
                    [created_by] => 1
                    [created_at] => 2019-11-29 23:57:00
                )

        )

)
*/




// // Get SMS History

// try {
    
//     $history = getHistory(array('filterby_device' => 'ARS-L22', 'filterby_from' => '2019-11-29 12:00:00', 'filterby_to' => '2019-11-29 23:59:00'));
//     print_r($history);
// } catch (Exception $e) {
    
//     echo $e->getMessage();
// }

