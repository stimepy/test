<?php

	
	/*****************************************
	Function:ButtonNavigation
	Needs:NA
	Returns:NA
	What does it do:Creates the buttons for the site.  The buttons are .jpg pictures and the words in $buttons coraspond to the name of the files  (which could be got dynamically if needed but the button names once set should never change.)
	******************************************/
	function ButtonNavigation(){
		$buttons = array("news", "forums", "server", "rosters", "media", "join_us", "event");
		$place = array("news" =>"./index.php", "forums"=>"../modules.php?name=Forums", "server"=>"../modules.php?name=Content&pa=showpage&pid=4", "rosters"=>"./index.php?op=roster", "media"=>"./index.php?op=media", "join_us"=>"../modules.php?name=Your_Account&op=new_user", "event"=>"./index.php?op=events");
		$width = array("news" =>"137", "forums"=>"137", "server"=>"132", "rosters"=>"130", "media"=>"118", "join_us"=>"134", "event"=>"127");
		
		echo "<!-- Navigation -->
		<ol id=\"navigation\"><!--5-->";
			foreach($buttons as $button){
				echo "<li><a href=$place[$button] id=\"nav_".$button."\">
            	<img src=\"./images/nav/".$button."_hover.jpg\" alt=$button width=$width height=\"66\" /></a></li>";
            	}
		echo "</ol><!--5-->";
		
	}
	
	
		
	/*****************************************
	Function:WelcomeBox
	Needs:N/A
	Returns:N/A
	What does it do:A welcome message inviting people in and giveing out mission statement
	******************************************/
	function WelcomeBox(){
	 	$welcomemessage="<p> Welcome to Pride! We are a gaming community that is going back to gamings' roots.  Having a fantastic time in a fun and relaxed setting while beating the crap out of your friends!(or letting them slaughter you....) <p /> We don't support and particular games but have forums for the Call of Duty Series, and any other game that our members play together! <p /> Hope to see you on Teamspeak!<p />Pride-Gaming<br/>";
	 	
	 	
		echo "<!-- Welcome/Introduction text -->
			<div class=\"box left welcome_box\"><!--7-->
				<h2><!--8-->
					<strong><!--9-->
                		<img src=\"./images/headers/welcome.jpg\" alt=\"Welcome\" width=\"150\" height=\"36\" />
					</strong><!--9-->
				</h2><!--8-->
				<div class=\"content\"><!--a-->
					$welcomemessage
				</div><!--a-->
			</div><!--7-->";
	}
	
	
	/*****************************************
	Function:Banner
	Needs:N/A
	Returns:N/A
	What does it do:gets and displays the banner for the site
	******************************************/
	function Banner(){
		echo "<!-- Banner -->
		<div class=\"left banner\"><!--b-->
			<img src=\"./images/banner.jpg\" alt=\"pride-gaming\" width=\"588\" height=\"219\" />
		</div><!--b-->";
	}
		/*****************************************
	Function:LeftColumn
	Needs:N/A
	Returns:N/A
	What does it do:Were we develop and display the parts of the left column
	******************************************/
	function LeftColumn(){
		echo "<div id=\"left-column\"><!--d-->
				<!-- Roster/Team -->
				<div class=\"box\"><!--e-->
					<h2><!--f-->
						<strong><!--10-->
                    		<img src=\"./images/headers/vent.jpg\" alt=\"Ventrillo\" width=\"150\" height=\"36\" />
						</strong><!--10-->
					</h2><!--f-->
					<div class=\"content\"><!--11-->
						<iframe src=\"http://cache.www.gametracker.com/components/html0/?host=8.9.6.122:4559&bgColor=333333&fontColor=CCCCCC&titleBgColor=222222&titleColor=FF9900&borderColor=555555&linkColor=FFCC00&borderLinkColor=222222&showMap=0&currentPlayersHeight=160&showCurrPlayers=1&showTopPlayers=0&showBlogs=0&width=240\" frameborder=\"0\" scrolling=\"no\" width=\"240\" height=\"348\"></iframe>
										
					</div><!--11-->
				</div><!--e-->
				
				
		
				
			</div><!--d-->";
	}
	/*		<!-- Latest News -->
				<!--<div class=\"box latest-news\"><!--12-->
					<h2><!--13-->
							<strong><!--14-->
								<img src=\"./images/headers/server.jpg\" alt=\"Our Servers\" width=\"151\" height=\"36\" />
							</strong><!--14-->
						</h2><!--13-->
					<div class=\"content\"><!--15-->
						<ol><!--16-->
					<a href=\"http://www.gametracker.com/server_info/68.232.168.111:28960/\" target=\"_blank\"><img src=\"http://cache.www.gametracker.com/server_info/68.232.168.111:28960/b_160x400_T1_F-2.png\" border=\"0\" width=\"160\" height=\"275\" /></a>
					<br />
			
						</ol><!--16-->
					</div><!--15-->
				</div><!--12-->  -->
				*/
	/*****************************************
	Function:RightColumnNews
	Needs:N/A
	Returns:N/A
	What does it do:The right column of the index.php page (main page) It builds it and then call the appropriate functions in 
	order to get the information
	******************************************/
	function RightColumnNews(){
		echo "<div id=\"right-column\"><!--17-->
				
				<!-- Latest News -->
				<div class=\"box\"><!--18-->
					<h2><!--19-->
						<strong><!--1a-->
                    		<img src=\"./images/headers/news.jpg\" alt=\"Latest News\" width=\"151\" height=\"36\" />
						</strong><!--1a-->
					</h2><!--19-->
					<div class=\"content\"><!--1b-->
						<!-- News Subject -->";
						
						Story();
				
				echo "</div><!--1b-->	
				</div><!--18-->	
				</div><!--17-->";
	}
	
	/*****************************************
	Function:Story
	Needs:N/A
	Returns:N/A
	What does it do:Gets the news from the database, and displays it
	******************************************/
	function Story(){
		global $xdb, $news_table;
		$my_stories = SqlGetAll("aid, title, time, hometext", $news_table, "where catid =".MakeItemString(0)." order by sid desc");
		foreach($my_stories as $stories){
			$output.= NewsTitle($stories['title'],$stories['time'],$stories['aid']);
			$output.= $stories['hometext'];
		}
		
		echo $output;
	}
	
	/*****************************************
	Function:NewsTitle
	Needs:String $name, String $date, String $author
	Returns:String $outpur
	What does it do:Takes the title as retrieved from the database and displays it.
	******************************************/
	function NewsTitle($name, $date, $author){
		return $output ="<h3><!--News Title-->
			<strong>
				$name
			</strong>
			| <cite>$date</cite> | <cite>Posted by $author</cite>
		</h3><!--News Title-->";
	}
	

	
	/*****************************************
	Function:Footer
	Needs:N/A
	Returns:N/A
	What does it do:displays the foot of the page.
	******************************************/	
	function Footer(){
		echo "<div id=\"footer\">
			<p><a href=\"http://www.clantemplates.com/\">
            <img src=\"./images/copyright.jpg\" alt=\"Copyright ClanTemplates.com | Designed by Asherz | Coded by Ross\" width=\"393\" height=\"25\" /></a></p>
		</div>";
	}
	
	/*****************************************
	Function:
	Needs:
	Returns:
	What does it do:
	******************************************/	
	function Roster(){
                global $roster_table;
		$cod = '33957';
		$cod4= '33956';
		//if(!RosterCache()){
		$my_rosters = SqlGetAll("bbcode_uid, post_text", $roster_table, "where post_id =$cod or post_id=$cod4", "order by post_id desc");		

		//}
		for($x=0;$x<=1;$x++){
			$roster=$my_rosters[$x];					
			$roster['post_text']= txt2html($roster['post_text']);
			if($x){
				list($roster['post_text'],$council,$xfire[$x])= RosterFormat($roster['post_text'], $roster['bbcode_uid'],$x);
			}
			else{
				list($roster['post_text'],$xfire[$x])= RosterFormat($roster['post_text'], $roster['bbcode_uid'],$x);
			}
			$my_rosters[$x]=$roster;
		}
		
		$cod_roster = $my_rosters[0];
		$cod4_roster = $my_rosters[1];
		$item=array_merge((array)$council,(array)$cod_roster['post_text'],(array)$cod4_roster['post_text']);
		$item = join("<br /> <br />", $item);
		$xfire=array_merge($xfire[0],$xfire[1],array("stimepy","yankeeberetz"));
		$txt=SetXfires($item,$xfire);
		echo $txt;
		
	}
	
	function RosterCache(){
		//
	}
	
	function RosterFormat($txt, $bbcode_uid,$get_council){
		$txt=StriSeleteLastLines("...",$txt);
		$txt=str_replace(":".$bbcode_uid, "",$txt);
		if($get_council){
			list($txt, $council, $xfire)= StriSplit(";;", $txt, $get_council);
		}
		else{
			list($txt, $xfire)= StriSplit(";;", $txt, $get_council, $get_council);
		}
		$txt=str_replace("[align=center]", "<div style=\"text-align:center\">",$txt);
		$txt=str_replace("[/align]", "</div>",$txt);
		$txt=str_replace("[size=24]", "<span style=\"font-size: 24;\">",$txt);
 		$txt=str_replace("[size=15]", "<span style=\"font-size: 15;\">",$txt);
		$txt=str_replace("[/size]", "</span>",$txt);
		$txt=str_replace("[color=", "<span style=\"color: ",$txt);
		$txt=str_replace("[/color]", "</span>",$txt);
		$txt=str_replace("[img]", "<img src=\"",$txt);
		$txt=str_replace("[/img] ","\" />",$txt);
		$txt=str_replace("]", "\">",$txt);
		if($get_council){
			$council=str_ireplace("[size=24]", "<span style=\"font-size: 24;\">",$council);
 			$council=str_ireplace("[size=15]", "<span style=\"font-size: 15;\">",$council);
			$council=str_ireplace("[/size]", "</span>",$council);
			$council=str_ireplace("[color=", "<span style=\"color: ",$council);
			$council=str_ireplace("[/color]", "</span>",$council);
			$council=str_ireplace("[img]", "<img src=\"",$council);
			$council=str_ireplace("[/img] ","\" />",$council);
			$council=str_ireplace("]", "\">",$council);		
		}
		
		if($get_council){
			return array($txt, $council, $xfire);
		}
		else{
			return array($txt, $xfire);	
		}
	}
	
	function SetXfires($txt,$xfire){
	 for($x=0;$x<=sizeof($xfire);$x++){
	  	$txt=str_replace("xxx ".$xfire[$x], "<br /> <br />  <a href=\"http://profile.xfire.com/".$xfire[$x]."\"><img src=\"http://miniprofile.xfire.com/bg/co/type/2/".$xfire[$x].".png\" width=\"450\" height=\"34\" /></a> <br />", $txt);	
		}
		return $txt;
	}
?>