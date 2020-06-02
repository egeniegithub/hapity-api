<?php

define('SUPER_ADMIN_ROLE_ID', 1);
define('HAPITY_USER_ROLE_ID', 2);
define('CONTACTUS_SEND_TO_EMAIL', ["masteruser@hapity.com","gohapity@gmail.com"]);
define('MAIL_FROM_ADDRESS','do-not-reply@hapity.com');
define('MAIL_FROM_NAME', 'Hapity');


switch (env('APP_URL')) {
	case 'http://localhost' || 'http://dev.hapity.local':
		define('STORAGE_PATH', storage_path('user_media'));
		break;
	case 'https://www.hapity.com':
		define('STORAGE_PATH', '');
		break;
	default:
		# code...
		break;
}
if(env('APP_ENV') == 'live')
	define('ANTMEDIA_HOST', 'antmedia.hapity.com');
else
	define('ANTMEDIA_HOST', 'stg-media.hapity.com');

define('ANT_MEDIA_SERVER_STAGING_IP', 'http://34.255.219.25:5080/');
define('ANT_MEDIA_SERVER_STAGING_URL', 'https://'.ANTMEDIA_HOST.':5443/');

define('WEBRTC_APP', 'WebRTCAppEE');
