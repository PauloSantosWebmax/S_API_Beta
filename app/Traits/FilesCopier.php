<?php 

/**
 * This trait is only to use in case o need to copy files 
 * from one dir to other, only works for windows systems
 * the directories path's are stored within the .en file,
 * under the namespace "IMAGES_".
 * To use this functionalitie please edit the .env file stored in the
 * root of the API folder.
 */

namespace Sage\Traits;

trait FilesCopier
{
	/**
	 * @var directory to copy
	 */
	protected $from = /*env('IMAGES_FROM')*/"C:\\xampp\\htdocs\\imagens\\";
	 
	/**
	 * @var final path
	 */
	protected $to = /*env('IMAGES_TO')*/"C:\\xampp\\htdocs\\SAGE_API_BETA\\public\\0856321452\\";
	
	/**
	 * Copy all files from sage image directory,
	 * to webmax api webservice.
	 *
	 * @return void
	 */
	public function initializeCopy()
	{
		// scan the current directory
		$dir = scandir($this->from);
		
		// clean the pointer from windows
		$dir = array_diff($dir, ['.', '..']);

		// copy files one by one to public directory in API folder within xampp/htdocs...
		foreach ($dir as $file) {
			copy($this->from . $file, $this->to . $file);
		}
	}
}
