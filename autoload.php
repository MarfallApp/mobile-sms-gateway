<?php
define('ROOT', __DIR__);
require_once(ROOT.'/config.php');
require_once(ROOT.'/lib/SMSGatewayApi.php');

$apiClient = new SMSGatewayApi(AUTH_KEY, SERVER);