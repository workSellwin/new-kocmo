<?php


namespace Kocmo\Exchange;


class Agents
{
    static public function start($step = 0, $status = 'end'){

        $bx = StaticFactory::factory($step);

        if($bx) {
            $flag = $bx->update();

            if ($bx->getStatus() == 'run') {

                $status = 'run';
            }
            elseif ($bx->getStatus() == 'end') {

                $status = 'end';
                $step = StaticFactory::nextStep($step);
            }
        }

        if($status == 'end'){
            return '';
        }
        return 'Kocmo\\Exchange\\Agents::start(' . "$step, '$status'" . ');';
    }

    static public function actualizeStock(){

        $dt = new \DateTime();
        $hour = $dt->format('G');

        if($hour > 20 || $hour < 8){
            return 'Kocmo\\Exchange\\Agents::actualizeStock();';
        }

        $bx = StaticFactory::factory(60);
        $bx->actualizeStock();

        return 'Kocmo\\Exchange\\Agents::actualizeStock();';
    }

    static public function actualizePrices(){

        $bx = StaticFactory::factory(80);
        $bx->update();

        return 'Kocmo\\Exchange\\Agents::actualizePrices();';
    }

    static public function updateStock(){

        \CAgent::AddAgent(
            "Kocmo\Exchange\Agents::start(60);",
            "kocmo.exchange",
            "N",
            40
        );

        return 'Kocmo\\Exchange\\Agents::updateStock();';

    }
}