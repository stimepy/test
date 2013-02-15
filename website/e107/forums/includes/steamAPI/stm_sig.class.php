<?php
/**
 * Created by JetBrains PhpStorm.
 * User: EC
 * Date: 4/27/12
 * Time: 11:00 AM
 * To change this template use File | Settings | File Templates.
 */
class stm_sig
{
    private $background;//500 x 94  or
    private $mhieght;
    private $mwidth;
    private $steam;

    /**
     * @param string $main
     * @param string $avatar
     * @param $steamInfo
     */
    public function __construct($main, $steamInfo){
        $this->background=$main;
        $temp_size=getimagesize($main);
        $this->mhieght=$temp_size[1];
        $this->mwidth=$temp_size[0];
        $this->steam=$steamInfo;
    }

    function createSignature(){
        try{
            $text= "<a href='". $this->steam->baseUrl() ."' class='stm_no'>
                <img class='stm_bck' src='". $this->background ."' height='94' width='500' />
                    <img class='stm_av' src='". $this->steam->getInfo('avatarMedium') ."' height='64' width='64' />
                    <div class='stm_txt'>
                        User: ". $this->steam->getInfo('steamID') ."<br />
                        Hours of game time in the last 2 weeks: ". $this->steam->getInfo('hoursPlayed2Wk') ." Hrs <br />
                        Online Status: ". $this->steam->getInfo('onlineState') ." <br />
                        ". $this->getGameImages() ."
                    </div>
                <!-- end img for background of sig -->
            </a>";
        }
        catch(SteamAPIException $e){
            $e->displayException();
            die();
        }

        return $text;

    }


    /*example

     <a href=linktosteamprofile>
        <img class=imgbackground|zindexof-1>
        <img class=placementonbackgroundwithpadding|possiblyfloat?|zindex1> <div class=textalignleft> username,<br /> hours played(this week) <br /> 3games

    </a>
    </div>*/

    /**
     * @return null|string
     * Grabs the mostplayed games array from the steam profile, grabs the 3 listed game icons and sets them as src img
     */
    function getGameImages(){
        $games=$this->steam->getInfoArray('mostPlayedGames');
        $text = NULL;
        foreach($games as $game){   //gameLogoSmall
            $text .= "<img src='". $game['gameIcon'] ."' /> &nbsp; ";
        }
        return $text;
    }

    function setUser($steam){
        $this->steam = $steam;
    }
}
