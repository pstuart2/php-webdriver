<?php
namespace facebook\Selenium\phpWebDriver;

/**
 * An abstraction allowing the driver to manipulate the browser's window
 */
class WebDriverAlert {

	protected $executor;
	protected $sessionID;

	public function __construct($executor, $session_id) {
		$this->executor = $executor;
		$this->sessionID = $session_id;
	}

	public function getText() {
		return $this->execute('getAlertText');
	}

	public function sendKeys($value) {
		$params = array('value' => array((string)$value));
		$this->execute('sendKeysToElement', $params);
		return $this;
	}

	public function accept() {
		$this->execute('acceptAlert');
		return $this;
	}

	public function dismiss() {
		$this->execute('dismissAlert');
		return $this;
	}

	private function execute($name, array $params = array()) {
		$command = array(
			'sessionId' => $this->sessionID,
			'name' => $name,
			'parameters' => $params,
		);
		$raw = $this->executor->execute($command);
		return $raw['value'];
	}
}
