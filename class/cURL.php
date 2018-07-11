<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: January 18, 2013
 * @description: Custom class cURL for DSS use...
 */

    class cURL { 
        var $headers; 
        var $user_agent; 
        var $compression;
        
        function cURL() {
            $this->headers[] = 'Connection: Keep-Alive'; 
            $this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8'; 
            $this->user_agent = $_SERVER['HTTP_USER_AGENT']; 
            $this->compression = 'gzip'; 
        }
        
        function get($url) { 
            $process = curl_init($url); 
            curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers); 
            curl_setopt($process, CURLOPT_HEADER, 0); 
            curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
            curl_setopt($process, CURLOPT_ENCODING , $this->compression); 
            //curl_setopt($process, CURLOPT_TIMEOUT, 30); 
            curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
            
            $return = curl_exec($process); 
            curl_close($process);
            
            return $return; 
        }
        
        function post($url,$data) { 
            $process = curl_init($url); 
            curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers); 
            curl_setopt($process, CURLOPT_HEADER, 0); 
            curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
            curl_setopt($process, CURLOPT_ENCODING , $this->compression); 
            //curl_setopt($process, CURLOPT_TIMEOUT, 30); 
            curl_setopt($process, CURLOPT_POSTFIELDS, $data); 
            curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
            curl_setopt($process, CURLOPT_POST, 1); 
            
            $return = curl_exec($process); 
            curl_close($process);
            
            return $return; 
        }
        
    }
?>
