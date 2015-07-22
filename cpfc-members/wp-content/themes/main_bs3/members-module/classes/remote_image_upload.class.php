<?php
namespace CPFCMembers;

class RemoteImageUpload
{
    protected $_directoryName;
    public $customFilename;

    /**
     * Constructor
     */
    public function __construct()
    {
        global $wp_version;

        $this->customFilename = '';

        if ($wp_version < 3.5) {
            if ( basename($_SERVER['PHP_SELF']) != "media-upload.php" ) return;
        } else {
            if ( basename($_SERVER['PHP_SELF']) != "media-upload.php" && basename($_SERVER['PHP_SELF']) != "post.php" && basename($_SERVER['PHP_SELF']) != "post-new.php") return;
        }

        if ( !function_exists("curl_init") && !ini_get("allow_url_fopen") ) {
            echo '<div id="message" class="error"><p><b>cURL</b> or <b>allow_url_fopen</b> needs to be enabled. Please consult your server Administrator.</p></div>';
        }
    }

    /**
     * Set the Directory Name the image should be stored in
     * @param [type] $name [description]
     */
    public function setDirectoryName($name)
    {
        $this->_directoryName = $name;

        return $this;
    }

    /**
     * get the Directory Name the image should be stored in
     * @return [type] [description]
     */
    public function getDirectoryName()
    {
        return $this->_directoryName;
    }

    /**
     * Fetch image from the remote url
     * @param  string $url The External URL
     * @return image
     */
    public function fetchImage($url)
    {
        if ( function_exists("curl_init") ) {
            return $this->curlFetchImage($url);
        } elseif ( ini_get("allow_url_fopen") ) {
            return $this->fopenFetchImage($url);
        }
    }

    /**
     * Fetch Remote Image using the cURL method
     * @param  string $url The External URL
     * @return image
     */
    public function curlFetchImage($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $image = curl_exec($ch);
        curl_close($ch);
        return $image;
    }


    /**
     * Fetch Remote Image using the fopen method
     * @param  string $url The External URL
     * @return image
     */
    public function fopenFetchImage($url)
    {
        $image = file_get_contents($url, false, $context);
        return $image;
    }

    /**
     * Run image process
     * @param  string $imageUrl The External URL
     * @return string           New Image path
     */
    public function processImage($imageUrl, $saveToDB = false)
    {
        $imageurl = $imageUrl;
        $imageurl = stripslashes($imageurl);
        $uploads = wp_upload_dir();
        $post_id = isset($_GET['post_id'])? (int) $_GET['post_id'] : 0;
        $ext = pathinfo( basename($imageurl) , PATHINFO_EXTENSION);
        $newfilename = $this->customFilename ? $this->customFilename . "." . $ext : basename($imageurl);

        // If file already exists locally then use that one
        if (file_exists($uploads['path'] . '/' . str_replace('--', '-',$newfilename))) {
            return $uploads['url'] . '/' . str_replace('--', '-',$newfilename);
        }

        $filename = wp_unique_filename($uploads['path'], $newfilename, $unique_filename_callback = null);
        $wp_filetype = wp_check_filetype($filename, null);
        $fullPathFilename = $uploads['path'] . '/' . $filename;
        $webPath = $uploads['url'] . '/' . $filename;

        // Remove remote path
        $webPath = str_replace(cpfc_home_url(), '', $webPath);
        $webPath = '/' . ltrim($webPath, '/');

        try {
            if ( !substr_count($wp_filetype['type'], "image") ) {
                throw new \Exception( basename($imageurl) . ' is not a valid image. ' . $wp_filetype['type']  . '' );
            }

            $image_string = $this->fetchImage($imageurl);
            $uploadPath =
            $fileSaved = file_put_contents($uploads['path'] . "/" . $filename, $image_string);
            if ( !$fileSaved ) {
                throw new \Exception("The file cannot be saved.");
            }

            if ($saveToDB) {
                $attachment = array(
                     'post_mime_type' => $wp_filetype['type'],
                     'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                     'post_content' => '',
                     'post_status' => 'inherit',
                     'guid' => $uploads['url'] . "/" . $filename
                );
                $attach_id = wp_insert_attachment( $attachment, $fullPathFilename, $post_id );
                if ( !$attach_id ) {
                    throw new \Exception("Failed to save record into database.");
                }
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                $attach_data = wp_generate_attachment_metadata( $attach_id, $fullPathFilename );
                wp_update_attachment_metadata( $attach_id,  $attach_data );

                return $attach_id;
            }

            return $webPath;

        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        return false;
    }
}