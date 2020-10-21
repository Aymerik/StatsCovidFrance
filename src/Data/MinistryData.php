<?php


namespace StatsCovidFrance\Data;


class MinistryData
{

    private $today;
    private $yesterday;
    private $lastWeek;
    private $yesterdayLastWeek;
    private $twoWeeksAgo;


    private function __construct(SingleDayMinistryData $today, SingleDayMinistryData $yesterday, SingleDayMinistryData $lastWeek, SingleDayMinistryData $yesterdayLastWeek, SingleDayMinistryData $twoWeeksAgo)
    {
        $this->today = $today;
        $this->yesterday = $yesterday;
        $this->lastWeek = $lastWeek;
        $this->yesterdayLastWeek = $yesterdayLastWeek;
        $this->twoWeeksAgo = $twoWeeksAgo;
    }

    /**
     * @return mixed
     */
    public function getTotalCases()
    {
        return $this->today->getTotalCases();
    }

    public function getTotalDeaths()
    {
        return $this->getTotalEhpadDeaths() + $this->getTotalHospitalDeaths();
    }

    /**
     * @return mixed
     */
    public function getTotalEhpadDeaths()
    {
        return $this->today->getTotalEhpadDeaths();
    }

    /**
     * @return mixed
     */
    public function getTotalHospitalDeaths()
    {
        return $this->today->getTotalHospitalDeaths();
    }

    /**
     * @return mixed
     */
    public function getNewHospitalized()
    {
        return $this->today->getNewHospitalized();
    }

    /**
     * @return mixed
     */
    public function getNewReanimated()
    {
        return $this->today->getNewReanimated();
    }

    /**
     * @return mixed
     */
    public function getHospitalized()
    {
        return $this->today->getHospitalized();
    }

    /**
     * @return mixed
     */
    public function getReanimated()
    {
        return $this->today->getReanimated();
    }

    public function getNewCases()
    {
        return $this->getTotalCases() - $this->yesterday->getTotalCases();
    }

    public function getNewCasesLastWeek()
    {
        return $this->lastWeek->getTotalCases() - $this->yesterdayLastWeek->getTotalCases();
    }

    public function getNewEhpadDeaths()
    {
        return $this->getTotalEhpadDeaths() - $this->yesterday->getTotalEhpadDeaths();
    }

    public function getNewHospitalDeaths()
    {
        return $this->getTotalHospitalDeaths() - $this->yesterday->getTotalHospitalDeaths();
    }

    public function getNewHospitalDeathsLastWeek()
    {
        return $this->lastWeek->getTotalHospitalDeaths() - $this->yesterdayLastWeek->getTotalHospitalDeaths();
    }

    public function getNewEhpadDeathsLastWeek()
    {
        return $this->lastWeek->getTotalEhpadDeaths() - $this->yesterdayLastWeek->getTotalEhpadDeaths();
    }

    public function getNewDeaths()
    {
        return $this->getNewEhpadDeaths() + $this->getNewHospitalDeaths();
    }

    public function getNewDeathsLastWeek()
    {
        return $this->getNewEhpadDeathsLastWeek() + $this->getNewHospitalDeathsLastWeek();
    }

    public function getCasesProgression()
    {
        return ($this->getNewCases() - $this->getNewCasesLastWeek()) / $this->getNewCasesLastWeek() * 100;
    }

    public function getDeathsProgression()
    {
        return ($this->getNewDeaths() - $this->getNewDeathsLastWeek()) / $this->getNewDeathsLastWeek() * 100;
    }

    public function getNewHospitalizedLastWeek()
    {
        return $this->lastWeek->getNewHospitalized();
    }

    public function getNewReanimatedLastWeek()
    {
        return $this->lastWeek->getNewReanimated();
    }

    public function getHospitalizedProgression()
    {
        return ($this->getNewHospitalized() - $this->getNewHospitalizedLastWeek()) / $this->getNewHospitalizedLastWeek() * 100;
    }

    public function getReanimatedProgression()
    {
        return ($this->getNewReanimated() - $this->getNewReanimatedLastWeek()) / $this->getNewReanimatedLastWeek() * 100;
    }

    public function getHospitalBalance()
    {
        return $this->getHospitalized() - $this->yesterday->getHospitalized();
    }

    public function getReanimationBalance()
    {
        return $this->getReanimated() - $this->yesterday->getReanimated();
    }

    public function getDate()
    {
        return $this->today->getDate();
    }

    public function getR()
    {
        $casesR = ($this->today->getTotalCases()-$this->lastWeek->getTotalCases())/($this->lastWeek->getTotalCases() - $this->twoWeeksAgo->getTotalCases());
        $hospitalR = ($this->today->getHospitalized()-$this->lastWeek->getHospitalized()+$this->today->getCured()-$this->lastWeek->getCured())/($this->lastWeek->getHospitalized()+$this->lastWeek->getCured()-$this->twoWeeksAgo->getHospitalized()-$this->twoWeeksAgo->getCured());

        return round(($casesR + $hospitalR)/2,2);
    }

    public static function load(\DateTimeInterface $date)
    {
        $currentData = SingleDayMinistryData::load($date);
        if($currentData) { // on ne charge les données que si les données du jour sont publiées
            $yesterdayData = SingleDayMinistryData::load($date->sub(new \DateInterval('P1D')));
            $lastWeekData = SingleDayMinistryData::load($date->sub(new \DateInterval('P7D')));
            $yesterdayLastWeekData = SingleDayMinistryData::load($date->sub(new \DateInterval('P8D')));
            $twoWeeksAgo = SingleDayMinistryData::load($date->sub(new \DateInterval('P14D')));
        }
        if ($currentData && $yesterdayData && $lastWeekData && $yesterdayLastWeekData && $twoWeeksAgo) {
            return new MinistryData($currentData, $yesterdayData, $lastWeekData, $yesterdayLastWeekData, $twoWeeksAgo);
        } else {
            return false;
        }
    }
}