<?php
namespace aalfiann\EbookAPI;

use aalfiann\EbookAPI\Helper;

/**
 * Google Book API
 * 
 * @package    EbookAPI
 * @author     M ABD AZIZ ALFIAN <github.com/aalfiann>
 * @copyright  Copyright (c) 2019 M ABD AZIZ ALFIAN
 * @license    https://github.com/aalfiann/ebook-api-php/blob/master/LICENSE.md  MIT License
 */
class GoogleBook extends Helper {

    /**
     * Root URL of the API (no trailing slash).
     */
    var $api_url = 'https://www.googleapis.com/books/v1';
    
    /**
     * @var string Your API key.
     */
    protected $apiKey = '';

    /**
     * @var array Parameter
     */
    var $parameters = [];

    /**
     * @var string ID of public user bookshelves 
     */
    var $iduserbookshelves = '';

    /**
     * @var string ID of public bookshelves 
     */
    var $idbookshelves = '';

    /**
     * @var bool Show items inside bookshelves
     */
    var $showitembookshelves = false;

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
    private function addParams($name,$value){
        $this->parameters[$name] = $value;
    }

    /**
     * Add to 'q' parameter
     * 
     * @param string $name
     * @param string $value
     * @param string $query
     * @return this
     */
    public function query($name, $value, $query=''){
        if (in_array($name,['intitle', 'inauthor', 'inpublisher', 'subject', 'isbn', 'lccn', 'oclc'])) {
            $this->addParams('q',(!empty($query)?$query.' ':'').$name.':'.$value);
        } else {
            $this->addParams('q',$value);
        }
        return $this;
    }

    /**
     * Appends `$value` to the `q` parameter
     * 
     * @param string $value
     * @return this
     */
    public function search($value){
        $this->query('',$value);
        return $this;
    }

    /**
     * Appends `intitle:$value` to the `q` parameter.
     * 
     * @param string $value The title of book
     * @param string $query Filter for spesific title of book
     * @return this
     */
    public function title($value,$query=''){
        $this->query('intitle',$value,$query);
        return $this;
    }

    /**
     * Appends `inauthor:$value` to the `q` parameter.
     * 
     * @param string $value The author name of book
     * @param string $query Filter for spesific title of book
     * @return this
     */
    public function author($value,$query=''){
        $this->query('inauthor',$value, $query);
        return $this;
    }

    /**
     * Appends `inpublisher:$value` to the `q` parameter.
     * 
     * @param string $value The publisher name of book
     * @param string $query Filter for spesific title of book
     * @return this
     */
    public function publisher($value,$query=''){
        $this->query('inpublisher',$value,$query);
        return $this;
    }

    /**
     * Appends `insubject:$value` to the `q` parameter.
     * 
     * @param string $value The subject name of book
     * @param string $query Filter for spesific title of book
     * @return this
     */
    public function subject($value,$query=''){
        $this->query('insubject',$value,$query);
        return $this;
    }

    /**
     * Appends `isbn:$value` to the `q` parameter.
     * 
     * @param string $value The ISBN of book
     * @return this
     */
    public function isbn($value){
        $this->query('isbn',$value);
        return $this;
    }

    /**
     * Appends `lccn:$value` to the `q` parameter.
     * 
     * @param string $value The LCCN of book
     * @return this
     */
    public function lccn($value){
        $this->query('lccn',$value);
        return $this;
    }

    /**
     * Appends `oclc:$value` to the `q` parameter.
     * 
     * @param string $value The OCLC of book
     * @return this
     */
    public function oclc($value){
        $this->query('oclc',$value);
        return $this;
    }

    /**
     * Add 'filter' parameter
     * 
     * @param string $value partial|full|free-ebooks|paid-ebooks|ebooks
     * @return this
     */
    public function filter($value='full'){
        if (in_array($value,['partial','full','free-ebooks','paid-ebooks','ebooks'])) {
            $this->addParams('filter',$value);
        }
        return $this;
    }

    /**
     * Add 'projection' parameter
     * 
     * @param string $value full|lite
     * @return this
     */
    public function projection($value='lite'){
        if (in_array($value,['full','lite'])) {
            $this->addParams('projection',$value);
        }
        return $this;
    }

    /**
     * Add 'startIndex' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function startIndex($value=0){
        $this->addParams('startIndex',$value);
        return $this;
    }

    /**
     * Add 'maxResults' parameter
     * 
     * @param string|int $value
     * @return this
     */
    public function maxResults($value){
        if($value>40) $value = 40;
        $this->addParams('maxResults',$value);
        return $this;
    }

    /**
     * Add 'printType' parameter
     * 
     * @param string $value all|books|magazines
     * @return this
     */
    public function printType($value){
        if (in_array($value,['all','books','magazines'])) {
            $this->addParams('printType',$value);
        }
        return $this;
    }

    /**
     * Add 'langRestrict' parameter
     * 
     * @param string $value
     * @return this
     */
    public function langRestrict($value){
        $this->addParams('langRestrict',$value);
        return $this;
    }

    /**
     * Add 'download' parameter
     * 
     * @param string $value epub only
     * @return this
     */
    public function download($value){
        if ($value === 'epub') {
            $this->addParams('download',$value);
        }
        return $this;
    }

    /**
     * Add 'orderBy' parameter
     * 
     * @param string $value newest|relevance
     * @return this
     */
    public function orderBy($value){
        if (in_array($value,['newest','relevance'])) {
            $this->addParams('orderBy',$value);
        }
        return $this;
    }

    //Bookshelves=========================

    /**
     * Get bookshelves by userid
     * 
     * @param string $userid The googlebook user id
     * @return this
     */
    public function bookshelves($userid){
        $this->iduserbookshelves = $userid;
        return $this;
    }

    /**
     * Get bookshelves for spesific shelf
     * 
     * @param string $shelf The bookshelves id
     * @return this
     */
    public function shelf($shelf){
        $this->idbookshelves = $shelf;
        return $this;
    }

    /**
     * Get list of bookshelves (required shelf)
     * 
     * @param bool $show
     * @return this
     */
    public function retrieve($show=true){
        $this->showitembookshelves = $show;
        return $this;
    }

    /**
     * Send request to Google Book API
     * 
     * @param bool $production
     * @return this
     */
    public function send($production=true){
        $this->addParams('key',$this->apiKey);
        $this->addParams('format','json');
        if (!empty($this->iduserbookshelves) && empty($this->idbookshelves)){
            $endpoint = 'users/'.$this->iduserbookshelves.'/bookshelves';
        } elseif (!empty($this->iduserbookshelves) && !empty($this->idbookshelves) && $this->showitembookshelves==false) {
            $endpoint = 'users/'.$this->iduserbookshelves.'/bookshelves/'.$this->idbookshelves;
        } elseif (!empty($this->iduserbookshelves) && !empty($this->idbookshelves) && $this->showitembookshelves){
            $endpoint = 'users/'.$this->iduserbookshelves.'/bookshelves/'.$this->idbookshelves.'/volumes';
        } else {
            $endpoint = 'volumes';
        }
        if($production){
            $this->results = $this->request($endpoint,$this->parameters,false);
        } else {
            $this->results = $this->requestDebug($endpoint,$this->parameters,false);
        }
        // cleanup any userid and id bookshelves
        $this->iduserbookshelves = '';
        $this->idbookshelves = '';
        $this->showitembookshelves = '';
        return $this;
    }

    /**
     * Get response from Google Book API
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

    /**
     * Count results item from response
     * 
     * @return string
     */
    public function count(){
        if(!empty($this->results)){
            $data = json_decode($this->results);
            if(!empty($data) && $data->code < 400){
                return $data->response->totalItems;
            }
        }
        return 0;
    }

}
