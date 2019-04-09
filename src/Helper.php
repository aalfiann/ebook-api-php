<?php
namespace EbookAPI;

use aalfiann\ParallelRequest;
use aalfiann\JSON;

/**
 * Helper class
 * 
 * @package    EbookAPI
 * @author     M ABD AZIZ ALFIAN <github.com/aalfiann>
 * @copyright  Copyright (c) 2019 M ABD AZIZ ALFIAN
 * @license    https://github.com/aalfiann/ebook-api-php/blob/master/LICENSE.md  MIT License
 */
class Helper {

    /**
     * Makes requests to the API.
     *
     * @param   string  $endpoint   an API function name
     * @param   array   $params     Optional parameters
     * @param   string  $formdata   if set to false then will encode params array to url parameter
     * @return  string  json data
     */
    public function request($endpoint, array $params = array(), $formdata=true)
    {
        $req = new ParallelRequest;

        $headers = array(
            'Accept: application/xml',
        );
        if(isset($params['format']) && $params['format'] === 'json') {
            $headers = array(
                'Accept: application/json',
            );
        }

        $req->setRequest([])->addRequest($this->api_url .'/'. $endpoint,$params,$formdata)
            ->setOptions([
                CURLOPT_NOBODY => false,
                CURLOPT_HEADER => false,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'GET'
            ])->setHttpInfo()->setEncoded();
        $response = $req->send()->getResponse();
        
        $json = new JSON;
        $data = "{}";
        if(!empty($response['response'])) {
            if(isset($params['format']) && $params['format'] === 'json') {
                $data = $json->withTrim()->decode($response['response']);
            } else {
                if($this->isValidXML($response['response'])){
                    $data = $json->decode($json->withTrim()->withSanitizer()->encode((array)simplexml_load_string($response['response'], 'SimpleXMLElement', LIBXML_NOCDATA|LIBXML_NOBLANKS)), 1);
                }    
            }
        }
        
        $result = [
            'code' => $response['code'],
            'response' => $data
        ];
        return $json->withLog()->encode($json->modifyJsonStringInArray($result,['response']),JSON_UNESCAPED_SLASHES);
    }

    /**
     * Validate for XML string
     * 
     * @param   string  $xml    Is the xml data string
     * @return  bool
     */
    public function isValidXML($xml)
    {
        libxml_use_internal_errors(true);
        $sxe = simplexml_load_string($xml);
        if ($sxe === false) {
            return false;
        }
        return true;
    }

    /**
     * Makes requests to the API for debug purpose only.
     *
     * @param   string  $endpoint   an API function name
     * @param   array   $params     Optional parameters
     * @param   string  $formdata   if set to false then will encode params array to url parameter
     * @return  string  json data
     */
    public function requestDebug($endpoint, array $params = array(), $formdata=true)
    {
        $req = new ParallelRequest;

        $headers = array(
            'Accept: application/xml',
        );
        if(isset($params['format']) && $params['format'] === 'json') {
            $headers = array(
                'Accept: application/json',
            );
        }

        $req->setRequest([])->addRequest($this->api_url .'/'. $endpoint,$params,$formdata)
            ->setOptions([
                CURLOPT_NOBODY => false,
                CURLOPT_HEADER => false,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'GET'
            ])->setHttpInfo('detail')->setEncoded();
        $response = $req->send()->getResponse();
        
        $json = new JSON;
        return $json->withLog()->encode($response,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    }

}