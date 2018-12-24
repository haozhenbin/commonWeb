<?php


class EscienceOauth extends AbstractProvider
{

	public function urlAuthorize()
	{
		return OAUTH_AUTHORIZE_URL;
	}

	public function urlAccessToken()
	{
		return OAUTH_ACCESS_TOKEN_URL;
	}

	public function urlUserDetails(AccessToken $token)
	{
		//获取token时已返回用户信息
	}

	public function userDetails($response, AccessToken $token)
	{
		//获取token时已返回用户信息
	}

	public function userUid($response, AccessToken $token)
	{
		return $response->id;
	}

	public function userEmail($response, AccessToken $token)
	{
		return isset($response->email) && $response->email ? $response->email : null;
	}

	public function userScreenName($response, AccessToken $token)
	{
		return array($response->first_name, $response->last_name);
	}
}

abstract class AbstractProvider
{
	public $clientId = '';

	public $clientSecret = '';

	public $redirectUri = '';

	public $name;

	public $uidKey = 'uid';

	public $scopes = array();

	public $method = 'post';

	public $scopeSeparator = ',';

	public $responseType = 'json';

	public $headers = null;

	protected $httpClient;

	/**
	 * @var int This represents: PHP_QUERY_RFC1738, which is the default value for php 5.4
	 *          and the default encryption type for the http_build_query setup
	 */
	protected $httpBuildEncType = 1;

	public function __construct($options = array())
	{
		foreach ($options as $option => $value) {
			if (isset($this->{$option})) {
				$this->{$option} = $value;
			}
		}

		//$this->setHttpClient(new GuzzleClient);
	}

	public function setHttpClient(array $client)
	{
		$this->httpClient = $client;

		return $this;
	}

	public function getHttpClient()
	{
		$client = clone $this->httpClient;

		return $client;
	}

	abstract public function urlAuthorize();

	abstract public function urlAccessToken();

	abstract public function urlUserDetails(AccessToken $token);

	abstract public function userDetails($response, AccessToken $token);

	public function getScopes()
	{
		return $this->scopes;
	}

	public function setScopes(array $scopes)
	{
		$this->scopes = $scopes;
	}

	public function getAuthorizationUrl($options = array())
	{
		$state = md5(uniqid(rand(), true));

		$params = array(
				'client_id' => $this->clientId,
				'redirect_uri' => $this->redirectUri,
				'state' => $state,
				'scope' => is_array($this->scopes) ? implode($this->scopeSeparator, $this->scopes) : $this->scopes,
				'response_type' => isset($options['response_type']) ? $options['response_type'] : 'code',
				'approval_prompt' => 'auto'
		);

		return $this->urlAuthorize() . '?' . $this->httpBuildQuery($params, '', '&');
	}

	// @codeCoverageIgnoreStart
	public function authorize($options = array())
	{
		header('Location: ' . $this->getAuthorizationUrl($options));
		exit;
	}
	// @codeCoverageIgnoreEnd

	public function getAccessToken($grant = 'authorization_code', $params = array())
	{
		if (is_string($grant)) {
			// PascalCase the grant. E.g: 'authorization_code' becomes 'AuthorizationCode'
			$className = str_replace(' ', '', ucwords(str_replace(array('-', '_'), ' ', $grant)));
			$grant = $className;
			if (!class_exists($grant)) {
				throw new \InvalidArgumentException('Unknown grant "'.$grant.'"');
			}
			$grant = new $grant;
		} elseif (! $grant instanceof GrantInterface) {
			$message = get_class($grant).' is not an instance of GrantInterface';
			throw new \InvalidArgumentException($message);
		}

		$defaultParams = array(
				'client_id'     => $this->clientId,
				'client_secret' => $this->clientSecret,
				'redirect_uri'  => $this->redirectUri,
				'grant_type'    => "authorization_code",
		);

		$requestParams = $grant->prepRequestParams($defaultParams, $params);

		try {
			switch (strtoupper($this->method)) {
				case 'GET':
					$response = $this->http($this->urlAccessToken(), $requestParams, 'GET');
					break;
					// @codeCoverageIgnoreEnd
				case 'POST':

					$response = $this->http($this->urlAccessToken(), $requestParams, 'POST');
					break;
					// @codeCoverageIgnoreStart
				default:
					throw new \InvalidArgumentException('Neither GET nor POST is specified for request');
					// @codeCoverageIgnoreEnd
			}
		} catch (BadResponseException $e) {
			// @codeCoverageIgnoreStart
			$raw_response = explode("\n", $e->getResponse());
			$response = end($raw_response);
			// @codeCoverageIgnoreEnd
		}

		switch ($this->responseType) {
			case 'json':
				$result = json_decode($response, true);
				break;
			case 'string':
				parse_str($response, $result);
				break;
		}

		if (isset($result['error']) && ! empty($result['error'])) {
			// @codeCoverageIgnoreStart
			throw new IDPException($result);
			// @codeCoverageIgnoreEnd
		}

		return $grant->handleResponse($result);
	}

	public function getUserDetails(AccessToken $token)
	{
		$response = $this->fetchUserDetails($token);

		return $this->userDetails(json_decode($response), $token);
	}

	public function getUserUid(AccessToken $token)
	{
		$response = $this->fetchUserDetails($token, true);

		return $this->userUid(json_decode($response), $token);
	}

	public function getUserEmail(AccessToken $token)
	{
		$response = $this->fetchUserDetails($token, true);

		return $this->userEmail(json_decode($response), $token);
	}

	public function getUserScreenName(AccessToken $token)
	{
		$response = $this->fetchUserDetails($token, true);

		return $this->userScreenName(json_decode($response), $token);
	}

	/**
	 * Build HTTP the HTTP query, handling PHP version control options
	 *
	 * @param  array        $params
	 * @param  integer      $numeric_prefix
	 * @param  string       $arg_separator
	 * @param  null|integer $enc_type
	 * @return string
	 *                                     @codeCoverageIgnoreStart
	 */
	protected function httpBuildQuery($params, $numeric_prefix = 0, $arg_separator = '&', $enc_type = null)
	{
		if (version_compare(PHP_VERSION, '5.4.0', '>=') && !defined('HHVM_VERSION')) {
			if ($enc_type === null) {
				$enc_type = $this->httpBuildEncType;
			}
			$url = http_build_query($params, $numeric_prefix, $arg_separator, $enc_type);
		} else {
			$url = http_build_query($params, $numeric_prefix, $arg_separator);
		}

		return $url;
	}

	protected function fetchUserDetails(AccessToken $token)
	{
		
	}

	protected function http($url, $data='', $method='GET'){
		$dataParam = $this->httpBuildQuery($data);
		$curl = curl_init(); // 启动一个CURL会话
		curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
		if($method=='POST'){
			curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
			if ($data != ''){
				curl_setopt($curl, CURLOPT_POSTFIELDS, $dataParam); // Post提交的数据包
			}
		}
		curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
		curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
		$tmpInfo = curl_exec($curl); // 执行操作
		curl_close($curl); // 关闭CURL会话
		return $tmpInfo; // 返回数据
	}
}



class IDPException extends \Exception
{
	protected $result;

	public function __construct($result)
	{
		$this->result = $result;

		$code = isset($result['code']) ? $result['code'] : 0;

		if (isset($result['error'])) {

			// OAuth 2.0 Draft 10 style
			$message = $result['error'];

		} elseif (isset($result['message'])) {

			// cURL style
			$message = $result['message'];

		} else {

			$message = 'Unknown Error.';

		}

		parent::__construct($message, $code);
	}

	public function getType()
	{
		if (isset($this->result['error'])) {

			$message = $this->result['error'];

			if (is_string($message)) {
				// OAuth 2.0 Draft 10 style
				return $message;
			}
		}

		return 'Exception';
	}

	/**
	 * To make debugging easier.
	 *
	 * @return string The string representation of the error.
	 */
	public function __toString()
	{
		$str = $this->getType() . ': ';

		if ($this->code != 0) {
			$str .= $this->code . ': ';
		}

		return $str . $this->message;
	}

}


class Authorizationcode implements GrantInterface
{
	public function __toString()
	{
		return 'authorization_code';
	}

	public function prepRequestParams($defaultParams, $params)
	{
		if ( ! isset($params['code']) || empty($params['code'])) {
			throw new \BadMethodCallException('Missing authorization code');
		}

		return array_merge($defaultParams, $params);
	}

	public function handleResponse($response = array())
	{
		return new AccessUserToken($response);
	}
}

interface GrantInterface
{
	public function __toString();

	public function handleResponse($response = array());

	public function prepRequestParams($defaultParams, $params);

}

class RefreshToken implements GrantInterface
{
	public function __toString()
	{
		return 'refresh_token';
	}

	public function prepRequestParams($defaultParams, $params)
	{
		if ( ! isset($params['refresh_token']) || empty($params['refresh_token'])) {
			throw new \BadMethodCallException('Missing refresh_token');
		}

		$params['grant_type'] = 'refresh_token';

		return array_merge($defaultParams, $params);
	}

	public function handleResponse($response = array())
	{
		return new AccessToken($response);
	}
}

class AccessToken
{
	/**
	 * @var  string  accessToken
	 */
	public $accessToken;

	/**
	 * @var  int  expires
	 */
	public $expires;

	/**
	 * @var  string  refreshToken
	 */
	public $refreshToken;

	/**
	 * @var  string  uid
	 */
	public $uid;

	/**
	 * Sets the token, expiry, etc values.
	 *
	 * @param  array $options token options
	 * @return void
	 */
	public function __construct(array $options = null)
	{
		if (! isset($options['access_token'])) {
			throw new \InvalidArgumentException('Required option not passed: access_token'
					. PHP_EOL.print_r($options, true));
		}

		$this->accessToken = $options['access_token'];

		// Some providers (not many) give the uid here, so lets take it
		isset($options['uid']) and $this->uid = $options['uid'];

		// Vkontakte uses user_id instead of uid
		isset($options['user_id']) and $this->uid = $options['user_id'];

		// Mailru uses x_mailru_vid instead of uid
		isset($options['x_mailru_vid']) and $this->uid = $options['x_mailru_vid'];

		// We need to know when the token expires, add num. seconds to current time
		isset($options['expires_in']) and $this->expires = time() + ((int) $options['expires_in']);

		// Facebook is just being a spec ignoring jerk
		isset($options['expires']) and $this->expires = time() + ((int) $options['expires']);

		// Grab a refresh token so we can update access tokens when they expires
		isset($options['refresh_token']) and $this->refreshToken = $options['refresh_token'];
	}

	/**
	 * Returns the token key.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->accessToken;
	}
}

class AccessUserToken
{
	/**
	 * @var  string  accessToken
	 */
	public $accessToken;

	/**
	 * @var  int  expires
	 */
	public $expires;

	/**
	 * @var  string  refreshToken
	 */
	public $refreshToken;

	/**
	 * @var  string  uid
	 */
	public $uid;

	public $userInfo;

	/**
	 * Sets the token, expiry, etc values.
	 *
	 * @param  array $options token options
	 * @return void
	 */
	public function __construct(array $options = null)
	{
		if (! isset($options['access_token'])) {
			throw new \InvalidArgumentException(
					'Required option not passed: access_token'. PHP_EOL
					. print_r($options, true)
			);
		}

		$this->accessToken = $options['access_token'];

		// Some providers (not many) give the uid here, so lets take it
		isset($options['uid']) and $this->uid = $options['uid'];

		// Vkontakte uses user_id instead of uid
		isset($options['user_id']) and $this->uid = $options['user_id'];

		// Mailru uses x_mailru_vid instead of uid
		isset($options['x_mailru_vid']) and $this->uid = $options['x_mailru_vid'];

		// We need to know when the token expires, add num. seconds to current time
		isset($options['expires_in']) and $this->expires = time() + ((int) $options['expires_in']);

		// Facebook is just being a spec ignoring jerk
		isset($options['expires']) and $this->expires = time() + ((int) $options['expires']);

		// Grab a refresh token so we can update access tokens when they expires
		isset($options['refresh_token']) and $this->refreshToken = $options['refresh_token'];

		isset($options['userInfo']) and $this->userInfo = json_decode($options['userInfo']);
	}

	/**
	 * Returns the token key.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->accessToken;
	}
}

?>