<?php
/***
 * @class ExtendedFields
 * This class is to create the fields as required for all extended profiles.  This uses the phpbb table
 * phpbb_profile_fields_lang.  The goal is to create each individual field as defined in phpbb, using snips of code from
 * phpbb, as well as creating code unique to the e107 process.  This will literally create the fill in fields and the
 * non-fill in fields (such as seen in profile views).
 *
 * @version 0.1
 * @Copyright Kris Sherrerd 2012  Original Code take from phpbb functions_profile_fields.php  Copyright phpBB 2005
 *
 */

class ExtendedFields{
    var $profile_types = array(1 => 'int', 2 => 'string', 3 => 'text', 4 => 'bool', 5 => 'dropdown', 6 => 'date');
    var $options_lang;
    var $info;

/**
 * @param $short_code
 * @param string $mode='register'
 * @param int $uid=NULL
 *
 * Creates the fields, and assigns them to the appropraite template.
 */

    function create_extendedfields($short_code, $mode='register', $uid=NULL){
        global $sql, $auth;
        if(!defined('IN_PHPBB')){
            define('IN_PHPBB', true);
            if(!defined('PHPBB_ROOT_PATH')){
                define('PHPBB_ROOT_PATH', './forums/');
            }
        }
        include_once(PHPBB_ROOT_PATH."common.php");

        $sql_where = '';
        switch ($mode)
        {
            case 'register':
                // If the field is required we show it on the registration page
                $sql_where .= ' AND f.field_show_on_reg = 1';
                break;

            case 'profile':
                // Show hidden fields to moderators/admins
                if (!$auth->acl_gets('a_', 'm_') && !$auth->acl_getf_global('m_'))
                {
                    $sql_where .= ' AND f.field_show_profile = 1';
                }
                break;

            default:
                trigger_error('Wrong profile mode specified', E_USER_ERROR);
                break;
        }

        if($uid!=NULL){//If a user, get the information.
            $u_data=get_user_data($uid);
        }
        //Get the field name, and language such as title and description.
        $msql = "SELECT l.*, f.*
			FROM e107_phpbb_profile_lang l right outer join e107_phpbb_profile_fields f
			ON (l.lang_id = 1
				AND l.field_id = f.field_id)
			WHERE f.field_active = 1
				{$sql_where}
			ORDER BY f.field_order";

        $result = $sql->db_Select_gen($msql);
        if($result){
            $text='';
            $ex_fields=$sql->db_getList();
            foreach($ex_fields as $row){ //make sure the field_ident is = to table name.
                $row['field_ident'] = 'pf_' . $row['field_ident'];

                //figure out what type of field we are making.
                $type_func = 'generate_' . $this->profile_types[$row['field_type']];
                // Make that field.  (Calls to message hander in phpBB3 first.)
                $text.=$this->$type_func($row, $short_code, $u_data);

            }
        }

        return ($text!='')? $text : false;
    }


    /**
     * @param array $profile_row
     * @param string $mode='register'
     * @param array $user_info=NULL
     *
     * Generates either two radio buttons, or one check box, pending the fields length.
     */
    private function generate_bool(&$profile_row, $short_code, $user_info=NULL)
    {
        $value = (isset($_REQUEST[$profile_row['field_ident']])) ? request_var($_REQUEST[$profile_row['field_ident']], '' ) : ((!$user_info[$profile_row['field_ident']] ) ? $profile_row['field_default_value'] : $user_info[$profile_row['field_ident']]);

        $profile_row['field_value'] = $value;
        $option[$profile_row['field_ident']][0]=$profile_row['lang_name'].":<br />".$profile_row['lang_explain'];
        $option[$profile_row['field_ident']][1]=$this->GenerateRequired($profile_row['field_required']);

        switch($profile_row['field_length']){
            case 1:// radio buttons
                if (!isset($this->options_lang[$profile_row['field_id']][$profile_row['lang_id']]) || !sizeof($this->options_lang[$profile_row['field_id']][$profile_row['lang_id']]))
                {
                    $this->get_option_lang($profile_row['field_id'], $profile_row['lang_id'], FIELD_BOOL);
                }
                $op=1;
                $text='';

                foreach ($this->options_lang[$profile_row['field_id']][$profile_row['lang_id']] as $option_id => $option_value)
                {
                    $text .="<input type=\"radio\" name=\"{$profile_row['field_ident']}\" value=\"{$op}\" ".(($value == $option_id) ? 'checked': '')." />{$option_value}";
                    $op++;
                }
                $option[$profile_row['field_ident']][2]=$text;
                break;
            case 2:// Check box
                $option[$profile_row['field_ident']][2]="<input type=\"checkbox\" name=\"{$profile_row['field_ident']}\" value=\"1\" ".(($profile_row['field_value'] == 1)? 'checked':'')." />";
                break;

        }//end switch
        return $this->createform($profile_row['field_ident'] ,$option , $short_code);

    }


    /**
     * @param $profile_row
     * @param $short_code
     * @param null $user_info
     * @return bool|mixed
     *
     * Generates a drop down menu for the date, with seperate units for day, month, year
     */
    private function generate_date(&$profile_row, $short_code, $user_info=NULL)
    {
        $user_ident = $profile_row['field_ident'];

        $now = getdate();

        if (!isset($_REQUEST[$profile_row['field_ident'] . '_day']))
        {
            if ($profile_row['field_default_value'] == 'now')
            {
                $profile_row['field_default_value'] = sprintf('%2d-%2d-%4d', $now['mday'], $now['mon'], $now['year']);
            }
            list($day, $month, $year) = explode('-', ((!isset($user_info[$user_ident])) ? $profile_row['field_default_value'] : $user_info[$user_ident]));
        }
        else
        {
            //todo:  make it so this can be a path.
            if (false && $profile_row['field_default_value'] == 'now')
            {
                $profile_row['field_default_value'] = sprintf('%2d-%2d-%4d', $now['mday'], $now['mon'], $now['year']);
                list($day, $month, $year) = explode('-', ((!isset($user_info[$user_ident]) || false) ? $profile_row['field_default_value'] : $user_info[$user_ident]));
            }
            else
            {
                $day = request_var($profile_row['field_ident'] . '_day', 0);
                $month = request_var($profile_row['field_ident'] . '_month', 0);
                $year = request_var($profile_row['field_ident'] . '_year', 0);
            }
        }

        $profile_row['s_day_options'] = '<select name="'.$profile_row['field_ident'].'_day" ><option value="0"' . ((!$day) ? ' selected="selected"' : '') . '>--</option>';
        for ($i = 1; $i < 32; $i++)
        {
            $profile_row['s_day_options'] .= '<option value="' . $i . '"' . (($i == $day) ? ' selected="selected"' : '') . ">$i</option>";
        }
        $profile_row['s_day_options'].='</select>';

        $profile_row['s_month_options'] = '<select name="'.$profile_row['field_ident'].'_month" ><option value="0"' . ((!$month) ? ' selected="selected"' : '') . '>--</option>';
        for ($i = 1; $i < 13; $i++)
        {
            $profile_row['s_month_options'] .= '<option value="' . $i . '"' . (($i == $month) ? ' selected="selected"' : '') . ">$i</option>";
        }
        $profile_row['s_month_options'].='</select>';

        $profile_row['s_year_options'] = '<select name="'.$profile_row['field_ident'].'_year" ><option value="0"' . ((!$year) ? ' selected="selected"' : '') . '>--</option>';
        for ($i = $now['year'] - 100; $i <= $now['year'] + 100; $i++)
        {
            $profile_row['s_year_options'] .= '<option value="' . $i . '"' . (($i == $year) ? ' selected="selected"' : '') . ">$i</option>";
        }
        $profile_row['s_year_options'].='</select>';
        unset($now);

        $profile_row['field_value'] = 0;

        $option[$profile_row['field_ident']][0]=$profile_row['lang_name'];
        $option[$profile_row['field_ident']][1]=$this->GenerateRequired($profile_row['field_required']);
        $option[$profile_row['field_ident']][2]=LAN_SIGNUP_107.$profile_row['s_day_options'].LAN_SIGNUP_108.$profile_row['s_month_options'].LAN_SIGNUP_109.$profile_row['s_year_options'];

        return $this->createform($profile_row['field_ident'], $option, $short_code);

    }


    /**
     * @param $profile_row
     * @param $short_code
     * @param null $user_info
     * @return bool|mixed
     *
     * Generates a drop down menu
     */
    private function generate_dropdown(&$profile_row, $short_code, $user_info=NULL)
    {
        $value = $this->get_var($profile_row, $user_info);

        if (!isset($this->options_lang[$profile_row['field_id']]) || !isset($this->options_lang[$profile_row['field_id']][$profile_row['lang_id']]) || !sizeof($this->options_lang[$profile_row['field_id']][$profile_row['lang_id']]))
        {
            $this->get_option_lang($profile_row['field_id'], $profile_row['lang_id'], FIELD_DROPDOWN);
        }
        $text="<select name=\"{$profile_row['field_ident']}\" >";
        $option[$profile_row['field_ident']][0]=$profile_row['lang_name'].":<br />".$profile_row['lang_explain'];
        $option[$profile_row['field_ident']][1]=$this->GenerateRequired($profile_row['field_required']);
        foreach ($this->options_lang[$profile_row['field_id']][$profile_row['lang_id']] as $option_id => $option_value)
        {
            $text .= "<option value=\"{$option_id}\" ".(($value == $option_id) ? ' selected="selected"' : '').">$option_value</option>";
        }
        $option[$profile_row['field_ident']][2]=$text."</select>";

        return $this->createform($profile_row['field_ident'],$option,$short_code);

    }

    /**
     * @param $profile_row
     * @param $short_code
     * @param null $user_info
     *
     * Generates a simple text box
     */
    private function generate_int(&$profile_row, $short_code, $user_info=NULL)
    {
        $profile_row['field_value'] = $this->get_var($profile_row, $user_info);
        $option[$profile_row['field_ident']][0]=$profile_row['lang_name'].":<br />".$profile_row['lang_explain'];
        $option[$profile_row['field_ident']][1]=$this->GenerateRequired($profile_row['field_required']);
        $option[$profile_row['field_ident']][2]="<input type=\"text\" name=\"{$profile_row['field_ident']}\" value=\"{$profile_row['field_value']}\">";

        return $this->createform($profile_row['field_ident'], $option, $short_code);
    }

    /**
     * @param $profile_row
     * @param $short_code
     * @param null $user_info
     *
     * Ibid
     */
    private function generate_string(&$profile_row, $short_code, $user_info=NULL)
    {
        return $this->generate_int($profile_row, $short_code, $user_info);
    }

    /**
     * @param $profile_row
     * @param $short_code
     * @param null $user_info
     * @return bool|mixed
     *
     * Generates a text area.
     */
    private function generate_text(&$profile_row, $short_code, $user_info=NULL)
    {
         $size=explode('|',$profile_row['field_length']);//figure our rows and cols of the textfield;

        $profile_row['field_value'] = $this->get_var($profile_row, $user_info);

        $option[$profile_row['field_ident']][0]=$profile_row['lang_name'].":<br />".$profile_row['lang_explain'];
        $option[$profile_row['field_ident']][1]=$this->GenerateRequired($profile_row['field_required']);
        $option[$profile_row['field_ident']][2]="<textarea name=\"{$profile_row['field_ident']}\" rows=\"{size[0]}\" cols=\"{$size[1]}\">{$profile_row['field_value']}</textarea>";

        return $this->createform($profile_row['field_ident'],$option,$short_code);
    }


    /**
     * @param $field_id
     * @param $lang_id
     * @param $field_type
     *
     * Get language entries for options and store them here for later use
     */
    function get_option_lang($field_id, $lang_id, $field_type){
        global $sql;

         $query = "SELECT option_id, lang_value
				FROM #phpbb_profile_fields_lang
					WHERE field_id = {$field_id}
					AND lang_id = {$lang_id}
					AND field_type = {$field_type}
				ORDER BY option_id";

        $result = $sql->db_Select_gen($query);

        while ($row = $sql->db_Fetch())
        {
            $this->options_lang[$field_id][$lang_id][($row['option_id'] + 1)] = $row['lang_value'];
        }
    }

    /**
     * @param $field_ident
     * @param $text_array
     * @param $code
     * @return bool|mixed
     *
     * Creates the actual form.
     */
    private function createform($field_ident, $text_array, $code){
        global $tp;
        if((!isset($code)|| !isset($text_array) || !isset($field_ident))){
            return false;
        }
        $match=$tp->searchToken($code);
        return str_replace($match[0],$text_array[$field_ident], $code );
    }

    /**
     * @param $profile
     * @param null $user_info
     * @return mixed
     *
     * Determines with variable to use.
     */
    private function get_var($profile, $user_info=NULL){
        //if the user has information via the field, grab it, else grab the default.
        return (isset($user_info[$profile['field_ident']]))?$user_info[$profile['field_ident']]:$profile['field_default_value'];
    }

    /**
     * @param int $required
     * @return string
     *
     * Simple figured out if a field is required, if yes returns a red *.
     */
    private function GenerateRequired($required){
        global $EXTENDED_USER_FIELD_REQUIRED;

        if(isset($EXTENDED_USER_FIELD_REQUIRED)){
            return ($required)?$EXTENDED_USER_FIELD_REQUIRED:'';
        }
        else{
          return '';
        }
    }
}
?>