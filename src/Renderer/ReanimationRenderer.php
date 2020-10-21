<?php


namespace StatsCovidFrance\Renderer;


use StatsCovidFrance\Data\GouvData;

class ReanimationRenderer
{
    private $gouvData;

    public function __construct(GouvData $gouvData)
    {
        $this->gouvData = $gouvData;
    }

    private function renderReanimations()
    {
        $string = '';
        foreach($this->gouvData->getOccupation() as $area) {
            $string .= $this->renderSingleReanimation($area);
        }
        return $string;
    }

    private function renderSingleReanimation($rea)
    {
        return $this->getReaIcon($rea['occupation']) . $rea['name'] . ' : ' . $rea['occupation'] . '%
';
    }

    private function getReaIcon($percent)
    {
        if ($percent >= 65) return '⚠️ ';
        if ($percent >= 50) return '🔴 ';
        if ($percent >= 30) return '🟠 ';
        if ($percent >= 20) return '🟡 ';
        return '🟢 ';
    }

    public function render()
    {
        return <<<EOL
Occupation en réanimation - {$this->gouvData->getDate()->format('d/m')} ({$this->getReaIcon($this->gouvData->getFrenchOccupation())}métropole : {$this->gouvData->getFrenchOccupation()}%)

{$this->renderReanimations()}
EOL;

    }
}