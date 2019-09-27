<?php

namespace Statamic\Addons\DynamicToken;

use Illuminate\Http\Request;
use Statamic\Extend\Controller;

class DynamicTokenController extends Controller
{
    /**
     * Get refreshed CSRF token.
     *
     * @return string
     */
    public function getRefresh(Request $request)
    {
        // checks that the request is comming from your own website.
        $referer = $request->headers->get('referer');
        // where APP_URL WOULD BE `site.com`
        $appUrl = env('APP_URL');
        $httpUrl = "http://{$appUrl}";
        $httpsUrl = "https://{$appUrl}";
        $startWithAppUrl = starts_with($referer, $httpUrl) || starts_with($referer, $httpsUrl);
        if (empty($referer) || !$startWithAppUrl) {
            abort(404);
        }

        return csrf_token();
    }
}
