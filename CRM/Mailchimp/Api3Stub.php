<?php
/**
 * @file
 * Mailchimp API v3.0 service wrapper Stub.
 *
 * This class is for testing only; it does not depend on or make any calls to
 * the real Mailchimp API.
 */
class CRM_Mailchimp_Api3Stub extends CRM_Mailchimp_ApiBase implements CRM_Mailchimp_ApiInterface {
  protected $debug=FALSE;
  /**
   * @param Array $mock_responses
   *
   * FIFO queue for mock responses.
   *
   */
  protected $mock_responses = [];
  /**
   * Return a mock response instead of submitting to the real API.
   */
  protected function sendRequest() {
    if ($this->debug) {
      print "Mock Mailchimp request: " . json_encode($this->request) . "\n";
    }
    if (empty($this->mock_responses)) {
      throw new CRM_Mailchimp_RequestErrorException($this, "No response mocks provided or left. Request: " . json_encode($this->request) ."\n");
    }
    list($curl_info, $result) = array_shift($this->mock_responses);
    return $this->curlResultToResponse($curl_info, $result);
  }
  /**
   * Ensure we have no mock responses in queue.
   */
  public function clearMockResponses() {
    $this->mock_responses = [];
  }

  /**
   * Queues a mocked response.
   *
   * @param array $response which can optionally have keys
   *
   * - curl_info
   *   Usually ommitted. This defaults to
   *   http_code: 200, content_type: application/json
   * - result
   *   String of json. Defaults to empty object.
   */
  public function addMockResponse($response=[]) {
    if (array_key_exists('curl_info', $response)) {
      $curl_info = $response['curl_info'];
    }
    else {
      $curl_info = [
        'http_code' => 200,
        'content_type' => 'application/json',
        ];
    }
    if (array_key_exists('result', $response)) {
      $result = $response['result'];
    }
    else {
      $result = '{}';
    }

    $this->mock_responses []= [$curl_info, $result];
  }

  public function addMockResponseError($http_code, $title) {
    $curl_info = [
        'http_code' => $http_code,
        'content_type' => 'application/json',
        ];
    $result = '{"status":' . $http_code .',"title":"' . $title . '","detail":"","instance":"","type":""}';

    $this->mock_responses []= [$curl_info, $result];
  }

  public function setDebug($debug) {
    $this->debug = (bool) $debug;
  }
}
