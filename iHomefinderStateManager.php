<?php

class iHomefinderStateManager {
	
	//stored in session
	const SESSION_ID = "ihf_session_id";
	const LAST_SEARCH_URL = "ihf_last_search_url";
	const SEARCH_SUMMARY = "ihf_search_summary";
	
	//stored as cookie
	const SUBSCRIBER_ID = "ihf_subscriber_id";
	const REMEMBER_ME = "ihf_remember_me";
	const LEAD_CAPTURE_USER_ID = "ihf_lead_capture_user_id";
	
	const COOKIE_TIMEOUT = 157680000; // 5 years
	
	private static $instance;
	
	//
	private $listingInfo;
	
	private function __construct() {
		
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function initialize() {
		$this->startSession();
	}
	
	private function startSession() {
		if(!$this->isSessionStarted()) {
			session_start();
		}
	}
	
	private function isSessionStarted() {
		$result = true;
		if(session_id() === "" || (function_exists("session_status") && session_status() === PHP_SESSION_NONE)) {
			$result = false;
		}
		return $result;
	}
	
	public function getUserAgent() {
		if($this->hasUserAgent()) {
			return $_SERVER["HTTP_USER_AGENT"];
		}
	}
	
	public function hasUserAgent() {
		return array_key_exists("HTTP_USER_AGENT", $_SERVER);
	}
	
	/**
	 * Returns true is the user agent is a known web crawler.
	 * We're currently not using this because we do checks on the IHF servers
	 * @return boolean
	 */
	public function isWebCrawler() {
		$result = false;
		if($this->hasUserAgent()) {
			$userAgent = $this->getUserAgent();
			$crawlers = array(
				"Mediapartners-Google",
				"Googlebot",
				"Baiduspider",
				"Bingbot",
				"msnbot",
				"Slurp",
				"Twiceler",
				"YandexBot"
			);
			foreach($crawlers as $crawler) {
				if(stripos($userAgent, $crawler)) {
					$result = true;
					break;
				}
			}
		}
		return $result;
	}
	
	public function getSessionId() {
		return $this->getSession(self::SESSION_ID);
	}
	
	public function setSessionId($value) {
		if(!empty($value)) {
			$this->setSession(self::SESSION_ID, $value);
		}
	}
	
	public function hasSessionId() {
		return $this->hasSession(self::SESSION_ID);
	}
	
	public function getLastSearchUrl() {
		return $this->getSession(self::LAST_SEARCH_URL);
	}
	
	public function setLastSearchUrl() {
		$value = $this->getCurrentUrl();
		$value = str_replace("newSearch=true&", "", $value);
		$this->setSession(self::LAST_SEARCH_URL, $value);
	}
	
	public function hasLastSearch() {
		return $this->hasSession(self::LAST_SEARCH_URL);
	}
	
	public function getSearchSummary() {
		return $this->getSession(self::SEARCH_SUMMARY);
	}
	
	public function setSearchSummary($value) {
		$this->setSession(self::SEARCH_SUMMARY, $value);
	}
	
	public function hasSearchSummary() {
		return $this->hasSession(self::SEARCH_SUMMARY);
	}
	
	public function getSubscriberId() {
		return $this->getCookie(self::SUBSCRIBER_ID);
	}
	
	public function setSubscriberId($value) {
		if(!empty($value)) {
			$this->setCookie(self::SUBSCRIBER_ID, $value);
		}
	}
	
	public function hasSubscriberId() {
		return $this->hasCookie(self::SUBSCRIBER_ID);
	}
	
	public function removeSubscriberId() {
		$this->removeCookie(self::SUBSCRIBER_ID);
	}
	
	public function getRememberMe() {
		return $this->getCookie(self::REMEMBER_ME);
	}
	
	public function setRememberMe($value) {
		$this->setCookie(self::REMEMBER_ME, $value);
	}
	
	public function hasRememberMe() {
		return $this->hasCookie(self::REMEMBER_ME);
	}
	
	public function removeRememberMe() {
		$this->removeCookie(self::REMEMBER_ME);
	}
	
	public function getLeadCaptureUserId() {
		return $this->getCookie(self::LEAD_CAPTURE_USER_ID);
	}
	
	public function setLeadCaptureUserId($value) {
		if(!empty($value)) {
			$this->setCookie(self::LEAD_CAPTURE_USER_ID, $value);
		}
	}
	
	public function hasLeadCaptureUserId() {
		return $this->hasCookie(self::LEAD_CAPTURE_USER_ID);
	}
	
	public function setListingInfo($value) {
		if(is_a($value, "iHomefinderListingInfo")) {
			$this->listingInfo = $value;
		}
	}
	
	public function getListingInfo() {
		return $this->listingInfo;
	}
	
	public function hasListingInfo() {
		return $this->listingInfo !== null;
	}
	
	public function isListingIdResults() {
		return array_key_exists("listingIdList", $_REQUEST);
	}
	
	public function isListingAddressResults() {
		return array_key_exists("streetNumber", $_REQUEST);
	}
	
	public function getCurrentUrl() {
		$scheme = "http";
		if(is_ssl()) {
			$scheme = "https";
		}
		$host = $_SERVER["HTTP_HOST"];
		$requestUri = $_SERVER["REQUEST_URI"];
		$result = $scheme . "://" . $host . $requestUri;
		return $result;
	}
	
	public function getLastSearch() {
		$result = null;
		$url = $this->getLastSearchUrl();
		$queryString = parse_url($url, PHP_URL_QUERY);
		if(!empty($queryString)) {
			parse_str($queryString, $result);
		}
		return $result;
	}
	
	private function getSession($name) {
		if($this->hasSession($name)) {
			return $_SESSION[$name];
		}
	}
	
	private function setSession($name, $value) {
		$_SESSION[$name] = $value;
	}
	
	private function hasSession($name) {
		return array_key_exists($name, $_SESSION);
	}
	
	private function removeSession($name) {
		if($this->hasSession($name)) {
			unset($_SESSION[$name]);
		}
	}
	
	private function getCookie($name) {
		if($this->hasCookie($name)) {
			return $_COOKIE[$name];
		}
	}
	
	private function setCookie($name, $value) {
		$_COOKIE[$name] = $value;
		$expireTime = time() + self::COOKIE_TIMEOUT;
		if(headers_sent()) {
			//WordPress does not buffer the response so we use JS to set cookies on shortcode requests because headers have already been sent
			$value = '
				<script type="text/javascript">
					(function() {
						var expire = new Date();
						expire.setSeconds(expire.getSeconds() + ' . self::COOKIE_TIMEOUT . ');
						document.cookie = "' . $name . '=' . $value . '; expires=" + expire.toUTCString() + "; path=/";
					})();
				</script>
			';
			iHomefinderEnqueueResource::getInstance()->addToFooter($value);
		} else {
			setcookie($name, $value, $expireTime, "/");
		}
	}

	private function hasCookie($name) {
		return array_key_exists($name, $_COOKIE);
	}
	
	private function removeCookie($name) {
		if($this->hasCookie($name)) {
			unset($_COOKIE[$name]);
		}
		$expireTime = time() - 3600;
		setcookie($name, null, $expireTime, "/");
	}
	
}