<?php

class Catchpoint_Pull
{
    protected $_tokenUrl = 'https://io.catchpoint.com/ui/api/';

    protected $_url = 'https://io.catchpoint.com/ui/api/v1/';

    public $key;

    public $secret;

    protected $_token;

    protected $_expires;

    public $override = false;

    public $session;

    public function fetchData($request)
    {
        /*
        echo("<script>console.log('fetchData called');</script>");
        echo("<script>console.log('".$this->_url.$request."');</script>");
        */
        $this->session = new Zend_Session_Namespace('Catchpoint');

        $this->session->setExpirationSeconds(60, 'token');

        if ($this->override == true) {
            unset($this->session->token);
        } else {
            $this->session->token = $this->getToken();
        }

        //echo("<script>console.log('Session Token: ".$this->session->token."');</script>");
        if (!isset($this->session->token) || $this->session->token == '') {
            $this->session->token = $this->getToken();
        //} else {
        // $this->session->token = $this->getToken();
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->_url.$request);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                    'Authorization: Bearer '.$this->session->token,
            ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /*
        curl_setopt($ch, CURLOPT_STDERR, fopen('php://output', 'w+'));
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        */

        $result = curl_exec($ch);

        curl_close($ch);

        $data = json_decode($result);

        if (isset($data->Message)) {
            throw new Exception($data->Message);
        }

        if(!$result) {
            throw new Exception('Error getting data ...');
        }

        return $data;
    }

    private function getToken()
    {
        $config = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $apiKeys = $config->getOption('catchpoint');

        if (!$this->key) {
            $this->key = base64_encode($apiKeys['master']['key']);
            $this->secret = base64_encode($apiKeys['master']['secret']);
        }


        echo("<script>console.log('getToken called');</script>");
        echo("<script>console.log('Key: ".$this->key."');</script>");
        echo("<script>console.log('Secret: ".$this->secret."');</script>");


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_tokenUrl.'token');
        curl_setopt($ch, CURLOPT_POSTFIELDS,
              'grant_type=client_credentials&client_id='.base64_decode($this->key).
              '&client_secret='.base64_decode($this->secret));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /*
        curl_setopt($ch, CURLOPT_STDERR, fopen('php://output', 'w+'));
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        */

        $result = curl_exec($ch);

        curl_close($ch);

        $jsonResponse = json_decode($result);

        if (isset($jsonResponse->Message)) {
          throw new Exception($jsonResponse->Message);
        }

        //echo("<script>console.log('Token: ".base64_encode($jsonResponse->access_token)."');</script>");

        return base64_encode($jsonResponse->access_token);
    }
}
