<?php


namespace StatsCovidFrance\Data;


use Symfony\Component\Yaml\Yaml;

class SingleDayMinistryData
{
    private $date;
    private $totalCases;
    private $totalEhpadDeaths;
    private $totalHospitalDeaths;
    private $newHospitalized;
    private $newReanimated;
    private $hospitalized;
    private $reanimated;
    private $cured;

    private function __construct(\DateTimeInterface $date, array $data)
    {
        $this->date = $date;
        $this->totalCases = $data['donneesNationales']['casConfirmes'];
        $this->totalEhpadDeaths = $data['donneesNationales']['decesEhpad'];
        $this->totalHospitalDeaths = $data['donneesNationales']['deces'];
        $this->newHospitalized = $data['donneesNationales']['nouvellesHospitalisations'];
        $this->newReanimated = $data['donneesNationales']['nouvellesReanimations'];
        $this->hospitalized = $data['donneesNationales']['hospitalises'];
        $this->reanimated = $data['donneesNationales']['reanimation'];
        $this->cured = $data['donneesNationales']['gueris'];
    }

    /**
     * @return mixed
     */
    public function getTotalCases()
    {
        return $this->totalCases;
    }

    public function getTotalDeaths()
    {
        return $this->totalHospitalDeaths + $this->totalEhpadDeaths;
    }

    /**
     * @return mixed
     */
    public function getTotalEhpadDeaths()
    {
        return $this->totalEhpadDeaths;
    }

    /**
     * @return mixed
     */
    public function getTotalHospitalDeaths()
    {
        return $this->totalHospitalDeaths;
    }

    /**
     * @return mixed
     */
    public function getNewHospitalized()
    {
        return $this->newHospitalized;
    }

    /**
     * @return mixed
     */
    public function getNewReanimated()
    {
        return $this->newReanimated;
    }

    /**
     * @return mixed
     */
    public function getHospitalized()
    {
        return $this->hospitalized;
    }

    /**
     * @return mixed
     */
    public function getReanimated()
    {
        return $this->reanimated;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getCured()
    {
        return $this->cured;
    }

    public static function load(\DateTimeInterface $date)
    {
        $data = Yaml::parse(file_get_contents('https://raw.githubusercontent.com/opencovid19-fr/data/master/ministere-sante/' . $date->format('Y-m-d') . '.yaml', false, stream_context_create(['http' => ['ignore_errors' => true]])));
        if(isset($data['404']) || !isset($data['donneesNationales']) || !isset($data['donneesNationales']['casConfirmes'])) {
            return false;
        } else {
            return new SingleDayMinistryData($date, $data);
        }
    }
}