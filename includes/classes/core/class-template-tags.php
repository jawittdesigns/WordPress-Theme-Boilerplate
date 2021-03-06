<?php
/**
 * Load Template Tag Functions.
 *
 * @package    Theme_Package
 * @subpackage Theme_Package/Includes/Classes
 * @author     Theme_Author <Theme_Author_Email>
 * @copyright  Copyright (c) 2018, Theme_Author
 * @license    GNU General Public License v2 or later
 * @version    1.0.0
 */

namespace Theme_Package\Includes\Classes;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( __NAMESPACE__ . '\\Template_Tags' ) ) {

	/**
	 * Load Template Tag Functions.
	 *
	 * @author Jason Witt
	 * @since  0.0.1
	 */
	class Template_Tags {

		/**
		 * Directory.
		 *
		 * @author Jason Witt
		 * @since  0.0.1
		 *
		 * @var string dir The directory that contains the template tag functions.
		 */
		protected $dir;

		/**
		 * Initialize the class
		 *
		 * @author Jason Witt
		 * @since  0.0.1
		 *
		 * @param string $dir The directory that contains the template tag functions.
		 *
		 * @return void
		 */
		public function __construct( $dir ) {
			// Set the directory path.
			$this->dir = $dir;

			// Include the files.
			$this->include_the_files();
		}

		/**
		 * Initiate.
		 *
		 * @author Jason Witt
		 * @since  0.0.1
		 *
		 * @return void
		 */
		public function include_the_files() {
			// Get the files to include.
			$files = $this->scan_directory();

			// Loop through the $files array.
			foreach ( $files as $file ) {

				// Verify that the file exists.
				if ( file_exists( $file ) ) {

					// Include the file.
					include $file;
				}
			}
		}

		/**
		 * Scan the Directory.
		 *
		 * @author Jason Witt
		 * @since  0.0.1
		 *
		 * @return array
		 */
		public function scan_directory() {
			// Declare the results array to return.
			$results = array();

			// Recursively scan the dirs for files.
			$files = new \RecursiveDirectoryIterator( $this->dir );

			// Loop through the files.
			foreach ( new \RecursiveIteratorIterator( $files ) as $file ) {
				$filename = $file->getFilename();
				$filepath = $file->getPathname();

				// Exclude dot files.
				if ( '.' === substr( $filename, 0, 1 ) ) {
					continue;
				}

				// Get the path to the file.
				$file = $filepath;

				// Get the file header info.
				$header = get_file_data( $file, array( 'Load' => 'Load' ) );

				// Get the file extension.
				$extension = substr( $file, strrpos( $file, '.' ) + 1 );

				// If 'Load' is true and the file is a PHP file.
				if ( 'true' === $header['Load'] && 'php' === $extension ) {
					$results[] = $file;
				}
			}

			// Return the results array.
			return $results;
		}
	}
}
