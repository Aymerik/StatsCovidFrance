<?php


namespace StatsCovidFrance\Renderer;


use StatsCovidFrance\Data\MinistryData;

class MinistryRenderer
{
    private $ministryData;

    public function __construct(MinistryData $ministryData)
    {
        $this->ministryData = $ministryData;
    }

    private function renderProgression($progression)
    {
        return (($progression > 0) ? "📈 +" : "📉 ") . round($progression) . '%';
    }

    private function renderNumber($number)
    {
        return (($number > 0) ? '+' : '') . $number;
    }


    public function render()
    {
        $ehpadDeaths = $this->ministryData->getNewEhpadDeaths() > 0 ? " dont " . $this->ministryData->getNewEhpadDeaths() . " en EHPAD" : "";
        return <<<EOL
Stats Covid France - {$this->ministryData->getDate()->format('d/m')}

🦠 +{$this->ministryData->getNewCases()} cas ({$this->ministryData->getNewCasesLastWeek()} cas J-7) {$this->renderProgression($this->ministryData->getCasesProgression())}
{$this->ministryData->getTotalCases()} au total

🛌 {$this->ministryData->getNewHospitalized()} hospitalisés ({$this->ministryData->getNewHospitalizedLastWeek()} J-7) {$this->renderProgression($this->ministryData->getHospitalizedProgression())}
Solde : {$this->renderNumber($this->ministryData->getHospitalBalance())} ({$this->ministryData->getHospitalized()} au total)

🏥 {$this->ministryData->getNewReanimated()} réanimations ({$this->ministryData->getNewReanimatedLastWeek()} J-7) {$this->renderProgression($this->ministryData->getReanimatedProgression())}
Solde : {$this->renderNumber($this->ministryData->getReanimationBalance())} ({$this->ministryData->getReanimated()} au total)

🪦 {$this->ministryData->getNewDeaths()} décès{$ehpadDeaths} ({$this->ministryData->getNewDeathsLastWeek()} J-7) {$this->renderProgression($this->ministryData->getDeathsProgression())}
EOL;
    }
}