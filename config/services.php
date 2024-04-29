<?php


use LightSaml\SamlConstants;

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'saml2' => [
        'acs' => env('SAML_IDP_ACS'), 
        'entityid' => env('SAML_IDP_ENTITYID'), 
        'certificate' => file_get_contents(storage_path('app').'/cert/idp_saml.pem'),
        'sp_certificate' => file_get_contents(storage_path('app').'/cert/sp_saml.crt'),
        'sp_private_key' => file_get_contents(storage_path('app').'/cert/sp_saml.pem'),
        'sp_acs' => 'app/saml2/callback',
        'sp_entityid' => env('SAML_SP_ENTITYID'),
        'sp_sls' => 'app/saml2/logout',
        'attribute_map' => [
            'email' => 'http://wso2.org/claims/emailaddress',
            'first_name' => 'http://wso2.org/claims/givenname',
            'last_name' => 'http://wso2.org/claims/lastname'
        ]
    ],

];
