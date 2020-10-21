<?php


namespace StatsCovidFrance\Data;


class GouvData
{
    const CAPACITIES = ['REG-11' => ['name' => 'IDF', 'beds' => 1147], 'REG-24' => ['name' => 'Centre', 'beds' => 180], 'REG-27' => ['name' => 'Bourgogne FC', 'beds' => 198], 'REG-28' => ['name' => 'Normandie', 'beds' => 240], 'REG-32' => ['name' => 'HDF', 'beds' => 438], 'REG-44' => ['name' => 'Grand Est', 'beds' => 465], 'REG-52' => ['name' => 'PdL', 'beds' => 181], 'REG-53' => ['name' => 'Bretagne', 'beds' => 162], 'REG-75' => ['name' => 'NA', 'beds' => 412], 'REG-76' => ['name' => 'Occitanie', 'beds' => 474], 'REG-84' => ['name' => 'Auvergne RA', 'beds' => 559], 'REG-93' => ['name' => 'PACA', 'beds' => 460], 'REG-94' => ['name' => 'Corse', 'beds' => 18]];
    const FRENCH_CAPACITY = 4934;

    private $usedPlaces = 0;
    private $occupation;
    private $date;

    private function __construct(array $data, \DateTimeInterface $date)
    {
        $this->date = $date;
        $this->occupation = array_filter(array_map(function ($v) {
            global $totalPlaces;
            if (isset(self::CAPACITIES[$v->code])) {
                $this->usedPlaces += $v->reanimation;
                return ['name' => self::CAPACITIES[$v->code]['name'], 'occupation' => round($v->reanimation / self::CAPACITIES[$v->code]['beds'] * 100)];
            }
        }, $data));
        usort($this->occupation, function ($a, $b)
        {
            return -$a['occupation'] + $b['occupation'];
        });
    }

    public function getFrenchOccupation()
    {
        return round($this->usedPlaces / self::FRENCH_CAPACITY * 100);
    }

    public function getOccupation()
    {
        return $this->occupation;
    }

    public function getDate()
    {
        return $this->date;
    }

    public static function load(\DateTimeInterface $date)
    {
        $data = json_decode(file_get_contents('https://raw.githubusercontent.com/opencovid19-fr/data/master/dist/chiffres-cles.json'));
        $data = array_filter($data, function ($obj) use ($date)
        {
            return ($obj->date == $date->format('Y-m-d') && strpos($obj->code, 'REG') === 0);
        });

        if(count($data) == 0)
            return false;

        return new GouvData($data, $date);
    }
}