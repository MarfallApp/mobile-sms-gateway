<?php
/**
 * @package		Mobile SMS Gateway Api
 * @author		Najmul Hossaiin (contact@ntechpark.com)
 * @copyright	Copyright (c) 2019, nTechpark Technologies Ltd.
 * @link		http://ntechpark.com
*/


class SMSGatewayApi {

	private $authKey;

	private $server;

	public function __construct($authKey, $server)
	{
		$this->authKey = $authKey;
		$this->server = $server;
	}

	/**
	 * @param string     $mobile_no  The mobile number where you want to send message.
	 * @param string     $message The message you want to send.
	 * @param string     $device_model The model of the device you want to use to send this message.
	 * @param int 		 $sim_id  The id  of the sim you want to use to send this message.
	 *
	 * @return array     Returns The array containing information about the message.
	 * @throws Exception If there is an error while sending a message.
	 */
	public function sendSMS($mobile_no, $message, $device_model, $sim_id = 1)
	{
	    $url = SERVER . "/sms/send";
	    $postData = array('mobile_no' => $mobile_no, 'message' => $message, 'device_model' => $device_model, 'sim_id' => $sim_id);
	    return $this->sendRequest($url, $postData);
	}

	/**
	 * @param array         $mobile_numbers  The mobile number where you want to send message.
	 * @param string        $message The message you want to send.
	 * @param string        $device_model The model of the device you want to use to send this message.
	 * @param int    		$sim_id  The id  of the sim you want to use to send this message.
	 * @param string        $send_at | tomorrow | after_5_days | 30 | 60 | 120 | 300 | 1800 | 3600 | 18000
	 *
	 * @return array     Returns The array containing information about the message.
	 * @throws Exception If there is an error while sending a message.
	 */
	public function sendMultipleSMS($mobile_numbers, $message, $device_model, $sim_id = 1, $send_at = 'now')
	{
	    $url = SERVER . "/sms/sendMultipleSms";
	    $postData = array('mobile_numbers' => $mobile_numbers, 'message' => $message, 'device_model' => $device_model, 'sim_id' => $sim_id, 'send_at' => $send_at);
	    return $this->sendRequest($url, $postData);
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
	public function getSmsInQueue($filter_data) {
	    $url = SERVER . "/sms/getSmsInQueue";
	    return $this->sendRequest($url, $filter_data);
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
	public function getHistory($filter_data) {
	    $url = SERVER . "/sms/getHistory";
	    return $this->sendRequest($url, $filter_data);
	}

	/**
	 * @param string $url The url of request destination
	 * @param array $postData The array of post data
	 * @return mixed     The result containing mixed data
	 * @throws Exception If there is an error while sending request.
	 */
	public function sendRequest($url, $postData)
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
}