<?php
namespace aalfiann\EbookAPI;

use aalfiann\EbookAPI\Helper;
use aalfiann\EventLocker;

/**
 * Google Reads API
 * 
 * @package    EbookAPI
 * @author     M ABD AZIZ ALFIAN <github.com/aalfiann>
 * @copyright  Copyright (c) 2019 M ABD AZIZ ALFIAN
 * @license    https://github.com/aalfiann/ebook-api-php/blob/master/LICENSE.md  MIT License
 */
class GoodReads extends Helper {

    /**
     * Root URL of the API (no trailing slash).
     */
    var $api_url = 'https://www.goodreads.com';
    
    /**
     * @var string Your API key.
     */
    protected $apiKey = '';

    /**
     * @var array Endpoint
     */
    var $endpoint = '';
    
    /**
     * @var array Parameter
     */
    var $parameters = [];

    /**
     * @var string $results of json data
     */
    var $results = '';
    
    /**
     * Initialise the API wrapper instance.
     *
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = (string)$apiKey;
    }

    /**
     * Add parameter to url
     * 
     * @param string $name  parameter name
     * @param string $value parameter value
     */
    public function addParam($name,$value){
        $this->parameters[$name] = $value;
        return $this;
    }

    /**
     * Set the path of GoodReads API
     * 
     * @param string $endpoint
     * @return this
     */
    public function path($endpoint=''){
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * Add 'q' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function q($value){
        $this->addParam('q',$value);
        return $this;
    }

    /**
     * Add 'id' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function id($value){
        $this->addParam('id',$value);
        return $this;
    }

    /**
     * Add 'page' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function page($value){
        $this->addParam('page',$value);
        return $this;
    }

    /**
     * Add 'per_page' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function per_page($value){
        if($value>200) $value = 200;
        $this->addParam('per_page',$value);
        return $this;
    }

    /**
     * Add 'sort' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function sort($value){
        $opt = [
            'title', 'author', 'cover', 'rating', 'year_pub', 'date_pub', 'date_pub_edition',
            'date_started', 'date_read', 'date_updated', 'date_added', 'recommender', 'avg_rating',
            'num_ratings', 'review', 'read_count', 'votes', 'random', 'comments', 'notes', 'isbn',
            'isbn13', 'asin', 'num_pages', 'format', 'position', 'shelves', 'owned', 'date_purchased', 
            'purchase_location', 'condition'
        ];
        if(in_array($value,$opt)){
            $this->addParam('sort',$value);
        }
        return $this;
    }

    /**
     * Add 'order' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function order($value){
        if(in_array($value,['a','d'])){
            $this->addParam('order',$value);
        }
        return $this;
    }

    /**
     * Add 'search[field]' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function searchField($value){
        $this->addParam('search[field]',$value);
        return $this;
    }

    /**
     * Add 'search[query]' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function searchQuery($value){
        $this->addParam('search[query]',$value);
        return $this;
    }

    /**
     * Add 'title' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function title($value){
        $this->addParam('title',$value);
        return $this;
    }

    /**
     * Add 'author' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function author($value){
        $this->addParam('author',$value);
        return $this;
    }

    /**
     * Add 'username' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function username($value){
        $this->addParam('username',$value);
        return $this;
    }

    /**
     * Add 'format' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function format($value){
        $this->addParam('format',$value);
        return $this;
    }

    /**
     * Add 'v' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function v($value){
        $this->addParam('v',$value);
        return $this;
    }

    /**
     * Add 'shelf' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function shelf($value){
        if(in_array($value,['read','currently-reading','to-read'])){
            $this->addParam('shelf',$value);
        }
        return $this;
    }

    /**
     * Send request to GoodReads API
     * 
     * @param bool $production
     * @return this
     */
    public function send($production=true){
        // lock this request
        $locker = new EventLocker();
        $locker->lock();
        // one second wait for the next request
        sleep(1);
        // unlock this request
        $locker->unlock();

        $this->addParam('key',$this->apiKey);
        
        if($production){
            $this->results = $this->request($this->endpoint,$this->parameters,false);
        } else {
            $this->results = $this->requestDebug($this->endpoint,$this->parameters,false);
        }
        // release memory
        $this->flush();
        return $this;
    }

    /**
     * Flush to release unused memory
     */
    private function flush(){
        $this->parameters = [];
    }

    /**
     * Get response from GoodReads API
     * 
     * @return string
     */
    public function getResponse(){
        if(!empty($this->results)){
            $data = json_decode($this->results);
            if(!empty($data) && $data->code < 400){
                return $this->results;
            }
        }
        return '';
    }

}
