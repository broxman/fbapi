<?php
namespace Broxman\Fbapi;

/**
 *
 */
class Request implements \Broxman\Iterator\Pager
{
    protected $type;
    protected $url;
    protected $apikey;
    protected $query;
    protected $postdata;
    protected $mime;
    protected $pageSize = 10;

    /**
     * Request constructor.
     * @param $type
     * @param $url
     * @param $apikey
     * @param null $query
     * @param null $postdata
     * @param null $mime
     * @param int $pageSize
     */
    public function  __construct($type, $url, $apikey, $query = null, $postdata = null, $mime = null)
    {
        $this->type = $type;
        $this->url = $url;
        $this->query = $query;
        $this->postdata = $postdata;
        $this->mime = $mime;
        if (isset($query['pageSize'])){
            $this->pageSize = $query['pageSize'];
        }
        $this->apikey = $apikey;
    }

    /**
     * @return \Httpful\Response
     */
    public function getResponce(){
        $url = $this->url;
        if (!isset($this->query['pageSize']) && $this->type == 'GET'){
            $this->query['pageSize'] = $this->pageSize;
        }
        if (!isset($this->query['pageNumber']) && $this->type == 'GET'){
            $this->query['pageNumber'] = 0;
        }
        if (isset($this->query)){
            $url .= '?' . http_build_query($this->query);
        }

        /** @var \Httpful\Response $Request */
        $Request = \Httpful\Request::init($this->type)->uri($url);
        if ($this->postdata){
            if ($this->mime === null) {
                $this->mime = 'application/json';
            }
            $Request->body($this->postdata, $this->mime);
        }
        else {
            $Request->mime($this->mime);
        }
        return $Request->authenticateWith(0, $this->apikey)->send();
    }

    /**
     * @param int $pageNumber
     * @return array
     */
    public function getPage(int $pageNumber = 0): array{
        $this->query['pageNumber'] = $pageNumber;
        $Responce = $this->getResponce();
        return $Responce->body;
    }

    /**
     * @return int
     */
    public function getPageSize(): int{
        return $this->pageSize;
    }

    /**
     * @param int $pageNumber
     */
    public function setPageSize(int $pageNumber = 0){
        $this->pageSize = $pageNumber;
    }
}
