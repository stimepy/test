<?php
$title = "Pride Gaming - Bringing Pride Back to Gaming";
	$banner=mt_rand(1,5);
	$style = "'stylesheet'";
	$csstype = "'text/css'";
	$javatype = "'text/javascript'";
	$robot = "'ALL'";
	$keyw = "'Gaming, community, call of duty, Modern warfare'";
	$desc = "'A Call of Duty 4: Modern Warfare Gaming community'";
	$output =" <html>
			<link rel=$style type=$csstype href=\"./lib/web.css\" />
			<!--[if IE 6]>
			<link rel=$style type=$csstype href=\"./lib/ie6.css\" />
			<![endif]-->
			<script type=$javatype src=\"./lib/roster.js\"></script>
			<body>
			<table width=100%>
			<tr>
				<td colspan=\"2\">
					<center>
					<div id=\"head-container\">
						<img src=\"./images/Coming-4-u_1.jpg\" alt=\"pride-gaming\" width=\"24%\" height=\"175\" />
						<img src=\"./images/banner/banner".$banner.".JPG\" alt=\"pride-gaming\" width=\"50%\" height=\"175\" />
						<img src=\"./images/snip_doom.jpg\" alt=\"pride-gaming\" width=\"24%\" height=\"175\" />
						<br />						
					</div>
				</td>
			</tr>
			<tr>
				<td colspan=\"2\">
					<div id=\"navigation\">
							
						</div>
				</td>
			</tr>
			<tr>
				<td width=20%>
					<table class=menu width=100%>
					<tr>
						<td>
							test
						</td>
					</tr>
					</table>
				</td>
				<td width=80%>
				Main area 
				";
		$output .="</td>
			</tr>
			<tr>
				<td>
					footer
				</td>
			</tr>
			</table>";
			
		
		$output .="</body>
		</html>";
		
		echo $output;

	/*		<table width=\"100%\">
			<img src=\"./images/nav/nav_normal.jpg\" alt=\"pride-gaming\" width=\"100%\" height=\"50\" />
				<tr>
				<!--<td class=left-column><img src=\"./images/snip.jpg\" alt=\"pride-gaming\" width=\"100%\" height=\"200\" /></td>
				<td><img src=\"./images/banner.jpg\" alt=\"pride-gaming\" width=\"100%\" height=\"200\" /></td>
				<td class=right-column>
				<img src=\"./images/snip.jpg\" alt=\"pride-gaming\" width=\"100%\" height=\"200\" />
				</td>
				-->
				</tr>
				
			</table>*/
			
			?>