<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
{
	exit();
}

function _xfac_dashboardOptions_renderTagForumMapping($tags, $forums, $i, $tagForumMapping)
{
	// generate fake forum in case we lost connection
	if (empty($forums) AND !empty($tagForumMapping['forum_id']))
	{
		$forums = array(array(
			'forum_id' => $tagForumMapping['forum_id'],
			'forum_title' => '#' . $tagForumMapping['forum_id'],
		));
	}
?>

<div class="<?php echo($tagForumMapping ? 'TagForumMapping_Record' : 'TagForumMapping_Template'); ?>" data-i="<?php echo $i; ?>">
	<select name="xfac_tag_forum_mappings[<?php echo $i; ?>][term_id]">
		<option value="0">&nbsp;</option>
		<?php foreach ($tags as $tag): ?>
			<option value="<?php echo esc_attr($tag->term_id); ?>"
				<?php
				if (!empty($tagForumMapping['term_id']) AND $tagForumMapping['term_id'] == $tag->term_id)
					echo ' selected="selected"';
			?>>
				<?php echo esc_html($tag->name); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<select name="xfac_tag_forum_mappings[<?php echo $i; ?>][forum_id]">
		<option value="0">&nbsp;</option>
		<?php foreach ($forums as $forum): ?>
			<option value="<?php echo esc_attr($forum['forum_id']); ?>"
				<?php
				if (!empty($tagForumMapping['term_id']) AND $tagForumMapping['forum_id'] == $forum['forum_id'])
					echo ' selected="selected"';
			?>>
				<?php echo esc_html($forum['forum_title']); ?>
			</option>
		<?php endforeach; ?>
	</select>
</div>
<?php
}
?>

<style>
	#xfacDashboardOptions fieldset label { margin-top: 1em !important; margin-bottom: 0 !important; }
</style>
<div class="wrap">
	<div id="icon-options-general" class="icon32">
		<br />
	</div><h2><?php _e('Lightpull', 'xenforo-api-consumer'); ?></h2>

	<form method="post" action="options.php" id="xfacDashboardOptions">
		<?php settings_fields('xfac'); ?>
		
		<table class="form-table">
			<?php if (empty($currentUserAccessToken)): ?>
			<tr valign="top">
				<td colspan="2">
					<?php _e('You have configured Lightpull but you haven\'t connect your WordPress account yet. Do it now to start synchronizing posts and comments!', 'xenforo-api-consumer'); ?><br />
					<?php _e('Other editors also need to connect their own account for their posts to show up in Lightpull.', 'xenforo-api-consumer'); ?>
					
					<?php $connectUrl = site_url('wp-login.php?xfac=authorize&redirect_to=' . rawurlencode(admin_url('profile.php')), 'login_post'); ?>
					<a href="<?php echo $connectUrl; ?>"><?php _e('Connect to an account', 'xenforo-api-consumer'); ?></a>
				</td>
			</tr>
			<?php endif; ?>
			
			<?php if (xfac_option_getWorkingMode() === 'network'): ?>
			<?php else: ?>
			<tr valign="top">
				<th scope="row"><label for="xfac_client_id"><?php _e('API Key', 'xenforo-api-consumer'); ?></label></th>
				<td>
					<input name="xfac_root" type="hidden" id="xfac_root" value="<?php echo esc_attr(XFAC_LIGHTPULL_ROOT); ?>" />
					<input name="xfac_client_id" type="text" id="xfac_client_id" value="<?php echo esc_attr($config['clientId']); ?>" class="regular-text" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="xfac_client_secret"><?php _e('API Secret', 'xenforo-api-consumer'); ?></label></th>
				<td>
					<input name="xfac_client_secret" type="text" id="xfac_client_secret" value="<?php echo esc_attr($config['clientSecret']); ?>" class="regular-text" />
				</td>
			</tr>
			<?php endif; ?>

			<?php if (!empty($meta['linkIndex'])): ?>
			<tr valign="top">
				<th scope="row">
					<?php _e('Top Bar', 'xenforo-api-consumer'); ?>
				</th>
				<td>
					<fieldset>
						<label for="xfac_top_bar_forums_0">
							<input name="xfac_top_bar_forums[]" type="checkbox" id="xfac_top_bar_forums_0" value="0" <?php checked(true, in_array(0, $optionTopBarForums)); ?> />
							<?php _e('Forums link', 'xenforo-api-consumer'); ?>
						</label>
						<p class="description"><?php _e('Show Lightpull link in Top Bar. Forums can be chosen to show as submenu items below.', 'xenforo-api-consumer'); ?></p>

						<?php if (!empty($forums)): ?>
							<div style="margin-left: 20px;">
								<?php foreach ($forums as $forum): ?>
									<label for="xfac_top_bar_forums_<?php echo $forum['forum_id']; ?>">
										<input name="xfac_top_bar_forums[]" type="checkbox" id="xfac_top_bar_forums_<?php echo $forum['forum_id']; ?>" value="<?php echo $forum['forum_id']; ?>" <?php checked(true, in_array($forum['forum_id'], $optionTopBarForums)); ?> />
										<?php echo $forum['forum_title']; ?>
									</label>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</fieldset>

					<fieldset>
						<label for="xfac_top_bar_notifications">
							<input name="xfac_top_bar_notifications" type="checkbox" id="xfac_top_bar_notifications" value="1" <?php checked('1', get_option('xfac_top_bar_notifications')); ?> />
							<?php _e('Show Alerts link', 'xenforo-api-consumer'); ?>
						</label>
						<p class="description"><?php _e('Show Alerts link in Top Bar. The number of unread alerts will be updated via AJAX.', 'xenforo-api-consumer'); ?></p>
					</fieldset>
					<fieldset>
						<label for="xfac_top_bar_conversations">
							<input name="xfac_top_bar_conversations" type="checkbox" id="xfac_top_bar_conversations" value="1" <?php checked('1', get_option('xfac_top_bar_conversations')); ?> />
							<?php _e('Show Conversations link', 'xenforo-api-consumer'); ?>
						</label>
						<p class="description"><?php _e('Show Conversations link in Top Bar. The number of new conversations will be updated via AJAX.', 'xenforo-api-consumer'); ?></p>
					</fieldset>

					<fieldset>
						<label for="xfac_top_bar_replace">
							<input name="xfac_top_bar_replace" type="checkbox" id="xfac_top_bar_replace" value="1" <?php checked('1', get_option('xfac_top_bar_replace')); ?> />
							<?php _e('Replace Admin Bar', 'xenforo-api-consumer'); ?>
						</label>
						<p class="description"><?php _e('Replace WordPress Admin Bar with Top Bar completely. All WordPress links will be removed.', 'xenforo-api-consumer'); ?></p>
					</fieldset>
					<fieldset>
						<label for="xfac_top_bar_always">
							<input name="xfac_top_bar_always" type="checkbox" id="xfac_top_bar_always" value="1" <?php checked('1', get_option('xfac_top_bar_always')); ?> />
							<?php _e('Always Show', 'xenforo-api-consumer'); ?>
						</label>
						<p class="description"><?php _e('Let the Top Bar show up everytime. A login form will appear for guests.', 'xenforo-api-consumer'); ?></p>
					</fieldset>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">
					<?php _e('Guest Account', 'xenforo-api-consumer'); ?>
				</th>
				<td>
					<?php if (!empty($xfGuestRecords)): ?>
						<?php foreach($xfGuestRecords as $xfGuestRecord): ?>
							<label for="xfac_xf_guest_account_<?php echo $xfGuestRecord->id; ?>">
								<input name="xfac_xf_guest_account" type="checkbox" id="xfac_xf_guest_account_<?php echo $xfGuestRecord->id; ?>" value="<?php echo $xfGuestRecord->id; ?>" <?php checked($xfGuestRecord->id, get_option('xfac_xf_guest_account')); ?> />
								<?php echo $xfGuestRecord->profile['username']; ?>
								<?php if (!empty($authorizeUrl)): ?>
								(<a href="<?php echo $authorizeUrl; ?>"><?php _e('change', 'xenforo-api-consumer'); ?></a>)
								<?php endif; ?>
							</label>
							<p class="description"><?php _e('The guest account will be used when contents need to be sync\'d to Lightpull but no connected account can be found.', 'xenforo-api-consumer'); ?></p>
						<?php endforeach; ?>
					<?php else: ?>
					<label for="xfac_xf_guest_account">
						<input name="xfac_xf_guest_account" type="hidden" value="0" />
						<input name="xfac_xf_guest_account" type="checkbox" id="xfac_xf_guest_account" value="1" disabled="disabled" />

						<?php if (!empty($authorizeUrl)): ?>
						<a href="<?php echo $authorizeUrl; ?>"><?php _e('Connect a Lightpull account as Guest account', 'xenforo-api-consumer'); ?></a>
						<?php else: ?>
						<?php _e('Configure API Client first', 'xenforo-api-consumer'); ?>
						<?php endif; ?>
					</label>
					<?php endif; ?>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="xfac_tag_forum_mappings"><?php _e('Tag / Forum Mappings', 'xenforo-api-consumer'); ?></label></th>
				<td>
					<?php

					$i = -1;
					foreach (array_values($tagForumMappings) as $i => $tagForumMapping)
					{
						if (empty($tagForumMapping['term_id']) OR empty($tagForumMapping['forum_id']))
						{
							continue;
						}

						_xfac_dashboardOptions_renderTagForumMapping($tags, $forums, $i, $tagForumMapping);
					}

					if (empty($tags))
					{
						_e('No WordPress tags found', 'xenforo-api-consumer');
					}
					elseif (empty($forums))
					{
						_e('No Lightpull forums found', 'xenforo-api-consumer');
					}
					else
					{
						_xfac_dashboardOptions_renderTagForumMapping($tags, $forums, ++$i, null);
					}
					?>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">
					<?php _e('Synchronization', 'xenforo-api-consumer'); ?><br />
				</th>
				<td>
					<?php _e('Next Run', 'xenforo-api-consumer'); ?>:
					<?php echo date_i18n('H:i', $hourlyNext + get_option('gmt_offset') * HOUR_IN_SECONDS); ?>
					(<a href="options-general.php?page=xfac&cron=hourly"><?php _e('Sync Now', 'xenforo-api-consumer'); ?></a>)

					<input name="xfac_sync_post_wp_xf" type="hidden" id="xfac_sync_post_wp_xf" value="1" />
					<input name="xfac_sync_post_wp_xf_excerpt" type="hidden" id="xfac_sync_post_wp_xf_excerpt" value="0" />
					<input name="xfac_sync_post_wp_xf_link" type="hidden" id="xfac_sync_post_wp_xf_link" value="1" />
					<input name="xfac_sync_post_xf_wp" type="hidden" id="xfac_sync_post_xf_wp" value="0" />
					<input name="xfac_sync_post_xf_wp_publish" type="hidden" id="xfac_sync_post_xf_wp_publish" value="0" />
					<input name="xfac_sync_comment_wp_xf" type="hidden" id="xfac_sync_comment_wp_xf" value="1" />
					<input name="xfac_sync_comment_wp_xf_as_guest" type="hidden" id="xfac_sync_comment_wp_xf_as_guest" value="1" />
					<input name="xfac_sync_comment_xf_wp" type="hidden" id="xfac_sync_comment_xf_wp" value="1" />
					<input name="xfac_sync_comment_xf_wp_as_guest" type="hidden" id="xfac_sync_comment_xf_wp_as_guest" value="1" />
					<input name="xfac_sync_avatar_xf_wp" type="hidden" id="xfac_sync_avatar_xf_wp" value="1" />
					<input name="xfac_bypass_users_can_register" type="hidden" id="xfac_bypass_users_can_register" value="1" />
					<input name="xfac_sync_password" type="hidden" id="xfac_sync_password" value="1" />
					<input name="xfac_sync_login" type="hidden" id="xfac_sync_login" value="1" />
					<input name="xfac_sync_user_wp_xf" type="hidden" id="xfac_sync_user_wp_xf" value="1" />
				</td>
			</tr>
			<?php endif; ?>

		</table>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes'); ?>"  />

			<a href="<?php echo admin_url('options-general.php?page=xfac&do=xfac_meta'); ?>"><?php _e('Reload System Info', 'xenforo-api-consumer'); ?></a>
		</p>
	</form>

</div>