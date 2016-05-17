<?php
/**
 * Copyright (C) 2014-2015 ServMask Inc.
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

class Ai1wmge_Settings_Controller {

	public static function index() {

		$model = new Ai1wmge_Settings;

		// Google Drive update
		if ( isset( $_POST['ai1wmge-gdrive-update'] ) ) {

			// Cron update
			if ( ! empty( $_POST['ai1wmge-gdrive-cron'] ) ) {
				$model->cron( (array) $_POST['ai1wmge-gdrive-cron'] );
			} else {
				$model->cron( array() );
			}

			// Set SSL mode
			if ( ! empty( $_POST['ai1wmge-gdrive-ssl'] ) ) {
				$model->ssl( 0 );
			} else {
				$model->ssl( 1 );
			}

			// Set number of backups
			if ( ! empty( $_POST['ai1wmge-gdrive-backups'] ) ) {
				$model->backups( (int) $_POST['ai1wmge-gdrive-backups'] );
			} else {
				$model->backups( 0 );
			}

			// Set size of backups
			if ( ! empty( $_POST['ai1wmge-gdrive-total'] ) && ! empty( $_POST['ai1wmge-gdrive-total-unit'] ) ) {
				$model->total( (int) $_POST['ai1wmge-gdrive-total'] . trim( $_POST['ai1wmge-gdrive-total-unit'] ) );
			} else {
				$model->total( 0 );
			}

			$model->email( $_POST['ai1wmge-notification-email'] );

			$model->toggle( isset( $_POST['ai1wmge-notification-toggle'] ) );
		}

		// Google Drive logout
		if ( isset( $_POST['ai1wmge-gdrive-logout'] ) ) {
			$model->revoke();
		}

		Ai1wm_Template::render(
			'settings/index',
			array(
				'backups'   => get_option( 'ai1wmge_gdrive_backups', false ),
				'cron'      => get_option( 'ai1wmge_gdrive_cron', array() ),
				'email'     => get_option( 'ai1wmge_gdrive_notify_email', get_option( 'admin_email', '' ) ),
				'notify'    => get_option( 'ai1wmge_gdrive_notify_toggle', false ),
				'ssl'       => get_option( 'ai1wmge_gdrive_ssl', true ),
				'timestamp' => get_option( 'ai1wmge_gdrive_timestamp', false ),
				'token'     => get_option( 'ai1wmge_gdrive_token', false ),
				'total'     => get_option( 'ai1wmge_gdrive_total', false ),
			),
			AI1WMGE_TEMPLATES_PATH
		);
	}

	public static function account() {
		$model = new Ai1wmge_Settings;
		if ( ( $account = $model->account() ) ) {
			echo json_encode( $account );
		}
		exit;
	}
}
