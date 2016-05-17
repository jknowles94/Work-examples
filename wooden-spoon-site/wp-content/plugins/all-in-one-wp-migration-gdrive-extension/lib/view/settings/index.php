<?php
/**
 * Copyright (C) 2014 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */
?>

<div class="ai1wm-container">
	<div class="ai1wm-row">
		<div class="ai1wm-left">
			<?php include AI1WM_TEMPLATES_PATH . '/common/maintenance-mode.php'; ?>

			<div class="ai1wm-holder">
				<h1><i class="ai1wm-icon-gear"></i> <?php _e( 'Google Drive Settings', AI1WMGE_PLUGIN_NAME ); ?></h1>
				<br />
				<br />

				<div class="ai1wm-field">
					<?php if ( $token ): ?>
						<p id="ai1wmge-gdrive-details">
							<?php _e( 'Retrieving Google Drive account details..', AI1WMGE_PLUGIN_NAME ); ?>
						</p>

						<div id="ai1wmge-gdrive-progress">
							<div id="ai1wmge-gdrive-progress-bar"></div>
						</div>

						<p id="ai1wmge-gdrive-size"></p>

						<form method="POST" action="">
							<button type="submit" class="ai1wm-button-red" name="ai1wmge-gdrive-logout" id="ai1wmge-gdrive-logout">
								<i class="ai1wm-icon-gdrive"></i>
								<?php _e( 'Sign Out from your Google Drive account', AI1WMGE_PLUGIN_NAME ); ?>
							</button>
						</form>

					<?php else: ?>

						<form method="POST" action="<?php echo AI1WMGE_GDRIVE_URL; ?>">
							<input type="hidden" name="ai1wmge_client" id="ai1wmge_client" value="<?php echo network_admin_url( 'admin.php?page=site-migration-gdrive-settings' ); ?>" />
							<button type="submit" class="ai1wm-button-blue" name="ai1wmge-gdrive-link" id="ai1wmge-gdrive-link">
								<i class="ai1wm-icon-gdrive"></i>
								<?php _e( 'Link your Google Drive account', AI1WMGE_PLUGIN_NAME ); ?>
							</button>
						</form>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( $token ): ?>
				<div class="ai1wm-holder" style="margin-top:22px;">
					<form method="POST" action="">
						<div>
							<h1><i class="ai1wm-icon-gear"></i> <?php _e( 'Google Drive Backups', AI1WMGE_PLUGIN_NAME ); ?></h1>
							<br />
							<br />
							<p><?php _e( 'Configure your backup plan', AI1WMGE_PLUGIN_NAME ); ?></p>
							<ul id="ai1wmge-gdrive-cron">
								<li>
									<input type="checkbox" name="ai1wmge-gdrive-cron[]" id="ai1wmge-gdrive-cron-hourly" value="hourly" <?php echo in_array( 'hourly', $cron ) ? 'checked' : null; ?> />
									<label for="ai1wmge-gdrive-cron-hourly"><?php _e( 'Every hour', AI1WMGE_PLUGIN_NAME ); ?></label>
								</li>
								<li>
									<input type="checkbox" name="ai1wmge-gdrive-cron[]" id="ai1wmge-gdrive-cron-daily" value="daily" <?php echo in_array( 'daily', $cron ) ? 'checked' : null; ?> />
									<label for="ai1wmge-gdrive-cron-daily"><?php _e( 'Every day', AI1WMGE_PLUGIN_NAME ); ?></label>
								</li>
								<li>
									<input type="checkbox" name="ai1wmge-gdrive-cron[]" id="ai1wmge-gdrive-cron-weekly" value="weekly" <?php echo in_array( 'weekly', $cron ) ? 'checked' : null; ?> />
									<label for="ai1wmge-gdrive-cron-weekly"><?php _e( 'Every week', AI1WMGE_PLUGIN_NAME ); ?></label>
								</li>
								<li>
									<input type="checkbox" name="ai1wmge-gdrive-cron[]" id="ai1wmge-gdrive-cron-monthly" value="monthly" <?php echo in_array( 'monthly', $cron ) ? 'checked' : null; ?> />
									<label for="ai1wmge-gdrive-cron-monthly"><?php _e( 'Every month', AI1WMGE_PLUGIN_NAME ); ?></label>
								</li>
							</ul>
						</div>

						<p>
							<?php _e( 'Last backup date:', AI1WMGE_PLUGIN_NAME ); ?>
							<?php if ( $timestamp ): ?>
								<strong>
									<?php echo date_i18n( sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) ), $timestamp ); ?>
								</strong>
							<?php else: ?>
								<strong><?php _e( 'None', AI1WMGE_PLUGIN_NAME ); ?></strong>
							<?php endif; ?>
						</p>

						<p>
							<input type="checkbox" name="ai1wmge-gdrive-ssl" id="ai1wmge-gdrive-ssl" value="1" <?php echo empty( $ssl ) ? 'checked' : null; ?> />
							<label for="ai1wmge-gdrive-ssl"><?php _e( 'Disable connecting to Google Drive via SSL (only if export is failing)', AI1WMGE_PLUGIN_NAME ); ?></label>
						</p>

						<article class="ai1wmge-article">
							<h3><?php _e( 'Notification settings', AI1WMGE_PLUGIN_NAME ); ?></h3>
							<p>
								<label for="ai1wmge-notification-toggle">
									<input type="checkbox" id="ai1wmge-notification-toggle" name="ai1wmge-notification-toggle" <?php echo $notify ? 'checked' : ''; ?> />
									<?php _e( 'Send an email when a backup is complete.', AI1WMGE_PLUGIN_NAME ); ?>
								</label>
							</p>

							<p>
								<label><?php _e( 'Email address', AI1WMGE_PLUGIN_NAME ); ?></label>
								<br />
								<input class="ai1wmge-email" style="width: 15rem;" type="email" id="ai1wmge-notification-email" name="ai1wmge-notification-email" value="<?php echo $email; ?>" />
							</p>
						</article>

						<article class="ai1wmge-article">
							<h3><?php _e( 'Retention settings', AI1WMGE_PLUGIN_NAME ); ?></h3>
							<p>
								<div class="ai1wm-field">
									<label for="ai1wmge-gdrive-backups"><?php _e( 'Keep the most recent', AI1WMGE_PLUGIN_NAME ); ?></label>
									<input style="width: 3em" type="number" name="ai1wmge-gdrive-backups" id="ai1wmge-gdrive-backups" value="<?php echo intval( $backups ); ?>" />
									<?php _e( 'backups. <small>Default: <strong>0</strong> unlimited.</small>', AI1WMGE_PLUGIN_NAME ); ?>
								</div>

								<div class="ai1wm-field">
									<label for="ai1wmge-gdrive-total"><?php _e( 'Limit the total size of backups to', AI1WMGE_PLUGIN_NAME ); ?></label>
									<input style="width: 4em" type="number" name="ai1wmge-gdrive-total" id="ai1wmge-gdrive-total" value="<?php echo intval( $total ); ?>" />
									<select style="margin-top: -2px" name="ai1wmge-gdrive-total-unit" id="ai1wmge-gdrive-total-unit">
										<option value="MB" <?php echo strpos( $total, 'MB' ) !== false ? 'selected="selected"' : null; ?>><?php _e( 'MB', AI1WMGE_PLUGIN_NAME ); ?></option>
										<option value="GB" <?php echo strpos( $total, 'GB' ) !== false ? 'selected="selected"' : null; ?>><?php _e( 'GB', AI1WMGE_PLUGIN_NAME ); ?></option>
									</select>
									<?php _e( '<small>Default: <strong>0</strong> unlimited.</small>', AI1WMGE_PLUGIN_NAME ); ?>
								</div>
							</p>
						</article>

						<p>
							<button type="submit" class="ai1wm-button-blue" name="ai1wmge-gdrive-update" id="ai1wmge-gdrive-update">
								<i class="ai1wm-icon-gdrive"></i>
								<?php _e( 'Update', AI1WMGE_PLUGIN_NAME ); ?>
							</button>
						</p>
					</form>
				</div>
			<?php endif; ?>
		</div>
		<div class="ai1wm-right">
			<div class="ai1wm-sidebar">
				<div class="ai1wm-segment">
					<?php if ( ! AI1WM_DEBUG ) : ?>
						<?php include AI1WM_TEMPLATES_PATH . '/common/share-buttons.php'; ?>
					<?php endif; ?>

					<h2><?php _e( 'Leave Feedback', AI1WMGE_PLUGIN_NAME ); ?></h2>

					<?php include AI1WM_TEMPLATES_PATH . '/common/leave-feedback.php'; ?>
				</div>
			</div>
		</div>
	</div>
</div>
