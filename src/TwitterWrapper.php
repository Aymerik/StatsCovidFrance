<?php

namespace StatsCovidFrance;


use DG\Twitter\Exception;
use DG\Twitter\Twitter;

class TwitterWrapper
{
    private $config;
    private $twitter;

    public function __construct($config)
    {
        $this->config = parse_ini_file($config);
    }

    public function send($string)
    {
        if($this->twitter == null) {
            try {
                $this->twitter = new Twitter($this->config['CONSUMER_KEY'], $this->config['CONSUMER_SECRET'], $this->config['ACCESS_TOKEN'], $this->config['ACCESS_TOKEN_SECRET']);
            } catch(Exception $e) {
                echo $e->getMessage();
            }
            $this->twitter->send($string);
        }
    }


}