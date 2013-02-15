if ($pref['advrank_enable_forum'] == "1"){

global $post_info, $sql;

$postowner  = $post_info['user_id'];



$sql->db_Select("aacgc_roster_adv_members", "*", "WHERE user_id='".$postowner."' LIMIT 0,".$pref['numrank']."", "");
while($row = $sql->db_Fetch()){

$sql2 = new db;
$sql2->db_Select("aacgc_roster_adv", "*", "WHERE rank_id='".$row['awarded_rank_id']."'", "");
while($row2 = $sql2->db_Fetch()){



$advforumrank .= "<br><img width='".$pref['advrank_forum_img']."' src='".e_PLUGIN."aacgc_roster_adv/ranks/".$row2['rank_pic']."'></img><br>";}}}







return "".$advforumrank."";




