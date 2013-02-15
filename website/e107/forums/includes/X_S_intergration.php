<?php
/**
 * Created by JetBrains PhpStorm.
 * @author: Kris Sherrerd
 * Date: 4/24/12
 * @copyright 2012 Kris Sherrerd
 * @package phpbb3
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

if (!defined('IN_PHPBB'))
{
    exit;
}
/**
 * @param $x_style
 * @return string
 * Takes the style as determined by the player and sets appropriately
 */

class xsintergration{

    var $xfirestat=0;

    /**
     * @static
     * @param $x_style
     * @return string
     *
     * return the style of the xfire.
     */
    public function determinexfiresty($x_style){
        switch($x_style){
            case 'Shadow':
                $sty='sh';
                break;
            case 'Combat':
                $sty= 'co';
                break;
            case 'Sci-fi':
                $sty= 'sf';
                break;
            case 'Fantasy':
                $sty= 'os';
                break;
            case 'World of Warcarft':
                $sty= 'wow';
                break;
            default:
                $sty= 'bg';
                break;
        };
        xsintergration::updatusecnt('xfire');
        return $sty;
    }

    /**
     * @param $display
     * @return string
     *
     * Figures out what display you are using.  returns a string with display type, hieght, width seperated by a '.'.
     */
    public function determinxfiredis($display){
        switch($display){
            case 'Mini':
                $display= "4.16.16";
                break;
            case 'Tiny':
                $display= "3.149.29";
                break;
            case 'Short and wide':
                $display= "2.450.34";
                break;
            case 'Compact':
                $display= "1.277.63";
                break;
            case 'classic':
            default:
                $display= "0.440.111";
                break;
        };
        $this->updatusecnt('xfire');
        return $display;
    }

    /**
     * @return bool
     *
     * Simply makes sure all information has been made.
     */
    public function determinxfirok(){
        global $user;
        if($this->xfirestat == 2){
            $user->add_lang('mods/xfire');
            return true;
        }
        return false;
    }

    /**
     * @param $what
     * @return bool
     *
     * $what determines which var to update in the class with a count.
     */
    private function updatusecnt($what){
        if($what==NULL){
            return false;
        }
        switch($what){
            case 'xfire':
                $this->xfirestat++;
                break;
        };
        return true;
    }



}