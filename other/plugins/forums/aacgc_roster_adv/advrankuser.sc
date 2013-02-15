global $sql,$sql2,$user; 

$suser = "";
$USER_ID = "";


$url = $_SERVER["REQUEST_URI"];
$suser = explode(".", $url);
	if ($suser[1] == 'php?id') {
	$suser = $suser[2];
	}
$SUSER_ID = $suser;

if (USER){


//----------------------------------------------------------------

if ($pref['advrank_enable_profile'] == "1"){

$sql->db_Select("aacgc_roster_adv_members", "*", "WHERE user_id='".$SUSER_ID."'", "");
while($row = $sql->db_Fetch()){

$sql2 = new db;
$sql2->db_Select("aacgc_roster_adv", "*", "WHERE rank_id='".$row['awarded_rank_id']."'", "");
while($row2 = $sql2->db_Fetch()){


if ($row2['rank_id'] == ""){
$advranksu = "";}
else
{
	
	$advranksu .= "<tr>
                    <td colspan=2 class='forumheader3'><img width='".$pref['advrank_profile_img']."' src='".e_PLUGIN."aacgc_roster_adv/ranks/".$row2['rank_pic']."' width='75' align='middle' alt = ''></img></td>
                    </tr>";}


//-------------------------------------------------------------------------------------



}}}}







return "".$advranksu."";


