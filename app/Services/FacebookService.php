<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FacebookService
{
    public function postToPage($message)
    {
        $pageId = '863707336834066';
        $token = 'EAA4c3g4kZCP8BRSqKLhJ1aRf5ApKCJYZBjqhOodJ5WhtLYlmm4fnabZCciXnMZCb4I3PLfFqoTrZCtKhqRQhZC70WcnPfqZBU0ZB2B4Ue4FLnE3gKvFBztI4prRhiDXhfDAXZAxsDrBsPmArSBUJXVS49nHpap7sf672FUdrTAG2QgzRaXsa33bBC7vdF369Kl6UOpMn2EgASZBwiaKK4ry7djZBYbJSSiLIRFIbg5ksRSvHlEZD';

        $url = "https://graph.facebook.com/{$pageId}/feed";

        return Http::post($url, [
            'message' => $message,
            'access_token' => $token,
        ])->json();
    }
}
