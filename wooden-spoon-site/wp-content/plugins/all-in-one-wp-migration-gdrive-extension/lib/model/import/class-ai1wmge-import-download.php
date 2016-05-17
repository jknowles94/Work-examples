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

class Ai1wmge_Import_Download {

	public static function execute( $params ) {

		$completed = false;

		// File ID
		if ( ! isset( $params['fileId'] ) ) {
			throw new Ai1wm_Import_Exception(
				__( 'Google Drive File ID is not specified. ', AI1WMGE_PLUGIN_NAME )
			);
		}

		// Set startBytes
		if ( ! isset( $params['startBytes'] ) ) {
			$params['startBytes'] = 0;
		}

		// Set endBytes
		if ( ! isset( $params['endBytes'] ) ) {
			$params['endBytes'] = ServMaskGdriveClient::CHUNK_SIZE;
		}

		// Set retry
		if ( ! isset( $params['retry'] ) ) {
			$params['retry'] = 0;
		}

		// Set Google Drive client
		$gdrive = new ServMaskGdriveClient(
			get_option( 'ai1wmge_gdrive_token' ),
			get_option( 'ai1wmge_gdrive_ssl', true )
		);

		// Get archive file
		$archive = fopen( ai1wm_archive_path( $params ), 'ab' );

		try {
			// Increase number of retries
			$params['retry'] += 1;

			// Download file chunk
			$gdrive->getFile( $params['fileId'], $archive, $params );
		} catch ( Exception $e ) {
			// Retry 3 times
			if ( $params['retry'] <= 3 ) {
				return $params;
			}

			throw $e;
		}

		// Reset retry counter
		$params['retry'] = 0;

		// Close the archive file
		fclose( $archive );

		// Calculate percent
		$percent = (int) ( ( $params['startBytes'] / $params['totalBytes'] ) * 100 );

		// Set progress
		Ai1wm_Status::progress( $percent );

		// Next file chunk
		if ( empty( $params['totalBytes'] ) ) {
			throw new Ai1wm_Import_Exception( 'Unable to import the archive! Please check file size parameter. ');
		} else if ( $params['totalBytes'] == $params['startBytes'] ) {
			$completed = true;
		}

		// Set completed flag
		$params['completed'] = $completed;

		return $params;
	}
}
