<?php
require_once(__DIR__.'/config.php');
require_once(__DIR__.'/lib/SMSGatewayApi.php');

$apiClient = new SMSGatewayApi(AUTH_KEY, SERVER);