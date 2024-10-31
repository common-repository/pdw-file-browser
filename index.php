<?php
/*
Plugin Name: PDW Media File Browser
Description: A very userfriendly and dynamic file browser for /wp-content/uploads/ folder. Interacts with the 'advanced image button' using the advanced version of TinyMCE (Ultimate TinyMCE plugin).
Version: 1.3
Author: Mattias Fjellvang
Author URI: http://constantsolutions.dk
*/

/*
PDW File Browser v1.3 beta
Date: October 19, 2010
Url: http://www.neele.name

Copyright (c) 2010 Guido Neele

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

The plugin is developed by Mattias Fjellvang
*/


	////////////////////////////////	
	// Settings
	////////////////////////////////
	
		// Start SESSION
			@session_start();
	
		// Get current WP lang
			$curLang = explode('_', get_locale()); // convert 'aa_BB' to 'aa'
			$pdwLang = $curLang[0];
			
			if(!empty($_COOKIE['language'])) {
				$pdwLang = $_COOKIE['language'];
			}
			
			if(!empty($_COOKIE['skin'])) {
				$pdwSkin = '&skin=' . $_COOKIE['skin'];
			}
			
		// Localhost fix
		// Add your localhost IP to the above array if you are experiencing problems on localhost with upload folder path
			$whitelist = array('localhost', '127.0.0.1');

			if(in_array($_SERVER['HTTP_HOST'], $whitelist)){
				$localhost = 1;
			}

	
		// Filebrowser URL
			$filebrowser_URL = plugins_url() . '/pdw-file-browser/pdw_file_browser/index.php?wp-root=' . ABSPATH . '&language=' . $pdwLang . $pdwSkin;
			
		// Upload path - as default /wp-content/uploads/ folder
			$pdwUploadPath = ABSPATH . 'wp-content/uploads/'; // End on slash
			
			if($localhost) {
				$pdwUploadPath = '/' . str_replace(str_replace('/', '\\', $_SERVER['DOCUMENT_ROOT']), '', $pdwUploadPath);
			} else {
				$pdwUploadPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $pdwUploadPath);
			}
			
			
			$_SESSION['PDWUploadPath'] = $pdwUploadPath; // Send data to PDW File manager
			define('PLUGINS_FOLDER_PATH', $_SERVER['DOCUMENT_ROOT'].$pdwUploadPath);
			
		// Check 'uploads' folder dir
			if(!@is_dir(PLUGINS_FOLDER_PATH) && $_GET['page'] == 'pdw-filebrowser-plugin') {
				if (@mkdir(PLUGINS_FOLDER_PATH, 0755)) {
					echo '<script>alert(\'The /uploads/ folder did not exists, however, PDW File Browser have just created the folder for you.\');</script>';
					
					if(!@chmod(PLUGINS_FOLDER_PATH, 0755)) {
						echo '<script>alert(\'Folder permission for /uploads/ folder failed. Please change folder permission for folder /wp-content/uploads/ to 755 manually.\');</script>';
					}
				}
			}
	
		// Menu link name
			$fileArchive_name = 'PDW File Browser';
			
		// PLUGIN PATH
			define( 'PLUGIN_PATH', plugin_dir_path(__FILE__) );
			
			
	////////////////////////////////	
	// PDW File Manager TinyMCE init
	////////////////////////////////
			
	
		// Add advanced configration 'file_browser_callback' to TinyMCE editor
			
			@add_filter('tiny_mce_before_init', 'add_filebrowser_to_advmceconf'); // Add configuration to TinyMCE init
			
			function add_filebrowser_to_advmceconf($a) {
			   $a['file_browser_callback'] = 'filebrowser';
			   return $a;
			}
			
		// Activate PDW file manager integration for TinyMCE
		
			function add_jquery_data() {
				global $parent_file;
				global $filebrowser_URL;

					?>
					<script type="text/javascript">
						function filebrowser(field_name, url, type, win) {
							fileBrowserURL = "<?php echo $filebrowser_URL; ?>&editor=tinymce&filter=" + type;
									
							tinyMCE.activeEditor.windowManager.open({
								title: "PDW File Browser",
								url: fileBrowserURL,
								width: 950,
								height: 650,
								inline: 0,
								maximizable: 1,
								close_previous: 0
							},{
								window : win,
								input : field_name
							});		
						}
					</script>
					<?php

			}

			@add_filter('admin_head', 'add_jquery_data');
			
	////////////////////////////////	
	// FILE BROWSER add 'browse' icon fix
	// The '/css/img/icons.gif' file is missing from
	// the Ultimate TinyMCE plugin, this below fix
	// will add it.
	////////////////////////////////
	
		$tinyMceSource = ABSPATH . 'wp-content/plugins/ultimate-tinymce/main.php';
		
		if(file_exists($tinyMceSource)) {
			$source = ABSPATH . 'wp-content/plugins/ultimate-tinymce/css/img/icons.gif';
			$imgData = file_get_contents(ABSPATH . 'wp-content/plugins/pdw-file-browser/icons.gif');
			
			if(!file_exists($source)) { // Check if i was wrong
				$handle = fopen($source, 'w'); // if not, create the file
				fwrite($handle, $imgData); // write data from '/plugins/pdw-file-browser/icons.gif'
				fclose($handle); // close
			}
		}
	
	////////////////////////////////	
	// FILE BROWSER PAGE
	////////////////////////////////
	
	// Add to menu
		add_action('admin_menu', 'add_pdw_filebrowser_to_submenu');

		function add_pdw_filebrowser_to_submenu() {
			global $fileArchive_name;
			add_submenu_page( 'upload.php', 'PDW Filebrowser Plugin', $fileArchive_name, 'manage_options', 'pdw-filebrowser-plugin', 'pdw_filebrowser_plugin_callback' ); 
		}
		
	// Filebrowser page
		function pdw_filebrowser_plugin_callback() {
			global $filebrowser_URL;
			echo '<iframe src="' . $filebrowser_URL . '" style="width:100%; height: 850px; border:0;"></iframe>';
		}
	
	////////////////////////////////	
	// FILE BROWSER SETTINGS PAGE
	////////////////////////////////
	
	// Save settings
		if(isset($_GET['save'])) {
				add_action('init', 'saveSettingsCookie');

				function saveSettingsCookie() {
						$url = parse_url(get_bloginfo('url'));
						
						setcookie('language', $_POST['language'], strtotime('+30 days'), COOKIEPATH, COOKIE_DOMAIN, false);
						setcookie('skin', $_POST['skin'], strtotime('+30 days'), COOKIEPATH, COOKIE_DOMAIN, false);
				}
		}
	
	// Add to menu
		add_action('admin_menu', 'add_pdw_filebrowser_to_settingmenu');

		function add_pdw_filebrowser_to_settingmenu() {
			global $fileArchive_name;
			add_submenu_page( 'options-general.php', 'PDW Filebrowser Plugin', $fileArchive_name, 'manage_options', 'pdw-filebrowser-plugin-settings', 'pdw_filebrowser_plugin_callback_setting' ); 
		}
		
	// Filebrowser page
		function pdw_filebrowser_plugin_callback_setting() {
		
			if(isset($_GET['save'])) {
				echo '<script>alert(\'Settings saved!\');</script>';
				echo '<meta http-equiv="refresh" content="0;URL=\'?page=pdw-filebrowser-plugin-settings\'">';
			}
		
			require_once(PLUGIN_PATH . 'pdw_file_browser/config.php');
		
			echo '<div class="wrap">';
			echo '<h2>PDW File browser - Settings</h2>';
			echo '<form action="?page=' . $_GET['page'] . '&save" method="POST"><table>';
			echo '<tr><td>Language:</td><td>';
				echo '<select id="settings_language" name="language">';
					   require_once(PLUGIN_PATH . 'pdw_file_browser/lang/languages.php');
					   
					   foreach($languages as $key => $value){
						   printf('<option%s value="%s">%s</option>',($language == $value ? ' selected="selected"' : '') , $value, $key);
					   }
				echo '</select>';
			echo '</td></tr>';
			echo '<tr><td>Skin:</td><td>';
			
			    echo '<select id="settings_skin" name="skin">';
					require_once(PLUGIN_PATH . 'pdw_file_browser/skins/skins.php');

					$skins["Redmond"] = "";
					asort($skins);

					foreach($skins as $key => $value){
					   printf('<option%s value="' . $value . '">' . $key . '</option>', ($_COOKIE['skin'] == $value ? ' selected="selected"' : ''), $value, $key);
					}
                echo '</select>';
				
			echo '</td></tr>';
			echo '<tr><td colspan="2"><input type="submit" value="Save settings"></td></tr>';
			echo '<tr><td colspan="2" style="font-size: 10px; color:gray;">Settings require COOKIE\'s to be enabled</td></tr>';
			echo '</table></form>';
			
			echo '</div>';
		}
		
	////////////////////////////////	
	// STANDALONE SUPPORT
	////////////////////////////////
	
		function pdw_file_browser_single() {
			global $filebrowser_URL;
			
			echo '
				<script type="text/javascript">
				 function openFileBrowser(id){
					  fileBrowserlink = "' . $filebrowser_URL . '&editor=standalone&returnID=" + id;
					  window.open(fileBrowserlink,\'pdwfilebrowser\', \'width=1000,height=650,scrollbars=no,toolbar=no,location=no\');
				 }
				</script>
			';
		}
		
		// Add hook for admin <head></head>
		add_action('admin_head', 'pdw_file_browser_single');
		
	////////////////////////////////	
	// Add function to media URL
	////////////////////////////////
	
		function pdw_to_media_url() {
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
				
				$('#src').attr('title', 'Double-click to open PDW File browser');
				
								$('#src').dblclick(function(){
								openFileBrowser('src');
								})
				});
			</script>
			<?php
		}
		
		// Add hook for admin <head></head>
		add_action('admin_head', 'pdw_to_media_url');
		
	////////////////////////////////	
	// ACF Integration
	// Create new custom field named
	// 'PDW Image select'
	////////////////////////////////
		
		if( function_exists( 'register_field' ) ) {
			register_field('acf_Pdwimage', dirname(__File__) . '/acf_pdw_field.php');
		}

?>