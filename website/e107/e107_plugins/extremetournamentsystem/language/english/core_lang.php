<?php
/*#############################################################
##Nuke Ladder - XTS
##Homepage::http://www.aodhome.com
##Copyright:: Shane Andrusiak 2000-2006   Kris Sherrerd 2008 (2.6.0)
##Version 2.6.4
###############################################################
if (!defined('X1plugin_include'))exit();
#############################################################*/

/*All reserved words are stored here, the first half is system wide and user friendly, the second have is admin use, but not security critical*/
//All can be Syswide but mainly seen on: 
define('XL_add', 'Add');
define('XL_save', 'Save');
define('XL_delete', 'Delete');
define('XL_edit','Edit');
define('XL_view','View');
define('XL_yes','Yes');
define('XL_no','No');
define('XL_ok','Ok');
define('XL_na','n/a');
define('XL_missingfile','Error:Missing File or undefined configuration variable.');
define('XL_notlogggedin','Error:Please login to use this feature.');
define('XL_adminonly','<center>Sorry, Administrators Only.</center>');
define('XL_teamadmin_activating', 'Please wait while we activate your team');
define('XL_moderatoronly','<center>Sorry, Moderators Only.</center>');
// Select Boxes
define('XL_select_event','Select Event');
define('XL_select_team','Select Team');
define('XL_select_map', 'Select Map');
define('XL_select_game','Select Game');
define('XL_select_user','Select User');
// Emails
define('X1_emailsubject','Attention user!');
// Core Index Page
define('XL_index_title','Welcome to our competition site.');
define('XL_index_none','No events have been created yet, please check back later.');
define('XL_index_mod','Competition Type :: ');
define('XL_index_teams','Curent Teams :: ');
define('XL_index_matches','Played Games :: ');
define('XL_index_challenges','Confirmed Challenges :: ');
define('XL_index_image','Image');
define('XL_index_events','Events');
// Team Profile Page
define('XL_teamprofile_noteam','The team you requested could not be found. It may have been removed or there could be errors contacting the database.');
define('XL_teamprofile_noprofile','This Team has not entered a profile yet.');
define('XL_teamprofile_title','Team Profile: ');
define('XL_teamprofile_tprofile','Profile');
define('XL_teamprofile_troster','Roster');
define('XL_teamprofile_thistory','History');
define('XL_teamprofile_logo','Team Logo');
define('XL_teamprofile_name','Team Name');
define('XL_teamprofile_homepage','Homepage');
define('XL_teamprofile_location','Location');
define('XL_teamprofile_mail','Mail');
define('XL_teamprofile_captain','Captain');
define('XL_teamprofile_contact','Captain Contact');
define('XL_teamprofile_moto','Team Moto/Profile');
define('XL_teamprofile_report','Report this profile');
define('XL_teamprofile_husername','Username');
define('XL_teamprofile_hcontact','Contact');
define('XL_teamprofile_hjoindate','Joindate');
define('XL_teamprofile_hextras','Extras');
define('XL_teamprofile_nomembers','This team has no members.');
define('XL_teamprofile_hid','Id');
define('XL_teamprofile_hevent','Event');
define('XL_teamprofile_tgp','Total Games Played'); 
define('XL_teamprofile_tw','Total Wins');
define('XL_teamprofile_tl','Total Losses');
define('XL_teamprofile_td','Total Draws');
define('XL_teamprofile_tp','Total Points');
define('XL_teamprofile_gp','Games<br />Played');
define('XL_teamprofile_w','Wins');
define('XL_teamprofile_l','Losses');
define('XL_teamprofile_d','Draws');
define('XL_teamprofile_p','Points');
define('XL_teamprofile_noevents','This team has not joined any events.');
define('XL_teamprofile_hwinner','Winner');
define('XL_teamprofile_hloser','Loser');
define('XL_teamprofile_hdate','Date');
define('XL_teamprofile_hdetails','Details');
define('XL_teamprofile_nomatches','This team has not played in any matches.');
define('XL_teamprofile_recruiting','Recruiting:');
define('XL_teamadmin_eventchl','CHLGD');
define('XL_teamprofile_totaldetails', '<b>Totals for all events participated in:</b>');
// Team List Page
define('XL_teamlist_title','Teamlist');
define('XL_teamlist_hcountry','Country');
define('XL_teamlist_hname','Name');
define('XL_teamlist_hmembers','Members');
define('XL_teamlist_recruiting', 'Recruiting');
define('XL_teamlist_prev','Prev');
define('XL_teamlist_next','Next');
// Team Create Page
define('XL_teamcreate_logintocreate','Please login to create a team');
define('XL_teamcreate_title','Create a new team');
define('XL_teamcreate_tags','Team Tags');
define('XL_teamcreate_email','Team Email');
define('XL_teamcreate_homepage','Team Homepage');
define('XL_teamcreate_jpass1','Team Join Password');
define('XL_teamcreate_jpass2','Team Join Password Confirm');
define('XL_teamcreate_location','Team Main Location');
define('XL_teamcreate_newteam','Create New Team');
define('XL_teamcreate_blankname','Team name cannot be blank.');
define('XL_teamcreate_invalidfeed','Invalid characters in the name.');
define('XL_teamcreate_blankpass','Password cannot be blank.');
define('XL_teamcreate_blankjpass','Join password cannot be blank.');
define('XL_teamcreate_blankemail','Please enter a email address.');
define('XL_teamcreate_blanktags','Please enter clantags or team initials.');
define('XL_teamcreate_passnomatch','Admin passwords do not match, please confirm again.');
define('XL_teamcreate_jpassnomatch','Join passwords do not match, please confirm again.');
define('XL_teamcreate_blankcountry','Country is not set.');
define('XL_teamcreate_toomanyteams','You have created too many teams.');
define('XL_teamcreate_dupeteam','This already Team Exsists!');
define('XL_teamcreate_created','Team Created, you can now login.');
define('XL_teamcreate_requestpass','Request password');
define('XL_teamcreate_sendrequest','Send Request');
define('XL_teamcreate_emailoff','Server emails are disabled , please contact and admin to have your password reset.');
define('XL_teamcreate_reset','Password Reset');
define('XL_teamcreate_emptyuser','Please Login');
define('XL_teamcreate_enteremail', 'Please enter the email address for your teams(s)');
define('XL_teamcreate_noteam','Cant find that email');
// Team Report Page
define('XL_teamreport_title','Report a match');
define('XL_teamreport_previous','View Previous Matches');
define('XL_teamreport_event','Event Name');
define('XL_teamreport_opponent','Opponent Name');
define('XL_teamreport_you','Your Team');
define('XL_teamreport_mapsandscores','Maps and Scores');
define('XL_teamreport_comments','Match Comments');
define('XL_teamreport_textarea','Please keep it clean.');
define('XL_teamreport_textarea2','255 characters max.');
define('XL_teamreport_demolink','Demo or Video Link');
define('XL_teamreport_screenlink','Screenshot Link');
define('XL_teamreport_report','Report');
define('XL_teamreport_rules','Rules ::');
define('XL_teamreport_loss',' Loss');
define('XL_teamreport_draw','Draw');
define('XL_teamreport_win','Win');
define('XL_teamreport_blankname','Unknown Team');
define('XL_teamreport_notactive','This event is disabled');
define('XL_teamreport_disabled','Challenges have been disabled');
define('XL_teamreport_playwithself','Stop Playing with yourself!');
define('XL_teamreport_gamesmaxday','You have played too many games on this event today.');
define('XL_teamreport_emailloss','Loss Recorded');
define('XL_teamreport_emailwin','Win Recorded');
define('XL_teamreport_emaildraw','Draw Recorded');
define('XL_teamreport_blankscores', 'One or more of the scores entered was empty, Please fill in the score.');
define('XL_teamreport_extras', 'Extra Information');
// Team Quit Page
define('XL_teamquit_login','You are either the Captain of a team and cannot quit or you need to login before you can quit a team.');
define('XL_teamquit_title','Quit a team.');
define('XL_teamquit_header','Select a team to remove yourself from.');
define('XL_teamquit_button','Quit Team');
define('XL_teamquit_none','You were not found on this team. You may have been removed already.');
define('XL_teamquit_removed','You have been removed from team :');
// Player Profile Page
define('XL_playerprofile_title','Player Profile');
define('XL_playerprofile_location','Location:');
define('XL_playerprofile_contact','Contact Information:');
define('XL_playerprofile_prof','Users Main Profile:');
define('XL_playerprofile_missing','Player has been removed or you entered thier name wrong.');
define('XL_playerprofile_team','Team');
define('XL_playerprofile_tags','Tags');
define('XL_playerprofile_none','There are no members on this roster');
define('XL_playerprofile_joinedteams','My Teams');
// Match History Page
define('XL_matchhistory_title','Match History');
define('XL_matchhistory_none','There are no previous matches');
// Match History Page
define('XL_matchpreview_challenger','Challenger');
define('XL_matchpreview_challenged','Challenged');
define('XL_matchpreview_matchdate','MatchDate');
define('XL_matchpreview_none','There are no pending matches');
// Match Information Page
define('XL_matchinfo_title','Match Information');
define('XL_matchinfo_nodemo','No Demo Avaliable');
define('XL_matchinfo_demo','Download Demo');
define('XL_matchinfo_comments','Comments');
define('XL_matchinfo_mapimage','Map Image');
define('XL_matchinfo_mapname','Map Name');
define('XL_matchinfo_notfound','<center>This match id no longer exsists</center>');
define('XL_matchinfo_screen','ScreenShot');
define('XL_matchinfo_noscreen','No ScreenShot Posted');
define('XL_matchinfo_gamewasdraw', 'This match was a determined to be a draw');
// Maps Listing Page
define('XL_maplist_title','Maps list for: ');
define('XL_maplist_download','Download');
define('XL_maplist_nodownload','No Download Avaliable');
define('XL_maplist_none','No maps have been added to this event.');
// Event Home Page
define('XL_eventhome_viewtitle','Viewing Options');
define('XL_eventhome_mapsbutton','View Maps');
define('XL_eventhome_standingsbutton','View Standings');
define('XL_eventhome_viewhistory','View History');
define('XL_eventhome_newmatches','Pending Matches');
define('XL_eventhome_settings','Event Settings');
define('XL_eventhome_viewrules', 'View Rules');
// Event Rules Page
define('XL_eventrules_title','View Rules for the event.');
define('XL_eventrules_none','No rules have been posted, check back later');
// Event Settings
define('XL_eventhome_active','Active');
define('XL_eventhome_enabled','Enabled');
define('XL_eventhome_timezone','Timezone');
define('XL_eventhome_numdates','Number of date selections');
define('XL_eventhome_dupedates','Duplicate dates?');
define('XL_eventhome_maps1','Challenger Maps');
define('XL_eventhome_maps2','Challenged maps');
define('XL_eventhome_dupemaps','Duplicate maps?');
define('XL_eventhome_pointswin','Points for a win');
define('XL_eventhome_pointsloss','Points for a loss');
define('XL_eventhome_pointsdraw','Points for a draw');
define('XL_eventhome_pointsdecline','Points subtracted for declining a challenge');
define('XL_eventhome_gamesday','Games per day limit');
define('XL_eventhome_challlimit','Challenge limit');
define('XL_eventhome_timeout','Challenge Timeout');
define('XL_eventhome_maxteams','Team limit');
define('XL_eventhome_rostermin','Roster minnimum');
// Team Join Page
define('XL_teamjoin_title','Join a team.');
define('XL_teamjoin_header','Select a team to join.');
define('XL_teamjoin_password','Teams join password');
define('XL_teamjoin_joinbutton','Join Team');
define('XL_teamjoin_none','Team does not exsist or the password was wrong.');
define('XL_teamjoin_login','Please login to join a team');
define('XL_teamjoin_dupe','You are already a member of this team');
define('XL_teamjoin_limit','You are a member of too many teams.');
define('XL_teamjoin_joined','You have joined the team: ');
// Team Invites Page
define('XL_teaminvites_title','Confirm invitation');
define('XL_teaminvites_limit','This user is a member of too many teams already.');
define('XL_teaminvites_sent','Invite Sent');
define('XL_teaminvites_accept','Accept Invite');
define('XL_teaminvites_decline','Decline Invite');
define('XL_teaminvites_none','Invite does not exsist.');
define('XL_teaminvites_youlimit','You are already a member of too many teams.');
define('XL_teaminvites_accepted','Invite accepted');
define('XL_teaminvites_declined','Invite declined');
define('XL_teaminvites_removed','Invite removed');
define('XL_teaminvites_enterid', 'Please enter the id sent in the confirmation email');
define('XL_teaminvites_allreadyonroster','This user is already on your roster, please choose another user.');
define('XL_teaminvites_allreadyinvited','This user is already on your invite list, please choose another user.');
define('XL_teaminvites_incorrect','The confirmation code entered is incorrect, please provide the correct confirmation code.');
// Team Disputes
define('XL_teamdisputes_filedispute','File Dispute');
define('XL_teamdisputes_button','Send');
define('XL_teamdisputes_error','Error');
define('XL_teamdisputes_submitted','Dispute Submitted');
// Team Admin Actions
define('XL_teamadmina_teamupdated','Team Updated');
define('XL_teamadmina_passupdated','<br />Password Changed');
define('XL_teamadmina_noeventsel','No Event Selected');
define('XL_teamadmina_noevent','Event doesnt exsist');
define('XL_teamadmina_joinevent','Join event :');
define('XL_teamadmina_joinmaxplayers','Your team has too many members on its roster for this event.');
define('XL_teamadmina_joinminplayers','Your team does not have enough members on its roster for this event.');
define('XL_teamadmina_captainonly','You must be a captain to remove a team.');
define('XL_teamadmina_teamremoved','Team removed.');
define('XL_teamadmina_memberremoved','Member Removed');
define('XL_teamadmina_memberupdated','Member Updated');
define('XL_teamadmin_msgsent','Notifications have been sent to :');
// Team Admin Page
define('XL_teamadmin_title','Team Administration: ');
define('XL_teamadmin_invites','Invites');
define('XL_teamadmin_matches','Matches');
define('XL_teamadmin_quit','Quit');
define('XL_teamadmin_joinpass','Join Password');
define('XL_teamadmin_homepage','Team Webpage');
define('XL_teamadmin_logo','Clan Logo');
define('XL_teamadmin_captaininfo','Captain Information');
define('XL_teamadmin_mail','Email');
define('XL_teamadmin_xfire', 'Xfire');
define('XL_teamadmin_update','Update Information');
define('XL_teamadmin_rostermodify','Modify');
define('XL_teamadmin_rosterupdate','Update');
define('XL_teamadmin_resterremove','Remove');
define('XL_teamadmin_invname','Invite Name');
define('XL_teamadmin_invcontact','Contact Info');
define('XL_teamadmin_invcancel','Cancel Invite');
define('XL_teamadmin_invcancelbut','Cancel');
define('XL_teamadmin_invnone','No Pending Invitations');
define('XL_teamadmin_invuser','Invite User');
define('XL_teamadmin_challnew','Start New Challenge');
define('XL_teamadmin_challconfirm','Confirm');
define('XL_teamadmin_challstatus','Status');
define('XL_teamadmin_challwidthdraw','Withdraw');
define('XL_teamadmin_challnone','No challenges');
define('XL_teamadmin_challmaps','Map Picks');
define('XL_teamadmin_challcomments','Challenge Comments');
define('XL_teamadmin_challreportwin','Report Win');
define('XL_teamadmin_challreportloss','Report Loss');
define('XL_teamadmin_challreportdraw','Report Draw');
define('XL_teamadmin_challnotify','Notify Roster');
define('XL_teamadmin_challdispute','Dispute Match');
define('XL_teamadmin_eventsnone','You have not joined any events.');
define('XL_teamadmin_eventsjoin','Join Event.');
define('XL_teamadmin_nosetmatches', 'No Matches have been confirmed');
define('XL_teamadmin_matchcontact', 'Contact Information');
define('XL_teamadmin_matchcomments', 'Match Communications:');
define('XL_teamadmin_matchesnone','You have not played any matches.');
define('XL_teamadmin_removeteam','Team Removal');
define('XL_teamadmin_removeteamwarming','You cannot get your team back. Once its gone its gone.');
define('XL_teamadmin_removeteambut','Yes, Remove My Team!');
define('XL_teamadmin_challengetitle','Accept - Decline Challenge Menu');
define('XL_teamadmin_transferteam','Team Captain Tranfer');
define('XL_teamadmin_transferteamwarming','Select a user to transfer the team to.');
define('XL_teamadmin_transferteambut','Yes, Transfer My Team!');
define('XL_teamadmin_eventteamremoved', 'You have removed your team from this event.');
define('XL_teamadmin_joinedevent','You have joined the event, ready your team!');
define('XL_teamadmin_nochallenges','Please finish all challenges before quitting, or report loss to end the challenge.');
// Challenges
define('XL_challenges_selectevent','Select Event to challenge on.');
define('XL_challenges_continue','Continue');
define('XL_challenges_notenabled','Event not enabled');
define('XL_challenges_challengeteam','Challenge a team.');
define('XL_challenges_otherteam','Other Team');
define('XL_challenges_selectdates','Select Dates');
define('XL_challenges_selectmaps','Select Maps');
define('XL_challenges_addedinfo','Added Information');
define('XL_challenges_declinechall','Decline Challenge');
define('XL_challenges_warning',' point(s) are subtracted for decling a challenge');
define('XL_challenges_vs','Vs');
define('XL_challenges_selectdate','Select Date.');
define('XL_challenges_challengermaps','Challengers Maps');
define('XL_challenges_yourmaps','Your Maps');
define('XL_challenges_acceptchalenge','Accept Challenege');
define('XL_challenges_notfound','Challenge Not Found');
define('XL_challenges_allreadychallenged', 'This team has already been challenged.');
define('XL_challenges_gamesmaxday', 'Past the games limit for today.');
define('XL_challenges_notactive', 'This event is not active');
define('XL_challenges_disabled', 'Challenged are not enabled');
define('XL_teamadmin_ChallengeTitle', 'Confirm a challenge');
define('XL_challenges_datesrestricted', 'You cannot select a date more than once, please try again.');
define('XL_mapsrestricted', "You cannot select a map more than once, please try again.");
//Expired
define('XL_challenges_expired','Challenge Expired');
define('XL_challenges_challengesuccess', 'Challenge Success');
define('XL_challenges_challaccepted','Challenge Accepted');
define('XL_challenges_challengedeclined', 'Challenge Declined');
define('XL_challenges_challengewithdrawn', 'Challenge Withdrawn');
define('XL_challenges_send', 'Send Challenge');
//My Teams
define('XL_myteams_title','Select a team to activate.');
define('XL_myteams_loc','Loc');
define('XL_myteams_notloggedin','You must be logged in to activate teams.');
define('XL_myteams_noteams','You dont have any teams, click here to create one.');
//Cookie Challenge
define('XL_challenges_nocommonevents',"We could not locate any common events");
define('XL_challenges_commonevents','You can only challenge teams that belong to the same event you do. 
			Please select an event to challenge on from the list below<br/>
			If you do not see an event list, this means you and the team you are 
			attempting to challenge do not share common events.');
//moderator terms
define('XL_mod_loginpage', 'Staff Login');
define('XL_mod_password', 'Password');
define('XL_mod_loginbutton','Login');
define('XL_mod_trylogin', 'I\'m sorry, you don\'t have permission to be here.');
define('XL_why_login','Either you don\'t have permission to be here or you need to Login');
define('XL_mod_team', 'Select Moderator');
define('XL_moderator_activating', 'Please wait while we activate your account');
define('XL_Mod_nopriv','You do not have moderator privileges.');
define('XL_mod_failedlog','I\'m sorry, you must have entered in the wrong information, please try again');
//message terms:
define('XL_teamadmin_message', 'New Messages');
define('XL_teamadmin_messnone', XL_teamadmin_challnone.' or you there are no messages to view');
define('XL_teamadmin_reply', 'Reply');
define('XL_teamadmin_nomessages', 'There are no messages');
define('XL_teamadmin_invalidteammes', 'I\'m sorry but the message you wish to see is not accessable by your team');
define('XL_adminmess_modteam', 'Team Mod');
define('XL_adminmess_user', 'User');
define('XL_adminmess_sent', 'Sent');
define('XL_adminmess_message', 'Message');
define('XL_adminmess_team', 'Message Id');
define('XL_teammess_title', 'Challenge Messages');
define('X1_teamuser_removself', 'You can\'t remove yourself from your own team.  Please Transfer your team or Quit the team!');
define('X1_myteam_baduser', 'Error:No user info found');
define('X1_cocap_nono', 'As a cocaptain you may not remove other captains!');
define('X1_cookie_config', "Configure cookie mode!!");
define('XL_playerprofile_fmail', 'False Email:');
define('XL_playerprofile_usefmail', 'Use a False Email?');
define('XL_playerprofile_edit', 'Edit Profile');
define('XL_playerprofile_name','Ingame name:');
define('XL_log_nowrite','Log not writable!  Please chmod the file to 755.');
define('XL_failed_login','I\'m sorry, no corresponding team was found.  Please try again.');
define('XL_failed_value','Values passed in were not set!');
define('X1_event_allreadyjoined','Your team has already joined this event.');
define('X1_laddermod_eventfull','Event is full.  No more teams are being accepted at this time.');
define('XL_leadership_transfered', 'Leadership of the Team has been transfered successfully!');
define('XL_leadership_notonroster', 'Leadership of the team has not been transfered.  Please recheck the information and try again.');
//Adminstration reserved words begin here


//Main Administration Panel
define('XL_admin_title', "Plugin Administration");
define('XL_tab_help', ' Help');
define('XL_tab_games', ' Games');
define('XL_tab_events', ' Events');
define('XL_tab_maps', ' Maps');
define('XL_tab_mapgroups', ' Map Groups');
define('XL_tab_teams', ' Teams');
define('XL_tab_challenges', ' Challenges');
define('XL_tab_matches', ' Match History');
define('XL_tab_disputes', ' Disputes');
define('XL_tab_config', ' Config');
//added for 2.6.0
define('XL_tab_moderator', ' Moderators');
//Games Administration Panel
define('XL_agames_add', 'Add Games');
define('XL_agames_name', 'Name');
define('XL_agames_pic', 'Picture');
define('XL_agames_desc', 'Description');
define('XL_agames_none', 'No games have been created in the database.');
define('XL_agames_selectimage', 'Select Image');
define('XL_agames_preview', 'Image Preview');
define('XL_agames_updated', 'Updated Games.');
define('XL_agames_added', ' blank game entries added.');
//Ladder Administration Panel
define('XL_aevents_add', 'Add New Events');
define('XL_aevents_fixrungs', 'Fix Rungs');
define('XL_aevents_hname', 'Ladder Name');
define('XL_aevents_hgame', 'Game');
define('XL_aevents_hmod', 'Mod Type');
define('XL_aevents_none', 'No Competitions have been created');
define('XL_aevents_general', 'General Options');
define('XL_aevents_mod', 'Competition Type.');
define('XL_aevents_options', 'Ladder Options and Settings');
define('XL_aevents_active', 'Competition Active?');
define('XL_aevents_enabled', 'Challenges Enabled?');
define('XL_aevents_simchall', 'Simultaneous Challenges Allowed?');
define('XL_aevents_maxgames', 'Maximum Matches per Day');
define('XL_aevents_maxteams', 'Maximum Teams');
define('XL_aevents_minplayers', 'Minimum Players');
define('XL_aevents_maxplayers', 'Maximum Players');
define('XL_aevents_challdate', 'Challenge Date Options');
define('XL_aevents_resdates', 'Restrict Simultaneous Date Selections.');
define('XL_aevents_dropdates', 'Number of days shown in date dropdown.');
define('XL_aevents_numdates', 'Number of possible match dates.');
define('XL_aevents_mapoptions', 'Challenge Map Options');
define('XL_aevents_resmaps', 'Restrict Simultaneous map selections.');
define('XL_aevents_nummaps1', 'Number of map selections for Challenger.  1-10');
define('XL_aevents_nummaps2', 'Number of map selections for Challenged.  1-10');
define('XL_aevents_pointoptions', 'Point Options');
define('XL_aevents_win', 'Points Awarded For A Win');
define('XL_aevents_loss', 'Points Awarded For A Loss');
define('XL_aevents_draw', 'Points Awarded For A Draw');
define('XL_aevents_declinedchall', 'Points Removed For A Declined Challenge');
define('XL_aevents_description', 'Please enter a description for your ladder.');
define('XL_aevents_rules', 'Enter Your rules.');
define('XL_aevents_notes', 'Enter any notes.');

define('XL_aevents_post', 'Create Ladder');
define('XL_aevents_added', 'Competition added.');
define('XL_aevents_editing', 'Editing Ladder :: ');
define('XL_aevents_removed', 'Removal Complete.');
define('XL_aevents_removewarning', 'Warning, this will remove the event and extra items you selected to remove, are you sure?');
define('XL_aevents_updated', 'Competition Updated');
define('XL_aevents_fixed', 'Rungs Fixed for ladder_id:');
define('XL_aevents_expireoptions', 'Challenge Expiration');
define('XL_aevents_enableexpires', 'Allow challenges to expire?');
define('XL_aevents_expirehours', 'Hours till a challange expires?');
define('XL_aevents_expirepenalty', 'Challenge Expiration Penalty:(in points)');
define('XL_aevents_expirebonus', 'Bonus for the challenger upon experation:(in points)');
define('XL_aevents_reportoptions', 'Reporting Options');
define('XL_aevents_whoreports', 'Who report the Matcb details.');
define('XL_aevents_mapgroups', 'Select the Map groups that belong to this event.');
define('XL_aevents_fixtherungs', 'Rung Reset');
//Matches Administration Panel
define('XL_amatches_createtitle', 'Create a played match');
define('XL_amatches_selwinner', 'Select Winner');
define('XL_amatches_selloser', 'Select Loser');
define('XL_amatches_seldate', 'Select Date Played');
define('XL_amatches_winnermaps', 'Select Winners Maps (First Set)');
define('XL_amatches_winnerscore', 'Winner Score');
define('XL_amatches_loserscore', 'Loser Score');
define('XL_amatches_losermaps', 'Select Losers Maps (Second Set)');
define('XL_amatches_extras', 'Extras and Comments');
define('XL_amatches_screenshot', 'Screenshot Link(blank if none)');
define('XL_amatches_demo', 'Demo Link(blank if none)');
define('XL_amatches_comments', 'Comments - 255 Char Max');
define('XL_amatches_addmatch', 'Add Match');
define('XL_amatches_errnowinner', 'Sorry winner cannot be blank');
define('XL_amatches_errnoloser', 'Sorry loser team cannot be blank');
define('XL_amatches_errsameteams', 'Sorry you cant create a past match with two identical teams');
define('XL_amatches_added', 'New Match Added to Database');
define('XL_amatches_addrecord', 'Add New Match Record');
define('XL_amatches_hevent', 'Ladder');
define('XL_amatches_none', 'No Matches Have Been Played Yet');
define('XL_amatches_matchadmin', 'Match Administration');
define('XL_amatches_monifymatch', 'Modify Match:: ');
define('XL_amatches_gameid', 'Game ID');
define('XL_amatches_maparray1', 'First Maps');
define('XL_amatches_maparray2', 'Second Maps');
define('XL_amatches_selmaparray', 'Map Select Array');
define('XL_amatches_winnerscorearray1', 'Map1 Score Winner Array');
define('XL_amatches_loserscorearray1', 'Map1 Score Loser Array');
define('XL_amatches_winnerscorearray2', 'Map2 Score Winner Array');
define('XL_amatches_loserscorearray2', 'Map2 Score Loser Array');
define('XL_amatches_screenshot1', 'Screenshot 1');
define('XL_amatches_screenshot2', 'Screenshot 2');
define('XL_amatches_eventid', 'Ladder Id');
define('XL_amatches_demolink', 'Demo Link');
define('XL_amatches_nomatch', 'Match was not found, or the event has been removed.');
define('XL_amatches_updated', 'Match updated');
define('XL_amatches_draw', 'Match was a draw');
define('XL_amatches_modifymatch', "Modify this match");
define('XL_amatches_runplugincode', "Run the plugin file when creating this match, this will alter the standings as if the game had happened.");
//Teams ADministration Panel
define('XL_ateams_editglobal', 'Edit team profiles or global records');//
define('XL_ateams_editevent', 'Edit team event records');
define('XL_ateams_teamadmin', 'Event Team Administration');//
define('XL_ateams_editteam', 'Modify Team: ');
define('XL_ateams_id', 'Team ID');//
define('XL_ateams_rung', 'Rung');//
define('XL_ateams_games', 'Matches Played');//
define('XL_ateams_tgames', 'Total Matches Played');//
define('XL_ateams_penalties', 'Penalties');//
define('XL_ateams_swins', 'Streak Wins');//
define('XL_ateams_slosses', 'Streak Losses');//
define('XL_ateams_rest', 'Rest');//
define('XL_ateams_challyesno', 'ChallYesNo');//
define('XL_ateams_clantags', 'Clan tags');//
define('XL_ateams_logo', 'Logo');//
define('XL_ateams_none', 'Team was not found.');//
define('XL_ateams_teamupdated', 'Team updated');//
define('XL_ateams_ircserver', 'IRC Server');
define('XL_ateams_ircchannel', 'IRC Channel');
define('XL_ateams_updatemain', 'Update Main Team Table for Totals');
define('XL_ateams_icq', 'ICQ');
define('XL_ateams_msn','Widows Live');
define('XL_ateams_aim', 'Aol Instant Messanger');
define('XL_ateams_yim', 'Yahoo Instant Messanger');
define('XL_ateams_removed', 'Team Removed');
define('XL_ateams_captain', 'Team Captain');
//Maps ADministration Panel
define('XL_amaps_add', 'Add New Maps');
define('XL_amaps_picture', 'Map Picture');
define('XL_amaps_event', 'Competition');
define('XL_amaps_none', 'You have not added any maps');
define('XL_amaps_updated', 'Updated maps');
define('XL_amaps_noupdate', 'Maps must be removed from map groups before being deleted.');
define('XL_amaps_added', ' map spots added');
// Mapgroups ADministration Panel
define('XL_amapgroups_add', 'Add New Mapgroups');
define('XL_amapgroups_name', 'Group Name');
define('XL_amapgroups_none', 'You have not added any map groups');
define('XL_amapgroups_updated', 'Updated map groups');
define('XL_amapgroups_added', 'Map groups added');
define('XL_amapgroups_contents', ' Map Preview');
define('XL_amapgroups_select', 'Selected');
define('XL_amapgroups_addmapstogroup', 'Add Maps to Mapgroup');
define('XL_amapgroups_addmapstogroup_info', 'Select the maps you want to be part of this mapgroup and save.');
define('XL_amapgroups_mapname', 'Map name');
define('XL_amapgroups_notfound', "Mapgroup Not Found");
//Config Administration Panel
define('XL_aconfig_left', 'left');
define('XL_aconfig_right', 'right');
define('XL_aconfig_center', 'center');
define('XL_javacook', 'Javascript makes Cookie');
define('XL_phpcook', 'PHP makes Cookie');
define('XL_aconfig_forhead', 'Forumheader');
define('XL_aconfig_tbord', 'Tborder');
define('XL_aconfig_title', 'System Configuration');
define('XL_aconfig_welcome','Welcome to the Nukeladder - Extreme Tournament System configuration page.  On this page you will be able to mess with all the general settings within this system.  Please take note if you should put in a value that keeps the system from working there is a default backup in: yoursystemplugins-modules/ExtremeTournamentSystem/Templates/config.php.backup');
define('XL_aconfig_sitnam','Site Name');
define('XL_aconfig_returl','Return URL');
define('XL_aconfig_langadmin','Language Admin');
define('XL_aconfig_langcore', 'Language Core');
define('XL_aconfig_datefor', 'Normal Date');
define('XL_aconfig_dateforext', 'Extended Date');
define('XL_aconfig_showlb', 'Show a Link back to '.X1_lblink);
define('XL_aconfig_ver', 'Show XTS version number');
define('XL_aconfig_align', 'Alignment of Linkback');
define('XL_aconfig_logoc', 'Logo color of Linkback');
define('XL_aconfig_lburl', 'Linkback URL');
define('XL_aconfig_cookmod', 'Cookie Mode');
define('XL_aconfig_tcook', 'Team Cookie Name');
define('XL_aconfig_mcook', 'Moderator Cookie Name');
define('XL_aconfig_tcooktime', 'Team Cookie timeout(min)');
define('XL_aconfig_mcooktime', 'Moderator Cookie Timeout(min)');
define('XL_aconfig_cookref', 'Cookie Refresh(sec)');
define('XL_aconfig_logout', 'Logout Page');
define('XL_aconfig_emailon', 'Email on?');
define('XL_aconfig_replyadd', 'Reply address');
define('XL_aconfig_emstamp', 'Email Timestamp');
define('XL_aconfig_showem', 'Show email text on send(not recommened)');
define('XL_aconfig_showteams', 'Show # of Teams');
define('XL_aconfig_maxteam', 'Max Teams Created');
define('XL_aconfig_maxjoin', 'Max Teams Joined');
define('XL_aconfig_timagesz', 'Team Image Size');
define('XL_aconfig_high', 'Height');
define('XL_aconfig_wide', 'Width');
define('XL_aconfig_xtrafields', '# of Extra Fields');
define('XL_aconfig_field1', 'Field 1');
define('XL_aconfig_field2', 'Field 2');
define('XL_aconfig_field3', 'Field 3');
define('XL_aconfig_rossort', 'Roster Sort');
define('XL_aconfig_standtmz', '# of Teams shown on Standings');
define('XL_aconfig_newmac', '# of new matches shown');
define('XL_aconfig_macshw', '# of match matches shown');
define('XL_aconfig_tzone', 'Competition Time Zone');
define('XL_aconfig_evenset', 'Show Event Settings when Challenging');
define('XL_aconfig_shwrule', 'Show Event Rules when Challenging');
define('XL_aconfig_addbut', 'Add ?? Icon/Button');
define('XL_aconfig_delbut', 'Delete ?? Icon');
define('XL_aconfig_savbut', 'Save ?? Icon/button');
define('XL_aconfig_editbut', 'Edit ?? Icon/Button');
define('XL_aconfig_tab', 'Tab Icon');
define('XL_aconfig_gampre', 'Default Game Preview');
define('XL_aconfig_tabsz', 'Tab size');
define('XL_aconfig_tabbord', 'Tab Border');
define('XL_aconfig_styleuse', 'Use style sheets(see below)');
define('XL_aconfig_styshe', 'Style Sheet');
define('XL_aconfig_tbor', 'Use forumheader or tborder');
define('XL_aconfig_cusmen', 'Custom Menu');
define('XL_aconfig_cusfile', 'Custom Menu File');
define('XL_aconfig_reset', 'Reset Config File!');
define('XL_aconfig_nowrtpt1','I\'m sorry please chmod ');
define('XL_aconfig_nowrtpt2',' to be writeable, perferably 666!');
define('XL_aconfig_plzwt', 'Please Wait while we refresh your Configuration.');
define('XL_aconfig_ingamename','Allow Editable In Game Name?');
define('XL_aconfig_iupload','Allow Image Upload');
define('XL_aconfig_dupload','Allow Demo Upload');
define('XL_aconfig_filetype','Allowable File Types');
define('XL_aconfig_either','Allow Either');
define('XL_aconfig_log','Record error to file(if no displays them on screen)');

//Challenges Administration Panel
define('XL_achallenges_title','Challenge Administration');
define('XL_achallenges_create','Create a challenge on ladder:');
define('XL_achallenges_selchallenger','Select Challenger');
define('XL_achallenges_selchallenged','Select Challenged');
define('XL_achallenges_add','Add Challenge');
define('XL_achallenges_errblankteam1','Sorry challenger cannot be blank');
define('XL_achallenges_errblankteam2','Sorry challenged team cannot be blank');
define('XL_achallenges_errsameteams','Sorry you cant create a challenge with two identical teams');
define('XL_achallenges_added','Challenge Inserted into Database');
define('XL_achallenges_editchallenge','Edit a challenge on ladder');
define('XL_achallenges_maps1','Select Challengers Maps (First Set)');
define('XL_achallenges_maps2','Select Challenged Maps (Second Set)');
define('XL_achallenges_misc','Extra Challenge Options');
define('XL_achallenges_matchdate','Match Date');
define('XL_achallenges_randid','Random Id');
define('XL_achallenges_setdate','Set Date');
define('XL_achallenges_update','Update Challenge');
define('XL_achallenges_editunconfirmed','Edit a  unconfirmed challenge on ladder:');
define('XL_achallenges_dt1','Date /Time 1');
define('XL_achallenges_updated','Challenge Updated');
define('XL_achallenges_deleted','Challenge Deleted');
define('XL_achallenges_confirmed', 'Un-confirmed challenges on ladder:');
define('XL_achallenges_unconfirmed', 'Confirmed challenges on ladder:');
define('XL_achallenges_none', 'There are no challenges on this event!');
define('XL_achallenges_selectevent','Select an Event');
define('XL_achallenges_databaseopps', 'The Database did not complete the request! ');
//Disputes Administration Panel
define('XL_adisputes_sender','Sender');
define('XL_adisputes_offender','Offender');
define('XL_adisputes_event','Laddername');
define('XL_adisputes_comments','Comments:');
define('XL_adisputes_none','No Disputes Have Been Filed');
define('XL_adisputes_delted','Dispute has been removed from the database.');
//Moderator Panel
define('XL_modadmin_createmod','Add Moderator');
define('XL_modadmin_title','Moderator Menu');
define('XL_modadmin_addmoder','Add A Moderator');
define('XL_modadmin_editmod','Modify a Moderator');
define('XL_modadmin_success', 'Moderator has sucessfully been added/updated');
define('XL_modadmin_start', 'Current Moderators');
define('XL_mod_modname', 'Modifying user:');
define('XL_modadmin_update', 'Update Moderator');
define('XL_modadmin_alreadymod', 'This person already has Moderator access, maybe you meant to edit or delete their access?');
define('XL_modadmin_moddeleted', ' has been removed from the moderator group.'); 
define('XL_asearch_player','Seach Player Name:');
define('XL_asearch_infoplayer','Use * as a wildcard for partial matches.');
define('XL_ateams_editplayer','Moderate Player'); 
define('XL_asearch_search','Search');
define('XL_playersearch_noname','No players located by the name:');
define('XL_asearch_plselect','Select A Player');
define('XL_aplayer_joined','Member in the Teams:');
define('XL_aplayer_remove', 'Remove from Team');
define('XL_aplayer_removeall', 'Remove from ALL teams');
define('XL_aplayer_removallnote','Will remove player from all teams if NOT captain of that team.');
define('XL_aplayer_namblank','Players must have a name');
define('XL_modadmin_passshort','Please give a password atleast 4 characters long.');
define('XL_modadmin_nopass','Password was not specified.');
define('XL_aevents_change', 'ChangeLadder');



/**************************DO NOT EDIT BELOW THIS LINE LOG AND SYSTEM USE USE THIS FOR INFORMATION***************************/
//Errors as written to the error log or with error log
define('XL_error_sys','An error has occurred in the system please contact the Web Administrator');
define('XL_dispute_error','Error! You tried to delete a dispute and there were NO entries in the database!');
define('XL_failed_retr','Failed Database information retrieval');
define('XL_failed_updat','Failed database update');
define('XL_failed_invite_ackn','No accepting or declining value acknowledged.');
define('XL_failed_invite_rm','Invite was unable to be removed. Invite Code:');
define('XL_failed_team','No team Found.');
define("ERROR_DISP", 1);
define("ERROR_DIE", 2);
define("ERROR_RET", 3);
//system information


?>
