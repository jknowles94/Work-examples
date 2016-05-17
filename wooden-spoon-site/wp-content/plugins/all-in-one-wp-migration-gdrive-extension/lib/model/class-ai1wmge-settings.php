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

class Ai1wmge_Settings {

	public function revoke() {
		// Set Gdrive client
		$gdrive = new ServMaskGdriveClient(
			get_option( 'ai1wmge_gdrive_token' ),
			get_option( 'ai1wmge_gdrive_ssl', true )
		);

		// Revoke token
		$gdrive->revoke();

		// Remove token option
		delete_option( 'ai1wmge_gdrive_token' );
	}

	public function cron( $schedules ) {
		// Reset cron schedules
		Ai1wm_Cron::clear( 'ai1wmge_gdrive_hourly_export' );
		Ai1wm_Cron::clear( 'ai1wmge_gdrive_daily_export' );
		Ai1wm_Cron::clear( 'ai1wmge_gdrive_weekly_export' );
		Ai1wm_Cron::clear( 'ai1wmge_gdrive_monthly_export' );

		// Update cron options
		update_option( 'ai1wmge_gdrive_cron', $schedules );

		// Update cron schedules
		foreach ( $schedules as $recurrence ) {
			Ai1wm_Cron::add( "ai1wmge_gdrive_{$recurrence}_export", $recurrence, array(
				'secret_key' => get_option( AI1WM_SECRET_KEY ),
				'gdrive'     => 1,
			) );
		}
	}

	public function ssl( $mode ) {
		update_option( 'ai1wmge_gdrive_ssl', $mode );
	}

	public function account() {
		// Set Gdrive client
		$gdrive = new ServMaskGdriveClient(
			get_option( 'ai1wmge_gdrive_token' ),
			get_option( 'ai1wmge_gdrive_ssl', true )
		);

		// Get account info
		$account = $gdrive->getAccountInfo();

		// Set account name
		$name = null;
		if ( isset( $account['name'] ) ) {
			$name = $account['name'];
		}

		// Set used quota
		$used = null;
		if ( isset( $account['quotaBytesUsed'] ) ) {
			$used = $account['quotaBytesUsed'];
		}

		// Set total quota
		$total = null;
		if ( isset( $account['quotaBytesTotal'] ) ) {
			$total = $account['quotaBytesTotal'];
		}

		return array(
			'name'     => $name,
			'used'     => size_format( $used ),
			'total'    => size_format( $total ),
			'progress' => ceil( ( $used / $total ) * 100 ),
		);
	}

	public function email( $email ) {
		update_option( 'ai1wmge_gdrive_notify_email', $email );
	}

	public function toggle( $toggle ) {
		update_option( 'ai1wmge_gdrive_notify_toggle', $toggle );
	}

	public function backups( $number ) {
		update_option( 'ai1wmge_gdrive_backups', $number );
	}

	public function total( $size ) {
		update_option( 'ai1wmge_gdrive_total', $size );
	}

}
