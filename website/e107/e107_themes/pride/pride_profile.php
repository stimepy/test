<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Angelofdoom
 * Date: 3/14/12
 * Time: 4:47 PM
 * This is where I deal with creating the "template" for avatars.
 */

/*

*/

function avatar_hell(){
    global $config;

    IncludePHPBB(true);
    include_once(PHPBB_ROOT_PATH.'includes/functions_user.php');

     $avatar_combo=0;

    if($config['allow_avatar']){
        if($config['allow_avatar_local']){
            $avatar_combo+=1;
        }
        if($config['allow_avatar_remote']){
            $avatar_combo+=2;
        }
        if($config['allow_avatar_upload']){
            $avatar_combo+=4;
        }
        if($config['allow_avatar_remote_upload']){
            $avatar_combo+=8;
        }
    }
    $remote =false;
    $text ="<tr>
        <td colspan=\"2\" style=\"text-align:center\" class=\"forumheader2\">
            Avatar Setting:
         </td>
    </tr>";

    /*
    * Avatar combo values defined:
    * 1 = local only
    * 2 = remote;
    * 3 = local + remote
    * 4 = file uploaded
    * 5 = local + file upload
    * 6 = remote + file upload
    * 7 = local + remote + file upload
    * 8 = remote uploaded
    * 9 = local + remote uploaded
    * 10 =  remote uploaded + remote
    * 12 = remote uploaded + file uploaded
    * 15 = All
    */
    switch($avatar_combo){
        case 0://No avatar option, exit immediately
            return '';
            break;
        case 1:
            $text .=av_local();
            break;
        case 2:
            $text .= av_remote();
        case 3:
            $text.= av_remote();
            $text.= av_local();
            break;
        case 8:
        case 12:
            $remote = true;
        case 4:
            $text .= av_upload($remote);
            break;
        case 9:
            $remote=true;
        case 5:
            $text .= av_upload($remote);
            $text .= av_local();
            break;
        case 10:
            $remote=true;
        case 6:
            $text .= av_remote();
            $text .= av_upload($remote);
            break;
        case 15:
            $remote =true;
        case 7:
            $text .= av_remote();
            $text .= av_upload($remote);
            $text .= av_local();
            break;
    }
    return $text;

}

function av_local(){
    global $curVal;
    //get the avatars from the gallary
    $avatars = avatar_gallery('default', $curVal['user_image'],4,'avatar_row', true);
    //comes back informat array([0]=>array([0]=>stuff)))  Begone first array!
                            //   ^ row      ^ pic
    $text ="<tr>
		<th class=\"forumheader3\" colspan=\"2\">Local gallery</th>
	</tr>
	<tr>
		<td style=\"width: 40%;\" class=\"forumheader3\" colspan=\"2\" align=\"center\" valign=\"middle\">&nbsp;</td>
    </tr>
    <tr>
        <td style=\"width: 40%;\" class=\"forumheader3\" colspan=\"2\" align=\"center\">
            <table cellspacing=\"1\" cellpadding=\"4\" border=\"0\">";
    //<!-- BEGIN avatar_row -->
    foreach($avatars as $rows){
        $text .="<tr>";
          foreach($rows as $ava_pic){
            //[file] => default/file.png [filename] => file.png [name] => File )
    //<!-- BEGIN avatar_column -->
                   $text.="<td class=\"row1\" align=\"center\"><img src=\"".PHPBB_ROOT_PATH."images/avatars/gallery/".$ava_pic['file']."\" alt=\"".$ava_pic['name']."\" title=\"".$ava_pic['name']."\" />
                   </td>";
                 //<!-- END avatar_column -->
          }

    $text .="<tr>";
         foreach($rows as $ava_pic){
             $sel='';
             if($curVal['user_image']==$ava_pic['file']){
                 $sel='checked="checked"';
             }
    //<!-- BEGIN avatar_option_column -->
            $text .="<td class=\"row2\" align=\"center\"><input type=\"radio\" class=\"radio\" name=\"avatar_select\" value=\"".$ava_pic[filename]."\" ".$sel."/>
             </td>";
         //<!-- END avatar_option_column -->
        }

    $text .="</tr>";
    //<!-- END avatar_row -->
    }
    $text .="<br /></table>
    </td>
    </tr>";//*/

    return $text;

}

function av_remote(){
    global $curVal;
    return "<tr>
            <td style=\"text-align:center\" class=\"forumheader3\" align=\"center\"><input class='tbox' type='text' name='remotelink' size='60' value='".$curVal['user_image']."' maxlength='100' /> <br /></td>
        </tr>";
}

function av_upload(){
    global $curVal;
    return "<tr>
        <td style=\"text-align:center\" class=\"forumheader3\" align=\"center\">
            <input class='tbox' type='readonly' name='curravatar' size='60' value='".$curVal['user_image']."' maxlength='100' /> <br />
    <input class='tbox' name='uploadfile' type='file' size='47' /> <br /> </td>
    </tr>";
}



?>