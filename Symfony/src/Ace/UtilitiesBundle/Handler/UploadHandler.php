<?php
/*
 * jQuery File Upload Plugin PHP Class 5.11.2
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

 namespace Ace\UtilitiesBundle\Handler;
 
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Bundle\FrameworkBundle\Controller\Controller;
 
class UploadHandler
{
    protected $options;	
	protected $up;
	
    function __construct($options=null, $script = null, Controller $up) {
		
		$this->up = $up;
		
        $this->options = array(
          //  'script_url' => $this->getFullUrl().'/',
           // 'upload_dir' => dirname($_SERVER['SCRIPT_FILENAME']).'/files/',
            'upload_url' => $this->getFullUrl().'/sketch:',
            'param_name' => 'files',
            // Set the following option to 'POST', if your server does not support
            // DELETE requests. This is a parameter sent to the client:
            'delete_type' => 'DELETE',
            // The php.ini settings upload_max_filesize and post_max_size
            // take precedence over the following max_file_size setting:
            'max_file_size' => null,
            'min_file_size' => 1,
            'accept_file_types' => '/(\.|\/)(pde|ino|zip)$/i', //'/.+$/i',
            // The maximum number of files for the upload directory:
            'max_number_of_files' => null,
            // Image resolution restrictions:
            'max_width' => null,
            'max_height' => null,
            'min_width' => 1,
            'min_height' => 1,
            // Set the following option to false to enable resumable uploads:
            'discard_aborted_uploads' => true
        );
        if ($options) {
            $this->options = array_replace_recursive($this->options, $options);
        }
    }

    protected function getFullUrl() {
        $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
      	return
    		($https ? 'https://' : 'http://').
    		(!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
    		(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
    		($https && $_SERVER['SERVER_PORT'] === 443 ||
    		$_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
    		substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    }

    /* protected function set_file_delete_url($file) {
        $file->delete_url = $this->options['script_url']
            .'?file='.rawurlencode($file->name);
        $file->delete_type = $this->options['delete_type'];
        if ($file->delete_type !== 'DELETE') {
            $file->delete_url .= '&_method=DELETE';
        }
    }
   */

     protected function validate($uploaded_file, $file, $error, $index) {
        if ($error) {
            $file->error = $error;
            return false;
        }
        if (!$file->name) {
            $file->error = 'missingFileName';
            return false;
        }
        if (!preg_match($this->options['accept_file_types'], $file->name)) {
            $file->error = 'acceptFileTypes';
            return false;
        }
        if ($uploaded_file && is_uploaded_file($uploaded_file)) {
            $file_size = filesize($uploaded_file);
        } else {
            $file_size = $_SERVER['CONTENT_LENGTH'];
        }
         if ($this->options['max_file_size'] && (
                $file_size > $this->options['max_file_size'] ||
                $file->size > $this->options['max_file_size'])
            ) {
            $file->error = 'maxFileSize';
            return false;
        } 
        if ($this->options['min_file_size'] &&
            $file_size < $this->options['min_file_size']) {
            $file->error = 'minFileSize';
            return false;
        } 
        
        return true;
    } 

    protected function upcount_name_callback($matches) {
        $index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        $ext = isset($matches[2]) ? $matches[2] : '';
        return ' ('.$index.')'.$ext;
    }

    protected function upcount_name($name) {
        return preg_replace_callback(
            '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/',
            array($this, 'upcount_name_callback'),
            $name,
            1
        );
    }

       protected function trim_file_name($name, $type, $index) {
        // Remove path information and dots around the filename, to prevent uploading
        // into different directories or replacing hidden system files.
        // Also remove control characters and spaces (\x00..\x20) around the filename:
        $file_name = trim(basename(stripslashes($name)), ".\x00..\x20");     
        return $file_name;
    }  

  

    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null) {
        $file = new \stdClass();
        $file->name = $name;//$this->trim_file_name($name, $type, $index);
        $file->size = intval($size);
        $file->type = $type;			
		$info = pathinfo($name);
	    $fileName =  basename($name,'.'.$info['extension']);
		$file->url = $this->options['upload_url'];
        $this->validate($uploaded_file, $file, $error, $index);
        return $file;
    }

    public function post($error) {
        if (isset($_REQUEST['_method']) && $_REQUEST['_method'] === 'DELETE') {
            return $this->delete();
        }
        $upload = isset($_FILES[$this->options['param_name']]) ?
            $_FILES[$this->options['param_name']] : null;
        $info = array();
        if ($upload && is_array($upload['tmp_name'])) {
            // param_name is an array identifier like "files[]",
            // $_FILES is a multi-dimensional array:
		  if(isset($error)){
            foreach ($upload['tmp_name'] as $index => $value) {
                $info[] = $this->handle_file_upload(
                    $upload['tmp_name'][$index],
                    isset($_SERVER['HTTP_X_FILE_NAME']) ?
                        $_SERVER['HTTP_X_FILE_NAME'] : $upload['name'][$index],
                    isset($_SERVER['HTTP_X_FILE_SIZE']) ?
                        $_SERVER['HTTP_X_FILE_SIZE'] : $upload['size'][$index],
                    isset($_SERVER['HTTP_X_FILE_TYPE']) ?
                        $_SERVER['HTTP_X_FILE_TYPE'] : $upload['type'][$index],
                    $error,										//$upload['error'][$index]
                    $index
                );}
			} else {
			foreach ($upload['tmp_name'] as $index => $value) {
                $info[] = $this->handle_file_upload(
                    $upload['tmp_name'][$index],
                    isset($_SERVER['HTTP_X_FILE_NAME']) ?
                        $_SERVER['HTTP_X_FILE_NAME'] : $upload['name'][$index],
                    isset($_SERVER['HTTP_X_FILE_SIZE']) ?
                        $_SERVER['HTTP_X_FILE_SIZE'] : $upload['size'][$index],
                    isset($_SERVER['HTTP_X_FILE_TYPE']) ?
                        $_SERVER['HTTP_X_FILE_TYPE'] : $upload['type'][$index],
                    $upload['error'][$index],
                    $index
                );}										
			}				
        } elseif ($upload || isset($_SERVER['HTTP_X_FILE_NAME'])) {
            // param_name is a single object identifier like "file",
            // $_FILES is a one-dimensional array:			
			$info[] = $this->handle_file_upload(
                isset($upload['tmp_name']) ? $upload['tmp_name'] : null,
                isset($_SERVER['HTTP_X_FILE_NAME']) ?
                    $_SERVER['HTTP_X_FILE_NAME'] : (isset($upload['name']) ?
                        $upload['name'] : null),
                isset($_SERVER['HTTP_X_FILE_SIZE']) ?
                    $_SERVER['HTTP_X_FILE_SIZE'] : (isset($upload['size']) ?
                        $upload['size'] : null),
                isset($_SERVER['HTTP_X_FILE_TYPE']) ?
                    $_SERVER['HTTP_X_FILE_TYPE'] : (isset($upload['type']) ?
                        $upload['type'] : null),
                isset($upload['error']) ? $upload['error'] : null ); 
        }
        
        return $info;
    }
	
	
	public function fixFile($info, $sketch_id, $project_name, $ext)
	{
		$File = new \stdClass();

		$vars = get_object_vars($info[0]);

		if(isset($sketch_id)){

		foreach($vars as $name => $value) {
			if($name == 'url'){
				$File->$name = $value.$sketch_id;
			}else if ($name == 'name' && $ext == "zip"){
				$File->$name = $project_name;
			}else{
				$File->$name = $value;
			}
		}
		}else {$File->error = "Failed to create Project.";}

		return $File;
	}
	
	public function createUploadedFile($id, $filename, $code)
	{		
		$projectmanager = $this->up->get('ace_project.sketchmanager');
		$response = $projectmanager->createFileAction($id, $filename, $code)->getContent();
		$response = json_decode($response, true);
		
		return $response["success"];
	}
	
	public function createUploadedProject($file_name)
	{
		$user = json_decode($this->up->get('ace_user.usercontroller')->getCurrentUserAction()->getContent(), true);

		$exp = explode(".", $file_name);
		$project_name =  $exp[0];

			$projectmanager = $this->up->get('ace_project.sketchmanager');
			$response1 = $projectmanager->createAction($user["id"], $project_name, "")->getContent();
			$response1=json_decode($response1, true);
			if($response1["success"]){
				$sketch_id = $response1["id"];
			} else {
				$sketch_id = null;
			}
		return $sketch_id;
	}

}
