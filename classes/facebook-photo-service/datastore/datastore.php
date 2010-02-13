<?php
/**
 * Provides a simple interface for storing and retrieving data items. Items are
 * stored simply as serialised data in files on the filesystem.
 */
class DataStore {

	public $datadir;
	
	/**
	 * Create a data store.
	 * @param $datadir the directory containing data that is read from and written to.
	 */
	public function __construct($datadir) {

		// Check that we can read/write to the data dir
		if (is_readable($datadir) && is_writable($datadir)) {
			$this->datadir = $datadir;
		} else {
			throw new Exception('Data directory is not readable/writable.');
		}
	}

	/**
	 * Returns whether or not there is a entry under the specified name.
	 * @param $item the name of the entry to check for.
	 * @return boolean
	 */	
	public function item_exists($item) {
		if (file_exists($this->datadir . '/' . $item)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Returns the contents of the specified data item.
	 * @param $item the name of the data item.
	 * @return the contents of the data item, or null if the data item does not exist or could not be read.
	 */
	public function get_item($item) {
		if (file_exists($this->datadir . '/' . $item)) {
			return unserialize(file_get_contents($this->datadir . '/' . $item));
		} else {
			return null;
		}
	}
	
	/**
	 * Puts the specified data into the data under the specified name. If a data item by that name already exists, it is overwritten.
	 * @param $item the name to give the data item.
	 */
	public function put_item($item, $data) {
		$dirname = dirname($this->datadir . '/' . $item);
		
		if (!is_dir($dirname)) {
			if (!mkdir($dirname, 0777, true)) {
				return false;
			}
		}
		
		return file_put_contents($this->datadir . '/' . $item, serialize($data));
	}
} 

?>