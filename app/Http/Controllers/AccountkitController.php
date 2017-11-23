<?php

namespace App\Http\Controllers;


use AccountKit;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use App\User;
use Auth;

class AccountkitController extends Controller
{
    //
    protected $appId;
    protected $appSecret;
    protected $tokenExchangeUrl;
    protected $endPointUrl;
    protected $userAccessToken;
    protected $refreshInerval;
    protected $client;

    public function __construct(){

    	$this->appId = config('accountKit.appId');

    	$this->client = new GuzzleHttpClient();

    	$this->appSecret = config('accountKit.appSecret');

    	$this->endPointUrl = config('accountKit.endPoint');

    	$this->tokenExchangeUrl = config('accountKit.tokenExchangeUrl');

    }

    public function login(Request $request){
 

		// $this->client->setDefaultOption('verify', false);


		$url = $this->tokenExchangeUrl.'grant_type=authorization_code'.'&code='.$request->get('code')."&access_token=AA|$this->appId|$this->appSecret";
   	
    	$apiRequest = $this->client->request('GET', $url, ['verify' => false]);

    	$body = json_decode($apiRequest->getBody()->getContents());

    	$this->userAccessToken = $body->access_token;
    	$this->refreshInerval = $body->token_refresh_interval_sec;
    	return $this->getData();

    }

    public function getData() {

		$appsecret_proof= hash_hmac('sha256', $this->userAccessToken, $this->appSecret); 

    	$request = $this->client->request('GET', $this->endPointUrl.'='. $this->userAccessToken.'&appsecret_proof='.$appsecret_proof, ['verify' => false]);

    	$data = json_decode($request->getBody());

        // dd($data);

    	$userid = $data->id;

    	$userAccessToken = $this->userAccessToken;

    	$refreshInterval = $this->refreshInerval;


        if(isset($data->phone)){
            if($exists = User::where('phone', $data->phone->number)->first()){
                 Auth::login($exists);
                 return redirect('/home');
            } else {
                $user = new User;
                $user->phone = $data->phone->number;
                $user->save();
                Auth::login($user);
                return redirect('/home');
            }
        }

        else if(isset($data->email)){
            if($exists = User::where('email',$data->email->address)->first()){
                 Auth::login($exists);
                 return redirect('/home');
            }
            else {
                $user = new User;
                $user->email = $data->email->address;
                $user->save();
                Auth::login($user);
                return redirect('/home');
            }
        }

    	// $phone = isset($data->phone) ? $data->phone->number : '';

    	// $email = isset($data->email) ? $data->email->address : '';

    	return view('successful-login', compact('phone','email','userid','refreshInterval','userAccessToken'));

    }

    public function logout(){

    	return redirect('/');

    }

}
