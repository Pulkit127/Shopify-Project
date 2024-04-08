<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Osiset\ShopifyApp\Services\ShopSession;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\View\View as ViewView;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View;
use Osiset\ShopifyApp\Actions\AuthenticateShop;
use Osiset\ShopifyApp\Exceptions\MissingAuthUrlException;
use Osiset\ShopifyApp\Exceptions\MissingShopDomainException;
use Osiset\ShopifyApp\Exceptions\SignatureVerificationException;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use Osiset\ShopifyApp\Util;
class AuthController extends Controller
{

    // public function index(){
    //     $shop = "quickstart-2244f509";
    //     $api_key = env('SHOPIFY_API_KEY');
    //     $scopes =  env('SHOPIFY_API_SCOPES');
    //     $redirect_uri = env('SHOPIFY_API_REDIRECT') ;
    //     // Build install/approval URL to redirect to
    //     $install_url = "https://admin.myshopify.com/store/".$shop."/oauth/authorize?client_id=" . $api_key . "&scope=" . urlencode($scopes). "&redirect_uri=" . urlencode($redirect_uri);
    //     return redirect($install_url);
    // }

    /**
     * Installing/authenticating a shop.
     *
     * @throws MissingShopDomainException if both shop parameter and authenticated user are missing
     *
     * @return ViewView|RedirectResponse
     */
    public function authenticate(Request $request, AuthenticateShop $authShop)
    {
        
        if ($request->missing('shop') && !$request->user()) {
            // One or the other is required to authenticate a shop
            throw new MissingShopDomainException('No authenticated user or shop domain');
        }

        // Get the shop domain
        $shopDomain = $request->has('shop')
            ? ShopDomain::fromNative($request->get('shop'))
            : $request->user()->getDomain();

        // If the domain is obtained from $request->user()
        if ($request->missing('shop')) {
            $request['shop'] = $shopDomain->toNative();
        }

        // Run the action
        [$result, $status] = $authShop($request);


        if ($status === null) {
            // Show exception, something is wrong
            throw new SignatureVerificationException('Invalid HMAC verification');
        } elseif ($status === false) {
        
            if (!$result['url']) {
                throw new MissingAuthUrlException('Missing auth url');
            }

            $shopDomain = $shopDomain->toNative();
            $shopOrigin = $shopDomain ?? $request->user()->name;

            return View::make(
                'shopify-app::auth.fullpage_redirect',
                [
                    'apiKey' => Util::getShopifyConfig('api_key', $shopOrigin),
                    'appBridgeVersion' => Util::getShopifyConfig('appbridge_version') ? '@'.config('shopify-app.appbridge_version') : '',
                    'authUrl' => $result['url'],
                    'host' => $request->host ?? base64_encode($shopOrigin.'/admin'),
                    'shopDomain' => $shopDomain,
                    'shopOrigin' => $shopOrigin,
                ]
            );
        } else {
            // Go to home route
            return Redirect::route(
                Util::getShopifyConfig('route_names.home'),
                [
                    'shop' => $shopDomain->toNative(),
                    'host' => $request->host,
                ]
            );
        }
    }


    public function token(Request $request)
    {
        // $request->session()->reflash();
        $shopDomain = ShopDomain::fromRequest($request);
        $target = $request->query('target');
        $query = parse_url($target, PHP_URL_QUERY);

        $cleanTarget = $target;
        if ($query) {
            // remove "token" from the target's query string
            $params = Util::parseQueryString($query);
            $params['shop'] = $params['shop'] ?? $shopDomain->toNative() ?? '';
            unset($params['token']);

            $cleanTarget = trim(explode('?', $target)[0].'?'.http_build_query($params), '?');
        } else {
            $params = ['shop' => $shopDomain->toNative() ?? ''];
            $cleanTarget = trim(explode('?', $target)[0].'?'.http_build_query($params), '?');
        }

        return View::make(
            'shopify-app::auth.token',
            [
                'shopDomain' => $shopDomain->toNative(),
                'target' => $cleanTarget,
            ]
        );
    }
}