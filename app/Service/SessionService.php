<?php

namespace App\Service;

use GuzzleHttp\Client;
use App\Models\User;

class SessionService
{
	private static $client;
	private static $redis;

	private static function init()
	{
		if (! self::$client) {
			self::$client = new Client([
	            'base_uri' => config('sso.session-service-url'),
	        ]);
		}
		if (! self::$redis) {
			self::$redis = new \Predis\Client();
		}
	}

	public static function action($type = null)
	{
		self::init();
		// если уже отсылали недавно обновление времени последнего действия
		if (self::setCache()) {
			return;
		}

		$params = [
			'user_id' => User::id(),
			'type'    => User::USER_TYPE,
		];

		self::$client->post('sessions/action', [
            'form_params' => $params,
        ]);
	}

	public static function exists()
	{
		self::init();
		$key = config('sso.cache-key') . ":session:exists:" . User::id();
		if (self::$redis->exists($key)) {
			return self::$redis->get($key);
		}
		$response = self::$client->get("sessions/exists/" . User::id());
		$exists = json_decode($response->getBody()->getContents());
		self::$redis->set($key, $exists ? 1 : 0, 'EX', 15);
		return $exists;
	}

	/**
	 * Закешировать установку ACTION.
	 * ACTION можно делать раз в минуту
	 */
	public static function setCache($seconds = 30)
	{
		self::init();
		$key = config('sso.cache-key') . ":session:action:" . User::id();
		if (self::$redis->get($key)) {
			return true;
		}
		self::$redis->set($key, 1, 'EX', $seconds);
		return false;
	}

	public static function clearCache()
	{
		self::init();
		$key = config('sso.cache-key') . ":session:exists:" . User::id();
		self::$redis->del($key);
	}

	public static function destroy()
	{
		self::init();
		self::clearCache();
		self::$client->get("sessions/destroy/" . User::id());
	}
}
