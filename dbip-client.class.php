<?php declare(strict_types=1);

/**
 *
 * DB-IP.com API client class
 *
 * Copyright (C) 2018 db-ip.com
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 */

namespace DBIP;

const DEFAULT_BASE_URL = "http://api.db-ip.com/v2/";
const DEFAULT_API_KEY = "free";

class ClientError extends \Exception {
}

class ServerError extends \Exception {
	private $errorCode;
	public function __construct(string $message, string $errorCode) {
		parent::__construct($message);
		$this->errorCode = $errorCode;
	}
	public function getErrorCode() : string {
		return $this->errorCode;
	}
}

abstract class ErrorCode {
	const INVALID_KEY = "INVALID_KEY",
		INVALID_ADDRESS = "INVALID_ADDRESS",
		HTTPS_NOT_ALLOWED = "HTTPS_NOT_ALLOWED",
		TEMPORARY_BLOCKED = "TEMPORARY_BLOCKED",
		TOO_MANY_ADDRESSES = "TOO_MANY_ADDRESSES",
		OVER_QUERY_LIMIT = "OVER_QUERY_LIMIT",
		EXPIRED = "EXPIRED",
		UNAVAILABLE = "UNAVAILABLE";
}

class Client {

	private $baseUrl;
	private $apiKey;
	private $lang;

	static private $defaultBaseUrl = DEFAULT_BASE_URL;
	static private $instance;

	static public function getInstance() : self {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	static public function setBaseUrl(string $url) : void {
		self::$defaultBaseUrl = $url;
	}

	protected function __construct(string $apiKey = null, string $baseUrl = null) {
		if (isset($apiKey)) {
			$this->apiKey = $apiKey;
		} else {
			$this->apiKey = APIKey::$defaultApiKey;
		}
		if (isset($baseUrl)) {
			$this->baseUrl = $baseUrl;
		} else {
			$this->baseUrl = self::$defaultBaseUrl;
		}
		if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
			$this->setPreferredLanguage($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
		}
	}

	protected function apiCall(string $path = "") : \stdClass {
		$url = $this->baseUrl . $this->apiKey . $path;
		$httpOptions = [
			"header" => [
				"User-Agent: dbip-api-client",
			],
		];
		if (isset($this->lang)) {
			$httpOptions["header"][] = [ "Accept-Language: {$this->lang}" ];
		}
		if (!$jsonData = file_get_contents($url, false, stream_context_create([ "http" => $httpOptions ]))) {
			throw new ClientError("unable to fetch URL: {$url}");
		} else if (!$data = json_decode($jsonData)) {
			throw new ClientError("cannot decode server response");
		} else if (isset($data->error)) {
			throw new ServerError("server reported an error: {$data->error}", $data->errorCode);
		}
		return $data;
	}

	public function setPreferredLanguage(string $lang) : void {
		$this->lang = $lang;
	}

	public function getAddressInfo($addr) : \stdClass {
		$path = "/";
		if (is_array($addr)) {
			$path .= implode(",", $addr);
		} else {
			$path .= $addr;
		}
		return $this->apiCall($path);
	}

	public function getASInfo($asNumber) : \stdClass {
		$path = "/as/";
		if (is_array($asNumber)) {
			$path .= implode(",", $asNumber);
		} else {
			$path .= $asNumber;
		}
		return $this->apiCall($path);
	}

	public function getKeyInfo() : \stdClass {
		return $this->apiCall();
	}

}

class Address {
	static public function lookup($addr = "self") : \stdClass {
		return Client::getInstance()->getAddressInfo($addr);
	}
}

class ASN {
	static public function lookup($asNumber) : \stdClass {
		return Client::getInstance()->getASInfo($asNumber);
	}
}

class APIKey {
	static public $defaultApiKey = DEFAULT_API_KEY;
	static public function set(string $apiKey) : void {
		self::$defaultApiKey = $apiKey;
	}
	static public function info() : \stdClass {
		return Client::getInstance()->getKeyInfo();
	}
}