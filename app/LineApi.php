<?php

namespace App;

class LineApi {
    public function __construct() {
        //
    }

    public function check_access_token($access_token) {
        $request_body = [
            'access_token' => $access_token,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, config('line.api.check_access_token.headers'));
        curl_setopt($ch, CURLOPT_URL, config('line.api.check_access_token.url'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, config('line.api.check_access_token.method'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request_body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        logger()->debug('--- check_access_token info ---');
        logger()->debug($info);
        if ($info['http_code'] == 200) {
            logger()->debug('--- check_access_token response ---');
            logger()->debug($response);
            return json_decode($response);
        }
        else {
            return false;
        }
    }

    public function get_access_token($code, $state) {
        $request_body = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => secure_url(config('line.oauth.redirect_uri')),
            'client_id' => config('line.oauth.client_id'),
            'client_secret' => config('line.oauth.client_secret'),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, config('line.api.get_access_token.headers'));
        curl_setopt($ch, CURLOPT_URL, config('line.api.get_access_token.url'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, config('line.api.get_access_token.method'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request_body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        logger()->debug('--- get_access_token info ---');
        logger()->debug($info);
        if ($info['http_code'] == 200) {
            logger()->debug('--- get_access_token response ---');
            logger()->debug($response);
            return json_decode($response);
        }
        else {
            return false;
        }
    }

    public function refresh_access_token($refresh_token) {
        $request_body = [
            'grant_type' => 'authorization_code',
            'refresh_token' => $refresh_token,
            'client_id' => config('line.oauth.client_id'),
            'client_secret' => config('line.oauth.client_secret'),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, config('line.api.refresh_access_token.headers'));
        curl_setopt($ch, CURLOPT_URL, config('line.api.refresh_access_token.url'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, config('line.api.refresh_access_token.method'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request_body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        logger()->debug('--- refresh_access_token info ---');
        logger()->debug($info);
        if ($info['http_code'] == 200) {
            logger()->debug('--- refresh_access_token response ---');
            logger()->debug($response);
            return json_decode($response);
        }
        else {
            return false;
        }
    }

    public function get_profile($access_token) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, [ 'Authorization: Bearer ' . $access_token ]);
        curl_setopt($ch, CURLOPT_URL, config('line.api.get_profile.url'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, config('line.api.get_profile.method'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        logger()->debug('--- get_profile info ---');
        logger()->debug($info);
        if ($info['http_code'] == 200) {
            logger()->debug('--- get_profile response ---');
            logger()->debug($response);
            return json_decode($response);
        }
        else {
            return false;
        }
    }

    public function push_messages($user_id, array $messages) {
        $request_body = [
            'to' => $user_id,
            'messages' => collect($messages)->map(function($message) {
                return [ 'type'=>'text', 'text'=>$message ];
            })->toArray(),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, config('line.api.push_message.headers'));
        curl_setopt($ch, CURLOPT_URL, config('line.api.push_message.url'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, config('line.api.push_message.method'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        logger()->debug('--- push_messages info ---');
        logger()->debug($info);
        if ($info['http_code'] == 200) {
            logger()->debug('--- push_messages response ---');
            logger()->debug($response);
            return json_decode($response);
        }
        else {
            return false;
        }
    }
}
