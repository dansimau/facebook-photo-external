<?php
/**
 * Extends the simple data store object to provide an interface for basic
 * caching. TTL is calculcated using the mtime of the stored files on the
 * filesystem.
 */
class CacheDataStore extends DataStore {

	public $cache_default_ttl;

	/**
	 * Constructs the CacheDataStore object.
	 *
	 * @param $datadir the directory to contain the cache data.
	 * @param $cache_default_ttl the default number of seconds before an item is considered stale.
	 */
	function __construct($datadir, $cache_default_ttl) {
	
		parent::__construct($datadir);
		$this->cache_default_ttl = $cache_default_ttl;
	}
	
	/**
	 * Returns the number of seconds since the data item was last modified.
	 *
	 * @param $item
	 * @return the number of seconds since the data item was last modified, or
	 *	null if the item does not exist.
	 */
	public function item_age($item) {

		// Check that the data item exists
		if ($this->item_exists($item)) {

			// Get file modified time
			$mtime = filemtime($this->datadir . '/' . $item);
			
			// (current time - modified time)
			return (time() - $mtime);
		} else {
			return null;	// If the item does not exit
		}
	}
	
	/**
	 * Returns the number of seconds until the data item is considered expired.
	 *
	 * @param $item
	 # @return int number of seconds until the data item is considered expired.
	 */
	public function item_ttl($item) {
	
		// Check that the data item exists
		if ($this->item_exists($item)) {
			return ($this->item_expires($item) - time());
		} else {
			return null;	// If the item does not exit
		}
	}
	
	
	/**
	 * Returns the date of when the cache item is going to expire.
	 *
	 * @param $item
	 * @return Date the date that the cache item expires, or null if the item
	 *	does not exist.
	 */
	public function item_expires($item) {
	
		// Check that the data item exists
		if ($this->item_exists($item)) {
			// Get file modified time
			$mtime = filemtime($this->datadir . '/' . $item);

			// (file date + ttl)
			return ($mtime + $this->cache_default_ttl);
		} else {
			return null;	// If the item does not exit
		}
	}
	
	/**
	 * Returns whether or not an item in the cache has expired.
	 *
	 * @param $item
	 * @return boolean whether or not the cache item has expired, or null if the
	 *	item does not exist.
	 */
	public function is_expired($item) {
	
		// Check that the data item exists
		if ($this->item_exists($item)) {
			if ($this->item_ttl($item) < 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return null;	// If the item does not exit
		}
	}

	/**
	 * Returns whether or not an item in the cache has expired.
	 *
	 * @param $item
	 * @return boolean whether or not the cache item has expired, or null if the
	 *	item does not exist.
	 */
	public function is_fresh($item) {
	
		// Check that the data item exists
		if ($this->item_exists($item)) {
		
			if ($this->item_ttl($item) > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return null;	// If the item does not exit
		}
	}
	
	/**
	 * Removes all files from the data directory.
	 *
	 * @return the number of files removed.
	 */
	public function clear_cache() {

		$count;

		// Loop through each directory entry in the data dir
		foreach (scandir($this->datadir) as $file) {

			// Skip this one if its a directory and not a file		
			if (!is_dir($file)) {
				if (unlink($file)) $count++;
			}
		}
		
		return $count;
	}
}

?>