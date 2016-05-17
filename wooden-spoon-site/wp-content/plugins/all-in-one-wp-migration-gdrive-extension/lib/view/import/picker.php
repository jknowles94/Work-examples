<div id="ai1wmge-import-modal" class="ai1wmge-modal ai1wm-not-visible">
	<div class="ai1wm-modal-content-middle">
		<span class="ai1wm-loader {{files !== false ? 'ai1wmge-hide' : ''}}"></span>
		<div class="ai1wmge-file-browser {{files !== false ? '' : 'ai1wmge-hide'}}">
			<span class="ai1wmge-path">
				<i class="ai1wm-icon-folder"></i>
				<span id="ai1wmge-download-path">{{path}}</span>
			</span>
			<ul class="ai1wmge-file-list">
				<li v-repeat="files" v-on="click: browse(this)" class="ai1wmge-file-item {{mime | icon}}">
					<span class="ai1wmge-filename" v-text="name"></span>
					<span class="ai1wmge-filesize {{mime === 'application/vnd.google-apps.folder' ? 'ai1wmge-hide' : ''}}">{{size}}</span>
				</li>
			</ul>
			<p class="{{files.length > 0 ? 'ai1wmge-hide' : ''}}">No files or directories</p>
		</div>
	</div>

	<div class="ai1wm-modal-action">
		<p>
			<span class="ai1wmge-contact-gdrive {{files !== false ? 'ai1wmge-hide' : ''}}">
				<?php _e( 'Connecting to Google Drive ...', AI1WMGE_PLUGIN_NAME ); ?>
			</span>
			<br />
			<br />
			<span class="{{selected_file ? '' : 'ai1wmge-hide'}}">
				<span id="ai1wmge-download-file" class="ai1wmge-selected-file ai1wm-icon-file-zip" v-if="selected_file_name" v-animation>{{selected_file_name}}</span>
				<br />
				<br />
			</span>
			<button class="ai1wm-button-red" id="ai1wmge-import-file-cancel" v-on="click: cancel">
				<i class="ai1wm-icon-notification"></i>
				<?php _e( 'Cancel', AI1WMGE_PLUGIN_NAME ); ?>
			</button>
			<button class="ai1wm-button-green" id="ai1wmge-import-file" v-if="selected_file" v-on="click: import">
				<i class="ai1wm-icon-publish"></i>
				<?php _e( 'Import', AI1WMGE_PLUGIN_NAME ); ?>
			</button>
		</p>
	</div>
</div>
