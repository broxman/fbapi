<?php
namespace Broxman\Fbapi;

/**
 * Class Fbapi
 * @package Fbapi
 */
class Fbapi {
	protected $api_endpoint;
    protected $api_key;
    protected $pageSize = 10;

    /**
     * Fbapi constructor.
     * @param $api_endpoint
     * @param $api_key
     */
	function __construct($api_endpoint, $api_key) {
		$this->api_endpoint = $api_endpoint;
		$this->api_key = $api_key;
	}

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if (preg_match("/^(get|post|put|delete)(.*)/i", $name, $m)){
            $type = strtoupper($m[1]);
            $url = $this->api_endpoint . '/' . $m[2] . '/';
            if (isset($arguments[0])){
                if (is_array($arguments[0])) {
                    $url .= implode('/', $arguments[0]);
                }
                else {
                    $url .= $arguments[0];
                }
            }
            return $this->execute(new Request($type, $url, $this->api_key, $arguments[1], $arguments[2], $arguments[3], $this->pageSize));
        }
        throw new \Exception("Method not found.");
    }

    /**
     * @param $type
     * @param $url
     * @param $data
     * @param $mime
     * @return mixed
     * @throws \Exception
     */
    protected function execute($Request)
    {
        $Response = $Request->getResponce();
        if ($Response->code == 200) {
            if(isset($Response->headers['x-count'])) { //Return iterator;
                return new \Broxman\Iterator\PageIterator($Response->body, $Request);
            }
            return $Response->body;
        }
        else {
            if (is_array($Response->body)) {
                if (isset($Response->body['message'])){
                    $error = $Response->body['message'];
                }
                else {
                    $error = $Response->raw_body;
                }
            }
            elseif (is_object($Response->body)){
                $error = implode(', ', array_map(function($e) {
                    return $e->Value;
                }, $Response->body->Errors));
            }
            else {
                $error = $Response->body;
            }
            throw new \Exception($error);
        }
    }



}