<?php
/**
 * Copyright (C) 2014-2016 ServMask Inc.
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

class Ai1wmge_Export_Clean {

	public static function execute( $params ) {
		// Set Gdrive client
		$gdrive = new ServMaskGdriveClient(
			get_option( 'ai1wmge_gdrive_token' ),
			get_option( 'ai1wmge_gdrive_ssl', true )
		);

		$backup_folder = $gdrive->listFolder( ai1wm_archive_folder() );

		if ( isset( $backup_folder['items'] ) && ( $item = array_shift( $backup_folder['items'] ) ) ) {
			$backup_folder = $item;
		} else {
			return $params;
		}

		$files = $gdrive->listFolder( null, $backup_folder['id'], array(
			'orderBy' => 'createdDate',
		) );

		$backups = array();
		foreach ( $files['items'] as $backup ) {
			if ( $backup['kind'] === 'drive#file' ) {
				$backups[] = $backup;
			}
		}

		// Skip calculations if there are no backups to delete
		if ( count( $backups ) === 0 ) {
			return $params;
		}

		// Number of backups
		if ( ( $backups_limit = get_option( 'ai1wmge_gdrive_backups', 0 ) ) ) {
			if ( ( $backups_to_remove = count( $backups ) - $backups_limit ) > 0 ) {
				for ( $index = 0; $index < $backups_to_remove; $index++ ) {
					$gdrive->delete( $backups[$index]['id'] );
				}
			}
		}

		// Sort backups by date desc
		$backups = array_reverse( $backups );

		// Get the size of the latest backup before we remove it
		$size_of_backups = $backups[0]['fileSize'];

		// Remove the latest backup, the user should have at least one backup
		array_shift( $backups );

		if ( ( $retention_size = ai1wm_parse_size( get_option( 'ai1wmge_gdrive_total', 0 ) ) ) ) {
			foreach ( $backups as $backup ) {
				$size_of_backups += $backup['fileSize'];
				if ( $size_of_backups > $retention_size ) {
					$gdrive->delete( $backup['id'] );
				}
			}
		}

		return $params;
	}
}
