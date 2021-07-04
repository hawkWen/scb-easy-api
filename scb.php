<?php
$account_number= "0987654321"; 
$pincode= "123456"; 
$deviceId= "6e45d592-d9aa-41b6-a6d0-95dc17b40515";
$scb = new api_scb($account_number, $pincode, $deviceId);
// echo $scb->balance();
// print_r($scb->balance());
// echo $scb->verify("0987654321", "004");
// echo $scb->tranfer("0987654321", "004", "1");
// echo $scb->transaction();


class api_scb 
{
    private $account_number, $pincode, $deviceId;
    private $api = "http://api.scb-easy.com/preloadandresumecheck";
    public function __construct($account_number, $pincode, $deviceId)
    {
        if (empty($account_number) || empty($pincode) || empty($deviceId)) {
            return $this->alert_msg("error", "ข้อมูลไม่ครบ!");
        }
        date_default_timezone_set("Asia/Bangkok");
        $this->account_number = $account_number;
        $this->pincode = $pincode;
        $this->deviceId = $deviceId;
    }

    private function Curl($method, $url, $header, $data, $cookie, $http = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HEADER, $http);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if ($cookie) {
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        }
        $result = curl_exec($ch);
        return $result;
    }

    private function alert_msg($status, $msg)
    {
        return json_encode(array(
            "status" => $status,
            "msg" => $msg,
        ), JSON_UNESCAPED_UNICODE);
    }

    public function balance()
    {
        $header = array(
            'cache-control: no-cache'
        );
        $data = array(
            "action"=>"balance",
            "account_number"=>$this->account_number,
            "pincode"=>$this->pincode,
            "deviceId"=>$this->deviceId,
        );
        $check = $this->Curl("POST", $this->api, $header, $data, false);
        $totalAvailableBalance = $check;
        return $totalAvailableBalance;
    }

    public function transaction()
    {
        $header = array(
            'cache-control: no-cache'
        );
        $data = array(
            "action"=>"transaction",
            "account_number"=>$this->account_number,
            "pincode"=>$this->pincode,
            "deviceId"=>$this->deviceId,
        );
        $check = $this->Curl("POST", $this->api, $header, $data, false);
        return $check;
    }

    public function verify($banknumber, $bankcode)
    {
        $banknumber = $banknumber;
        $bankcode = $bankcode;
        $header = array(
            'cache-control: no-cache'
        );
        $data = array(
            "action"=>"verify",
            "account_number"=>$this->account_number,
            "pincode"=>$this->pincode,
            "deviceId"=>$this->deviceId,
            "verifybanknumber"=>$banknumber,
            "verifybankcode"=>$bankcode,
        );
        $check = $this->Curl("POST", $this->api, $header, $data, false);
        return $check;
    }

    public function tranfer($banknumber, $bankcode, $Amount)
    {
        // $banknumber = "3311232289";
        // $bankcode = "025";
        $banknumber = $banknumber;
        $bankcode = $bankcode;
        // $Amount = "0.1";
        $Amount = $Amount;

        $Amount = number_format($Amount, 2, '.', '');

        $header = array(
            'cache-control: no-cache'
        );
        $data = array(
            "action"=>"tranfer",
            "account_number"=>$this->account_number,
            "pincode"=>$this->pincode,
            "deviceId"=>$this->deviceId,
            "verifybanknumber"=>$banknumber,
            "verifybankcode"=>$bankcode,
            "amount"=>$Amount
        );
        $check = $this->Curl("POST", $this->api, $header, $data, false);
        return $check;
    }
}



?>