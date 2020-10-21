<?php


namespace StatsCovidFrance;


use Symfony\Component\Yaml\Yaml;

class Updater
{
    private $dates;
    private $url;

    public function __construct($url)
    {
        $this->dates = Yaml::parseFile($url);
        $this->url = $url;
    }

    public function isUpToDate($type, $date)
    {
        return $this->dates['data'][$type] >= $date;
    }

    public function update($type, $date)
    {
        $this->dates['data'][$type] = $date;
    }

    public function save()
    {
        $yaml = Yaml::dump($this->dates);
        file_put_contents($this->url, $yaml);
    }
}