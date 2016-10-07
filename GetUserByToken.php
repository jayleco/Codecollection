<?php
namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Arr;
class OAuthController extends Controller
{
    protected $http;
    /**
     * OAuthController constructor.
     *
     * @param $http
     */
    public function __construct(Client $http)
    {
        $this->http = $http;
    }
    public function oauth(Request $request)
    {
      
  // $http = new \GuzzleHttp\Client;
  // $response = $http->post('http://passport.dev/oauth/token', [
  //   'form_params' => [
  //       'grant_type' => 'authorization_code',
  //       'client_id' => 'client_id',
  //       'client_secret' => 'client_secret',
  //       'redirect_uri' => 'http://passport-client.dev/callback',
  //       'code' => $request->get('code'),
  //   ],
  // ]);
  // 上面是 OAuth 2.0 的最标准流程的实现，下面的 Password Grant Type 的实现
  
        $response = $this->http->post('http://passport.dev/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => 6,
                'client_secret' => 'OX9FHfdux2IRCqo7o0SyRsffCkwnbW61h7Sl3IBB',
                'username' => 'jellybool@outlook.com',
                'password' => 'jellybool',
                'scope' => '',
            ],
        ]);
        $accessToken =  Arr::get(json_decode((string) $response->getBody(), true),'access_token');
        return $this->getUserByToken($accessToken);
    }
    private function getUserByToken($accessToken) {
        $headers = ['Authorization' => 'Bearer '.$accessToken];
        $request = new \GuzzleHttp\Psr7\Request('GET', 'http://passport.dev/api/user', $headers);
        $response = $this->http->send($request);
        return json_decode((string) $response->getBody(), true);
    }
}
