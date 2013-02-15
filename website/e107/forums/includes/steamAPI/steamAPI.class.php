<?php
/*
 *      Copyright 2010 Rob McFadzean <rob.mcfadzean@gmail.com>
 *      
 *      Permission is hereby granted, free of charge, to any person obtaining a copy
 *      of this software and associated documentation files (the "Software"), to deal
 *      in the Software without restriction, including without limitation the rights
 *      to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *      copies of the Software, and to permit persons to whom the Software is
 *      furnished to do so, subject to the following conditions:
 *      
 *      The above copyright notice and this permission notice shall be included in
 *      all copies or substantial portions of the Software.
 *      
 *      THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *      IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *      FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *      AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *      LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *      OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *      THE SOFTWARE.
 *      
 */


class SteamAPI {

    private $customURL;
    private $gameList;
    private $steamData;
    function version() {
        return (float) '0.1';
    }

    /**
     *  Sets the $steamID64 or CustomURL then retrieves the profile.
     * @param int $id
     * */
    function __construct($id, $steam_user) {
        if(is_numeric($id)) {
            $this->steamID64 = $id;
        } else {
            $this->customURL = strtolower($id);
        }
        $url=@parse_url($this->baseUrl());
        $host = $url['host'];
        $port = (isset($url['port'])) ? $url['port'] : 0;
        $protocol = (isset($url['scheme'])) ? $url['scheme'] : 'http';
     //   if(@fsockopen($host,$port,$err,$ertstr,1)){

        if(!$this->retrieveProfile($steam_user)){
            trigger_error("Unable to create instance of SteamAPI class!.");
        }
            return;
      //  }
        echo 'blah';
    }

    /**
     *  Creates and then returns the url to the profiles.
     *  @return string
     * */
    function baseUrl() {
        if(empty($this->customURL)) {
            return "http://steamcommunity.com/profiles/{$this->steamID64}";
        }
        else {
            return "http://steamcommunity.com/id/{$this->customURL}";
        }
    }

    /**
     *  Retrieves all of the games owned by the user
     * */
    function retrieveGames() {
        $url = $this->steamData->baseUrl() . "/games?xml=1";
        //$gameData = new SimpleXMLElement(file_get_contents($url));
        $this->gamesList = array();
        try{
            $gameData = new SimpleXMLElement(file_get_contents($url));
            if(!empty($gameData->error)) {
                throw new SteamAPIException((string) $gameData->error, 2);
            }
        }
        catch(SteamAPIException $e){
            $e->displayException();
            return false;
        }

        if(!empty($gameData->error)) {
            #throw new SteamAPIException((string) $gameData->error);
        }

        foreach($gameData->games->game as $game) {
            $g['appID'] = (string) $game->appID;
            $g['name']  = (string) $game->name;
            $g['logo'] = (string) $game->logo;
            $g['storeLink'] = (string) $game->storeLink;
            $g['hoursOnRecord'] = (float) $game->hoursOnRecord;
            $g['hoursLast2Weeks'] = (float) $game->hoursLast2Weeks;
            $this->gameList[] = $g;
            //print_r($this->gameList);
        }
    }

    /**
     *  Retrieves all of the information found on the profile.
     * */
    function retrieveProfile($steam_user) {
        $wait_time=7200; // # of seconds since last check.
        $time=time();
        //if the profile have not been updated in the last 2 hours grab the info and update.
      //  echo $steam_user['steam_last_update'].'<br />';
      //  echo time() - $steam_user['steam_last_update'] ;
        if(($time - $steam_user['steam_last_update'])>=$wait_time || $steam_user['steam_last_update']==NULL)            {
            global $db;
            $profileData = $this->getInfoXmlFromSteam();
            if($profileData->Error){
                if($steam_user['steam_last_update']!=NULL){
                    $profileData = $this->getInfoXmlFromDB($steam_user['user_id']);
                }
                if($profileData->Error){
                    //error
                    return false;
                }
            }
            if($steam_user['steam_last_update'] == NULL){
                $sql="Insert into e107_steam values({$steam_user['user_id']},'{$db->sql_escape($profileData->asXML())}', {$time})";
            }
            else{
                $sql="Update e107_steam set steam_xml='{$db->sql_escape($profileData->asXML())}', steam_last_update={$time} where user_id={$steam_user['user_id']}";
            }
            if(!$db->sql_query($sql)){
                trigger_error('Invalid Query');
            }
        }
        else{//other wise grab the profile from the database.
            $profileData = $this->getInfoXmlFromDB($steam_user['user_id']);
            if($profileData->Error){
                $profileData = $this->getInfoXmlFromSteam();
                if($profileData->Error){
                    //error
                    return false;
                }
            }
        }

        $this->steamData=$profileData;
        return true;
    }

    /**
     * @return SimpleXMLElement
     */
    private function getInfoXmlFromSteam(){
        $url = $this->baseUrl() . "/?xml=1";

        $profileData = new SimpleXMLElement(file_get_contents($url));
        if(!empty($profileData->error)) {
            $profileData = $this->XMLError();
        }
        return $profileData;
    }

    /**
     * @return SimpleXMLElement
     */
     private function getInfoXmlFromDB($user){
        global $db;
        $sql="Select steam_xml from e107_steam where user_id={$user}";
        $result = $db->sql_query($sql);
        if($data = $db->sql_fetchrow($result)){
            return new SimpleXMLElement($data['steam_xml']);
        }
        return $this->XMLError();
    }

    /**
     * @return SimpleXMLElement
     * Sets up a simple errro message.
     */
    private function XMLError(){
        return new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
                    <ErrorMessage>
                        <Error>Profile Unavailable.</Error>
                    </ErrorMessage>");
    }

    /**
     *  If there are no games in the variable it calls the retrieveGames() function, upon completion returns an array of all of the owned games and related information
     *  @return array
     * */
    function getGames() {
        if(empty($this->gameList)) {
            $this->retrieveGames();
        }
        return $this->gameList;
    }

    /**
     *  Returns the friendly name of the user. The one seen by all friends & visitors.
     *  @return string
     * */
    function getFriendlyName() {
        return $this->steamData->steamID;
    }

    /**
     *  Returns the users current state. (online,offline)
     *  @return string
     * */
    function onlineState() {
        return $this->steamData->onlineState;
    }

    /**
     *  Returns the state message of the user (EG: "Last Online: 2 hrs, 24 mins ago", "In Game <br /> Team Fortress 2")
     *  @return string
     * */
    function getStateMessage() {
        return $this->steamData->stateMessage;
    }

    /**
     *  Returns the users Vac status. 0 = Clear, 1 = Banned
     *  @return boolean
     * */
    function isBanned() {
        return $this->steamData->vacBanned;
    }

    /**
     *  Returns a link to the small sized avatar of the user (32x32)
     *  @return string
     * */
    function getAvatarSmall() {
        return $this->steamData->avatarIcon;
    }

    /**
     *  Returns a link to the medium sized avatar of the user (64x64)
     *  @return string
     * */
    function getAvatarMedium() {
        return $this->steamData->avatarMedium;
    }

    /**
     *  Returns a link to the full sized avatar of the user
     *  @return string
     * */
    function getAvatarFull() {
        return $this->steamData->avatarLarge;
    }

    /**
     *  Returns the Steam ID of the user
     *  @return int
     * */
    function getSteamID64() {
        return $this->steamData->steamID64;
    }

    /**
     *  Returns the total amount of games owned by the user
     *  @return int
     * */
    function getTotalGames() {
        return sizeof($this->gameList);
    }

    /**
     * @param $item
     * @return string
     * @throws SteamAPIException
     *
     */
    function getInfo($item){
        if(!isset($item)){
            throw new SteamAPIException('No information provided',3);
        }
        if(!isset($this->steamData->$item)){
            throw new SteamAPIException('Can not find tag:'. $item,4);
        }
       return (string)$this->steamData->$item;
    }

    /**
     * @param $item
     * @return array
     * @throws SteamAPIException
     *
     */
    function getInfoArray($item){
        if(!isset($item)){
            throw new SteamAPIException('No information provided',3);
        }//|| !is_array($item)
       if(!isset($this->steamData->$item) ){
            throw new SteamAPIException('Can not find tag:'. $item,5);
        }

        switch($item){
            case 'mostPlayedGames':
                foreach($this->steamData->mostPlayedGames->mostPlayedGame as $game){
                    $g['gameName'] = (string) $game->gameName;
                    $g['gameLink'] = (string) $game->gameLink;
                    $g['gameIcon'] = (string) $game->gameIcon;
                    $g['gameLogo'] = (string) $game->gameLogo;
                    $g['gameLogoSmall'] = (string) $game->gameLogoSmall;
                    $g['hoursPlayed'] = (string) $game->hoursPlayed;
                    $g['hoursOnRecord'] = (string) $game->hoursOnRecord;
                    $g['statsName'] = (string) $game->statsName;
                    $info[]=$g;
                }
                break;
            default:
                //Nothing atm
        }
        return $info;

    }
}

//class SteamAPIException extends Exception { }
class SteamAPIException extends Exception
{
    function __construct($message="A failure has happened.", $code=0, $previous=NULL) {
        parent::__construct($message, $code, $previous);
    }

    function displayException(){
        return "Error:".parent::getMessage()." Error code:".parent::getCode();
    }
}

?>
