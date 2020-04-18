<?php

namespace App\Http\Controllers;

use App\Leaderboard;
use Illuminate\Http\Request;
use Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException as ExceptionRequestException;

use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class Chatbot extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function quickStart()
    {
        $user = Auth::user();
        $client = new Client(['/'], array(
            'request.options' => array(
               'exceptions' => false,
             )
        ));
        $twitch_userId =  DB::table('social_accounts')->where('id', $user->id)->first()->platform_user_id;
        try {
            $response = $client->post('http://ec2-3-90-25-66.compute-1.amazonaws.com:5000/', ['json' => ['twitch_username' => $user->username, 'twitch_userId' => $twitch_userId]]);
            $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
            return redirect()->route('dashboard');
        } catch (ExceptionRequestException $e) {
            return view('dashboard.index', [$error => true]);
        }
    }

    public function join()
    {
        $user = Auth::user();
        $client = new Client(['/'], array(
            'request.options' => array(
               'exceptions' => false,
             )
        ));
        $twitch_userId =  DB::table('social_accounts')->where('id', $user->id)->first()->platform_user_id;
        try {
            $response = $client->put('http://ec2-3-90-25-66.compute-1.amazonaws.com:5000/', ['json' => ['twitch_username' => $user->username, 'twitch_userId' => $twitch_userId, 'action' => 'join']]);
            $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
            return response()->json(json_decode($data));
        } catch (ExceptionRequestException $e) {
            return view('dashboard.index', [$error => true]);
        }
    }

    public function part()
    {
        $user = Auth::user();
        $client = new Client(['/'], array(
            'request.options' => array(
               'exceptions' => false,
             )
        ));
        $twitch_userId =  DB::table('social_accounts')->where('id', $user->id)->first()->platform_user_id;
        try {
            $response = $client->put('http://ec2-3-90-25-66.compute-1.amazonaws.com:5000/', ['json' => ['twitch_username' => $user->username, 'twitch_userId' => $twitch_userId, 'action' => 'part']]);
            $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
            return response()->json(json_decode($data));
        } catch (ExceptionRequestException $e) {
            return view('dashboard.index', [$error => true]);
        }
    }

    public function updateUsername()
    {
        $user = Auth::user();
        $client = new Client(['/'], array(
            'request.options' => array(
               'exceptions' => false,
             )
        ));
        $twitch_userId =  DB::table('social_accounts')->where('id', $user->id)->first()->platform_user_id;
        try {
            $response = $client->put('http://ec2-3-90-25-66.compute-1.amazonaws.com:5000/', ['json' => ['twitch_username' => $user->username, 'twitch_userId' => $twitch_userId, 'action' => 'updateUsername']]);
            $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
            return response()->json(json_decode($data));
        } catch (ExceptionRequestException $e) {
            return view('dashboard.index', [$error => true]);
        }
    }

    public function delete()
    {
        $user = Auth::user();
        $client = new Client(['/'], array(
            'request.options' => array(
               'exceptions' => false,
             )
        ));
        $twitch_userId =  DB::table('social_accounts')->where('id', $user->id)->first()->platform_user_id;
        try {
            $response = $client->delete('http://ec2-3-90-25-66.compute-1.amazonaws.com:5000/', ['json' => ['twitch_userId' => $twitch_userId]]);
            $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
            return response()->json(json_decode($data));
        } catch (ExceptionRequestException $e) {
            return view('dashboard.index', [$error => true]);
        }
    }

    public function status()
    {
        $user = Auth::user();
        $client = new Client(['/'], array(
            'request.options' => array(
               'exceptions' => false,
             )
        ));
        $twitch_userId =  DB::table('social_accounts')->where('id', $user->id)->first()->platform_user_id;
        try {
            $response = $client->post('http://ec2-3-90-25-66.compute-1.amazonaws.com:5000/status', ['json' => ['twitch_userId' => $twitch_userId]]);
            $data = json_encode(['status_code' => $response->getStatusCode(), 'message' => json_decode($response->getBody())]);
            return response()->json(json_decode($data));
        } catch (ExceptionRequestException $e) {
            return view('dashboard.index', [$error => true]);
        }
    }
}
