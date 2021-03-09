<?php

$db = null;

$db3 = null;

class tlc {
    // Configuration options
    private $username;
    private $password;
    private $proto;
    private $host;
    private $port;
    private $url;
    private $CACertificate;
    // Information and debugging
    public $status;
    public $error;
    public $raw_response;
    public $response;
    private $id = 0;
    /**
     * @param string $username
     * @param string $password
     * @param string $host
     * @param int $port
     * @param string $proto
     * @param string $url
     */

    function __construct() {
        $this->username      = "tlc1111";
        $this->password      = "asdfqwerzxcv";
        $this->host          = 'localhost';
        $this->port          = 33642;
        $this->url           = null;
        // Set some defaults
        $this->proto         = 'http';
        $this->CACertificate = null;
    }
    /**
     * @param string|null $certificate
     */
    function setSSL($certificate = null) {
        $this->proto         = 'https'; // force HTTPS
        $this->CACertificate = $certificate;
    }
    function __call($method, $params) {
        $this->status       = null;
        $this->error        = null;
        $this->raw_response = null;
        $this->response     = null;
        // If no parameters are passed, this will be an empty array
        $params = array_values($params);
        // The ID should be unique for each call
        $this->id++;
        // Build the request, it's ok that params might have any empty array
        $request = json_encode(array(
            'method' => $method,
            'params' => $params,
            'id'     => $this->id
        ));
        // Build the cURL session
        $curl    = curl_init("{$this->proto}://{$this->host}:{$this->port}/{$this->url}");
        $options = array(
            CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
            CURLOPT_USERPWD        => $this->username . ':' . $this->password,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_HTTPHEADER     => array('Content-type: application/json'),
            CURLOPT_POST           => TRUE,
            CURLOPT_POSTFIELDS     => $request
        );
        // This prevents users from getting the following warning when open_basedir is set:
        // Warning: curl_setopt() [function.curl-setopt]: CURLOPT_FOLLOWLOCATION cannot be activated when in safe_mode or an open_basedir is set
        if (ini_get('open_basedir')) {
            unset($options[CURLOPT_FOLLOWLOCATION]);
        }
        if ($this->proto == 'https') {
            // If the CA Certificate was specified we change CURL to look for it
            if ($this->CACertificate != null) {
                $options[CURLOPT_CAINFO] = $this->CACertificate;
                $options[CURLOPT_CAPATH] = DIRNAME($this->CACertificate);
            }
            else {
                // If not we need to assume the SSL cannot be verified so we set this flag to FALSE to allow the connection
                $options[CURLOPT_SSL_VERIFYPEER] = FALSE;
            }
        }
        curl_setopt_array($curl, $options);
        // Execute the request and decode to an array
        $this->raw_response = curl_exec($curl);
        $this->response     = json_decode($this->raw_response, TRUE);
        // If the status is not 200, something is wrong
        $this->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // If there was no error, this will be an empty string
        $curl_error = curl_error($curl);
        curl_close($curl);
        if (!empty($curl_error)) {
            $this->error = $curl_error;
        }
        if ($this->response['error']) {
            // If bitcoind returned an error, put that in $this->error
            $this->error = $this->response['error']['message'];
        }
        elseif ($this->status != 200) {
            // If bitcoind didn't return a nice error message, we need to make our own
            switch ($this->status) {
                case 400:
                    $this->error = 'HTTP_BAD_REQUEST';
                    break;
                case 401:
                    $this->error = 'HTTP_UNAUTHORIZED';
                    break;
                case 403:
                    $this->error = 'HTTP_FORBIDDEN';
                    break;
                case 404:
                    $this->error = 'HTTP_NOT_FOUND';
                    break;
            }
        }
        if ($this->error) {
            return FALSE;
        }
        return $this->response['result'];
    }
}




$tlc = new tlc();



function refresh_tlc_balance(){ // ��Ʈ���� �ܾ��� �������� �Ѵ�.
	
	global $tlc, $db;	

	if(isset($_SESSION['tlc_username'])) {
		
		$balance = $tlc->getbalance($_SESSION['tlc_username']);
		
		$uid = $_SESSION['tlc_uid'];

		
		$qry = "UPDATE btc_users_addresses SET available_balance_tlc = '$balance' WHERE uid = $uid ";
		$update = $db->query($qry);
			return true;
		}
	else {
			return false;
	}
	
	

}

function send_tlc_internal( $from_username, $to_username , $amount){
		Global $db, $tlc;			



		$result = $tlc->move($from_username, $to_username, $amount);

	

		return "transfer  $amount $from_username to $to_username";


}


function get_address_user_tlc($address){ 
	global $tlc;

	
	
	$account = $tlc->getaccount($address);

	
	
	return $account;

	

}

function get_tlc_balance($address){ // ��Ʈ���� �ܾ��� �������� �Ѵ�.
	global $tlc;

	
	$balance = $tlc->getreceivedbyaddress($address);

	
	
	return $balance;

	

}


function getherALLCoinToAdmin_tlc(){
	global $tlc, $db, $db3;	
	
}





function send_tlc( $from_address, $recipient, $recipient_type , $amount){
	global $tlc, $db;	


				$from_balance =  get_tlc_balance($from_address);

				$transaction_no = "";

				if( $from_balance  < str_replace($amount,",","")  ) { // 잔액체크
					echo "<br>".$amount."<br>";
					echo $from_balance;
					return "not enough balance";
				} else {	

								if ( check_valid_address_tlc($address) ) {
									 $transaction_no = $tlc->sendtoaddress($recipient,(float)$amount);
								
								} else {
									$transaction_no = "coin address is invalid";

								}




							$time = time();
							
							if($transaction_no){
							
								/*
								아래와 같이 보낸이력 저장
								$insert = $db->query("INSERT btc_users_transactions set uid = '$uid', type = 'send_tlc', recipient = '$recipient', 	sender = '$from_username',amount = $amount, time = $time, txid = '$transaction_no' ");
								*/
							}

							return $transaction_no;
				}


}




function Create_tlc_address($username){
		Global $db, $tlc;
		$getnewaddress_tlc  = $tlc->getnewaddress($username);

	//	$qry = "UPDATE btc_users_addresses SET address_tlc = '$getnewaddress_tlc' WHERE label='$username'";
	//	$update = $db->query($qry);

		return $getnewaddress_tlc;
}



function lastest_transaction_tlc(){
	Global $db3;

	$sql = "SELECT txid FROM btc_transaction WHERE cointype='tlc' ORDER BY id DESC LIMIT 1";
		$query = $db3->query($sql);
		if($query->num_rows>0) {
			$row = $query->fetch_assoc();

			return $row['txid'];
		} else {
			return false;
		}

}
function get_tx_skip_tlc(){
	Global $db3;

	$sql = "SELECT count(*) as txid FROM btc_transaction WHERE cointype='tlc'";
		$query = $db3->query($sql);
		
		
		if($query->num_rows>0) {
			$row = $query->fetch_assoc();
			$txid = $row['txid'];
			
			if($txid > 50) { $txid = $txid - 50; $txid = 0; } 
			return $txid;
		} else {
			return false;
		}


}
function update_tlc_transactions2()
{
	Global $db, $tlc;	


$log_text_main .= log_maker ("update start",1,1,3);
						$page = 0;
			
						$limit = 1000;
						$lastest_transaction = lastest_transaction_tlc();
						
						$tx_end = true;
						
						$log_text_main .= log_maker (" Lastest Transaction :  ".$lastest_transaction."<br>",1,1,3);
						while($tx_end){ // DB �� �ִ� �������� ���ö� ���� ��� �ݺ�


						$i = $page * 10;
						
						$transaction_list = $tlc->listtransactions("*",$limit,$i);
						echo "tr_count".count($transaction_list);
							if(count($transaction_list) == 0) {$tx_end = false; break;}

								for($j = count($transaction_list) - 1; $j >= 0; $j--) {
									
									$account = $transaction_list[$j]['account'];
									$address = $transaction_list[$j]['address'];
									$category = $transaction_list[$j]['category'];
									
									if($category == "send") { $account = $tlc->getaccount($recipient); }
									
									$amount = $transaction_list[$j]['amount'];
									$confirmations = $transaction_list[$j]['confirmations'];
									$txid = $transaction_list[$j]['txid'];
									$normtxid = $transaction_list[$j]['normtxid'];
									$tr_time = $transaction_list[$j]['time'];
									$timereceived = $transaction_list[$j]['timereceived'];
									$cointxid = "tlc".$txid;
									$processed = "n";

									if($txid == $lastest_transaction) {
										$log_text_main .= log_maker (" Transaction Finished at the ".$txid ,1,1,1);
										echo " Transaction Finished at the ".$txid;
										$tx_end = false; break;
									} else {
										echo "<br>";
										$inert_result = insert_transaction('tlc',$account,$address,$category,$amount,$confirmations,$txid,$normtxid,$tr_time,$timereceived,$processed);
										$inert_result = $inert_result.$account."_".$cointxid."_".$address."_".$category."_".$amount."_".$confirmations."_".$txid."_".$normtxid."_".$time."_".$timereceived;
										$log_text_main .= log_maker ( $inert_result, 1, 1, 1);
									}
								}
						$page++ ;
						}

}
function update_tlc_transactions()
{
	Global $db, $tlc;	

	
$log_text_main .= log_maker ("update start",1,1,3);
						
			
						$limit = 1000;
						
						$tx_skip = get_tx_skip_tlc();
						$tx_end = true;
						
						
						echo "\n tx skip = ".$tx_skip."\n";
						
						$transaction_list = $tlc->listtransactions("*",$limit, (int) $tx_skip);
						//$transaction_list = $tlc->listtransactions("*",$limit, 4);
						
						echo "tr_count".count($transaction_list);
							
							for($j = 0; $j < count($transaction_list); $j++) {							
								var_dump($transaction_list[$j]);
								echo "<br>";
								$account = $transaction_list[$j]['account'];
								$address = $transaction_list[$j]['address'];
								$category = $transaction_list[$j]['category'];
								$amount = $transaction_list[$j]['amount'];
								$confirmations = $transaction_list[$j]['confirmations'];
								$txid = $transaction_list[$j]['txid'];
								$normtxid = $transaction_list[$j]['normtxid'];
								$tr_time = $transaction_list[$j]['time'];
								$timereceived = $transaction_list[$j]['timereceived'];
								$cointxid = "tlc".$txid;
								$processed = "n";
								if($confirmations < 3){
								//	$tx_skip--;	
									break;
								} 
								// $tx_skip++;	
								
								$inert_result = insert_transaction('tlc',$account,$address,$category,$amount,$confirmations,$txid,$normtxid,$tr_time,$timereceived,$processed);
								$inert_result = $inert_result.$account."_".$cointxid."_".$address."_".$category."_".$amount."_".$confirmations."_".$txid."_".$normtxid."_".$time."_".$timereceived;
								$log_text_main .= log_maker ( $inert_result, 1, 1, 1);

								
							}
							//$tx_skip=10;
							//$qry = "UPDATE btc_coins SET tx_skip = '$tx_skip' WHERE api='tlc'";
							
								
							echo "<br>".$tx_skip."</br>";
							$update = $db->query($qry);

						

}

function check_valid_address_tlc($address){
	Global $db, $tlc;


	$isvalid = $tlc->validateaddress($address);

	if($isvalid['isvalid']) {
		return "valid";
	} else {
		return "invalid";
	}


}