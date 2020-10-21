<?php

use StatsCovidFrance\Data\GouvData;
use StatsCovidFrance\Renderer\MinistryRenderer;
use StatsCovidFrance\Renderer\ReanimationRenderer;
use StatsCovidFrance\TwitterWrapper;
use StatsCovidFrance\Updater;
use StatsCovidFrance\Data\MinistryData;

session_start();


require_once __DIR__.'/vendor/autoload.php';

$updateFile = __DIR__.'/res/last-update.yml';
$configFile = __DIR__.'/config.ini';

$updater = new Updater($updateFile);
$twitter = new TwitterWrapper($configFile);

$dateNow = $_GET['date'] ?? $argv[2] ?? date('Y-m-d');

if ($updater->isUpToDate('ministere', $dateNow) && !isset($argv[2]))
{
    echo "stats covid : already up to date\n";
}
else
{
    $ministryData = MinistryData::load(new \DateTimeImmutable($dateNow));

    if (!$ministryData)
    {
        echo "stats covid : data not updated yet\n";
    }
    else
    {
        $ministryRenderer = new MinistryRenderer($ministryData);
        echo $ministryRenderer->render();

            if (isset($argv[1]) && $argv[1] == '--tweet')
            {
                try
                {
                    $twitter->send($ministryRenderer->render());
                    $updater->update('ministere', $dateNow);
                }
                catch(Exception $e)
                {
                    echo 'error when send to twitter : ' . $e->getMessage();
                }
            }
            else
            {
                echo "\n\n\n\n\n\n(tweet not sent)\n\n";
            }
        }

}

// Vérif des chiffres-clés
if ($updater->isUpToDate('gouv', $dateNow) && !isset($argv[2]))
{
    echo "reanimation : already up to date\n";
}
else
{
    $gouvData = GouvData::load(new \DateTimeImmutable($dateNow));
    if (!$gouvData)
    {
        echo "reanimation : not updated yet\n";
    }
    else
    {
        $gouvDataRenderer = new ReanimationRenderer($gouvData);
        echo $gouvDataRenderer->render();

        if (isset($argv[1]) && $argv[1] == '--tweet')
        {
            try
            {
                $twitter->send($gouvDataRenderer->render());
                $updater->update('gouv', $dateNow);
            }
            catch(Exception $e)
            {
                echo 'error when send to twitter : ' . $e->getMessage();
            }
        }
        else
        {
            echo "\n\n\n\n\n\n(tweet2 not sent)\n";
        }
    }
}

$updater->save();
