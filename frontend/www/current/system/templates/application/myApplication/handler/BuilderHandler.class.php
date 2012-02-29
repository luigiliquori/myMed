<?php
require_once 'system/templates/handler/IRequestHandler.php';

/**
 *
 * Request Handler for the tabBarMenu
 * @author lvanni
 *
 */
class BuilderHandler implements IRequestHandler {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public /*void*/ function copyDirectory( $source, $destination ) {
		if ( is_dir( $source ) ) {
			@mkdir( $destination );
			$directory = dir( $source );
			while ( FALSE !== ( $readdirectory = $directory->read() ) ) {
				if ( $readdirectory == '.' || $readdirectory == '..' ) {
					continue;
				}
				$PathDir = $source . '/' . $readdirectory;
				if ( is_dir( $PathDir ) ) {
					$this->copyDirectory( $PathDir, $destination . '/' . $readdirectory );
					continue;
				}
				copy( $PathDir, $destination . '/' . $readdirectory );
			}

			$directory->close();
		} else {
			copy( $source, $destination );
		}
	}

	public /*void*/ function handleRequest() {
		if(isset($_GET['myAppName']) && $_GET['myAppName'] != "") {
			
			// COPY THE TEMPLATE
			$this->copyDirectory('system/templates/application/myTemplate', 'system/templates/application/' . $_GET['myAppName']);
			
			// CONFIGURE THE NEW APPLICATION
			$fp = fopen('system/templates/application/' . $_GET['myAppName'] . '/config.tmp' , 'w');
			fwrite($fp, '<?php ');
			foreach($_GET AS $key=>$value) {
				fwrite($fp, 'define("' . $key . '", ' . $value . '); ');
			}
			fwrite($fp, '?>');
			fclose($fp);
			rename('system/templates/application/' . $_GET['myAppName'] . '/config.tmp', 'system/templates/application/' . $_GET['myAppName'] . '/myConfig.php');
			
		}
	}

}
?>