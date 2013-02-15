<?php if (!defined('IN_PHPBB')) exit; ?><span class="corners-top"><span></span></span>
	<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_BLOCK_START'])) ? $this->_tpldata['DEFINE']['.']['CA_BLOCK_START'] : ''; echo (isset($this->_tpldata['DEFINE']['.']['CA_CAP2_START'])) ? $this->_tpldata['DEFINE']['.']['CA_CAP2_START'] : ''; echo (isset($this->_tpldata['DEFINE']['.']['CA_CAP2_END'])) ? $this->_tpldata['DEFINE']['.']['CA_CAP2_END'] : ''; ?>
		<table class="tablebg" cellspacing="<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_SPACING'])) ? $this->_tpldata['DEFINE']['.']['CA_SPACING'] : ''; ?>" id="top_five" style="width:100%">
		<thead>
		<tr>
			<th style="width: 5%;"> </th>
			<th style="width: 15%;"><?php echo ((isset($this->_rootref['L_FORMNAME'])) ? $this->_rootref['L_FORMNAME'] : ((isset($user->lang['FORMNAME'])) ? $user->lang['FORMNAME'] : '{ FORMNAME }')); ?></th>
			<th style="width: 60%;"><?php echo ((isset($this->_rootref['L_NEWEST_TOPICS'])) ? $this->_rootref['L_NEWEST_TOPICS'] : ((isset($user->lang['NEWEST_TOPICS'])) ? $user->lang['NEWEST_TOPICS'] : '{ NEWEST_TOPICS }')); ?></th>
			<th style="width: 5%;"><?php echo ((isset($this->_rootref['L_REP'])) ? $this->_rootref['L_REP'] : ((isset($user->lang['REP'])) ? $user->lang['REP'] : '{ REP }')); ?></th>
			<th style="width: 5%;"><?php echo ((isset($this->_rootref['L_VIEWS'])) ? $this->_rootref['L_VIEWS'] : ((isset($user->lang['VIEWS'])) ? $user->lang['VIEWS'] : '{ VIEWS }')); ?></th>
			<th style="width: 30%;"><?php echo ((isset($this->_rootref['L_LTPT'])) ? $this->_rootref['L_LTPT'] : ((isset($user->lang['LTPT'])) ? $user->lang['LTPT'] : '{ LTPT }')); ?></th>
		</tr>
		</thead>
		<tbody>
			<?php $_top_five_topic_count = (isset($this->_tpldata['top_five_topic'])) ? sizeof($this->_tpldata['top_five_topic']) : 0;if ($_top_five_topic_count) {for ($_top_five_topic_i = 0; $_top_five_topic_i < $_top_five_topic_count; ++$_top_five_topic_i){$_top_five_topic_val = &$this->_tpldata['top_five_topic'][$_top_five_topic_i]; ?><tr class="bg2">
				<td class="row1">
					<?php echo $_top_five_topic_val['TOP_ICON']; ?>
				</td>
				<td class="row1">
					<a href=<?php echo $_top_five_topic_val['FORUM_URL']; ?>><b><?php echo $_top_five_topic_val['F_NAME']; ?></b></a>
				</td>
				<td class="row1">
				<?php if ($_top_five_topic_val['NO_TOPIC_TITLE']) {  echo $_top_five_topic_val['NO_TOPIC_TITLE']; } else { ?>
				<a href="<?php echo $_top_five_topic_val['U_TOPIC']; ?>"><?php echo $_top_five_topic_val['TOPIC_TITLE']; ?></a>
				<?php } ?>
				</td>
				<td class="row1" text-align="center">
					<?php echo $_top_five_topic_val['TOPIC_REP']; ?>
				</td>
				<td class="row1" text-align="center">
					<?php echo $_top_five_topic_val['TOPIC_VIEW']; ?>
				</td>
				<td class="row1" colspan="2">
					<?php echo $_top_five_topic_val['USERNAME_FULL']; ?><br /><?php echo $_top_five_topic_val['LAST_TOPIC_TIME']; ?>
				</td>
			</tr><?php }} ?>
		</tbody>
		</table>
<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_BLOCK_END'])) ? $this->_tpldata['DEFINE']['.']['CA_BLOCK_END'] : ''; ?>