<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use App\User;
use Socialite;
use Auth;

class FacebookController extends Controller
{
    //


	 public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {   

        $providerUser = Socialite::driver('facebook')->stateless()->setHttpClient(new GuzzleHttpClient(['verify' => false]))->user();

        // dd($providerUser);
        // $user = Socialite::driver('facebook')->stateless()->user();
        if($exists = User::where('email',$providerUser->email)->first()){
             Auth::login($exists);
             return redirect('/home');
        }
        else{

            $user = new User;
            $user->name = $providerUser->name;
            $user->email = $providerUser->email;

            $user->save();
            Auth::login($user);
            return redirect('/home');
        }
    

    }



}
