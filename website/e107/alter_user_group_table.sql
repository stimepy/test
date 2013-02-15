ALTER TABLE `e107_phpbb_users`
  ADD `user_name` VARCHAR(100) NOT NULL,
  ADD `user_sess` VARCHAR(100) NOT NULL,
  ADD `user_currentvisit` INT(10) NOT NULL DEFAULT '0',
  ADD `user_lastpost` INT(10) NOT NULL DEFAULT '0',
  ADD `user_chats` INT(10) NOT NULL DEFAULT '0',
  ADD `user_comments` INT(10) NOT NULL DEFAULT '0',
  ADD `user_forums` INT(10) NOT NULL DEFAULT '0',
  ADD `user_ban` TINYINT(3) NOT NULL DEFAULT '0',
  ADD `user_prefs` TEXT NOT NULL,
  ADD `user_viewed` TEXT NOT NULL,
  ADD `user_visits` INT(10) NOT NULL DEFAULT '0',
  ADD `user_admin` TINYINT(3) NOT NULL,
  ADD `user_login` VARCHAR(100) NOT NULL,
  ADD `user_class` TEXT NOT NULL,
  ADD `user_perms` TEXT NOT NULL,
  ADD `user_realm` TEXT NOT NULL,
  ADD `user_xup` VARCHAR(100) NOT NULL;


UPDATE e107_user p, e107_phpbb_users pp
SET pp.user_name = p.user_name,
  pp.user_sess=p.user_sess,
  pp.user_currentvisit=p.user_currentvisit,
  pp.user_lastpost=p.user_lastpost,
  pp.user_chats=p.user_chats,
  pp.user_comments=p.user_comments,
 pp.user_forums=p.user_forums,
  pp.user_ban=p.user_ban,
  pp.user_prefs=p.user_prefs,
  pp.user_viewed=p.user_viewed,
  pp.user_visits=p.user_visits,
  pp.user_admin=p.user_admin,
  pp.user_login=p.user_login,
  pp.user_class=p.user_class,
 pp.user_perms=p.user_perms,
  pp.user_realm=p.user_realm,
  pp.user_xup=p.user_xup
WHERE pp.user_id = p.phpbb_id;

drop table e107_user;



create view e107_user as(
select user_id, username as user_name, username as user_loginname, user_password, user_sess, user_email,
user_sig as user_signature, user_avatar as user_image, user_timezone, user_allow_viewemail as user_hideemail,
user_regdate as user_join, user_lastvisit, user_currentvisit, user_lastpost, user_chats,
user_comments, user_forums, user_ip, user_ban, user_prefs, user_viewed,
user_visits, user_admin, user_login, user_class, user_perms, user_realm, user_passchg as user_pwchange, user_xup from e107_phpbb_users);



