<?php
/**
 * Class for creating requests to Graph API
 * 
 * @author Andreas Geisler
 * @version 0.1
 */
namespace Facebook;

class Request {
	protected static $appId = '';
	protected static $appSecret = '';

	/**
	 * Set credentials for the app
	 * 
	 * @param string $appId
	 * @param string $appSecret
	 */
	public static function setAppCredentials($appId, $appSecret) {
		self::$appId = $appId;
		self::$appSecret = $appSecret;
	}

	public static function getAuthToken() {
		return self::_fetchUrl("https://graph.facebook.com/oauth/access_token?grant_type=client_credentials&client_id=". self::$appId ."&client_secret=".self::$appSecret);
	}
	
	/**
	 * Read posts of a facebook profile, given by $profileId
	 * 
	 * @param string $profileId
	 * @return \JsonSerializable
	 * @throws Exception
	 */
	public function readProfileFeed($profileId = null) {
		if( empty($profileId) ) {
			throw new Exception("Empty profile id in request");
		}

		$authToken = self::getAuthToken();
		$resultJsonString = self::_fetchUrl("https://graph.facebook.com/{$profileId}/feed?{$authToken}");
		$json = json_decode($resultJsonString, true);

		return $json;
	}

	/**
	 * Do a simple cUrl query
	 * 
	 * @param string $url
	 * @return mixed
	 */
	private static function _fetchUrl($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	
		$feedData = curl_exec($ch);
		curl_close($ch);
		
		// TODO: Handle errors
		
		return $feedData;
	}
	
}