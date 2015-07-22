<?php
namespace CPFCMembers;

class Vimeo {

    protected $_playlistId;
    protected $_cacheKey;
    protected $_lastCachedKey;
    protected $_data;
    protected $_keys;
    protected $_client;
    public $thumbnailSizes;

    /**
     * Constructor
     * @param string $playlistId URL of the YouTube Feed
     */
    public function __construct()
    {
        $this->_cacheKey = 'cpfc_vimeo_feed';
        $this->_lastCachedKey = $this->_cacheKey . '_last_cached';
        $this->_data = array();
        $this->_keys = array(
            'client_id'     => 'vimeo_feed_oauth_client_id',
            'client_secret' => 'vimeo_feed_oauth_client_secret',
            'access_token'  => 'cpfc_vimeo_api_access_token',
        );
    }

    public function authenticate()
    {
        $OAUTH2_CLIENT_ID = get_field($this->_keys['client_id'], 'option');
        $OAUTH2_CLIENT_SECRET = get_field($this->_keys['client_secret'], 'option');

        $this->_client = new \Vimeo\Vimeo($OAUTH2_CLIENT_ID, $OAUTH2_CLIENT_SECRET);

        $redirectUrl = filter_var(cpfc_home_url(CPFC_MEMBERS_VIMEO_AUTH_URL),
          FILTER_SANITIZE_URL);

        if (Input::get('code')) {
            if (strval(Session::get('state')) !== strval(Input::get('state'))) {
                die('The session state did not match.');
            }

            var_dump(Input::get());die();


            $tokens = $lib->accessToken($_GET['code'], REDIRECT_URI);

            if ($tokens['status'] == 200) {
                $_SESSION['access_token'] = $tokens['body']['access_token'];
                echo 'Successful authentication. Please go to <a href="http://localhost:8080">localhost:8080</a>';
            } else {
                echo "Unsuccessful authentication";
            }

            var_dump($tokens);
            die();





            /*$this->_client->authenticate($_GET['code']);
            $_SESSION['token'] = $this->_client->getAccessToken();
            update_option($this->_keys['access_token'], $this->_client->getAccessToken());
            header('Location: ' . $redirect);*/
        } else
            die('Auth Code not found');

        header('Location: ' . cpfc_home_url(CPFC_MEMBERS_YOUTUBE_AUTH_ADMIN_URL));
    }

    public function connect()
    {
        $OAUTH2_CLIENT_ID = get_field($this->_keys['client_id'], 'option');
        $OAUTH2_CLIENT_SECRET = get_field($this->_keys['client_secret'], 'option');

        $this->_client = new \Vimeo\Vimeo($OAUTH2_CLIENT_ID, $OAUTH2_CLIENT_SECRET);
    }

    public function checkAuth()
    {
        $this->connect();

        // Check to ensure that the access token was successfully acquired.
        if ($this->_client->request('/me')) {

            $htmlBody = <<<END
            <h3>Access to Vimeo Account - Authorised</h3>
            <p>You are able to fetch Vimeo data as required.<p>
END;
        } else {
            $redirectUrl = filter_var(cpfc_home_url(CPFC_MEMBERS_VIMEO_AUTH_URL), FILTER_SANITIZE_URL);
            $state = mt_rand();
            Session::set('state', $state);

            $authUrl = $lib->buildAuthorizationEndpoint($redirectUrl, 'public private', Session::get('state'));

            $htmlBody = <<<END
            <h3>Authorization Required</h3>
            <p>You need to <a href="$authUrl">authorize access</a> before proceeding.<p>
END;
            }

            echo $htmlBody;
    }

    /**
     * Fetch YouTube Feed and Store in Cache
     */
    public function fetch()
    {
        $this->connect();

        // Define an object that will be used to make all API requests.
        $youtube = new \Google_Service_YouTube($this->_client);

        // Call the channels.list method to retrieve information about the
        // currently authenticated user's channel.
        $channelsResponse = $youtube->channels->listChannels('contentDetails', array(
          'mine' => 'true',
        ));

        if ($channelsResponse['items']) {

            $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet,status', array(
                'playlistId' => $this->_playlistId,
                'maxResults' => 50
            ));

            if ($playlistItemsResponse['items']) {
                return $playlistItemsResponse['items'];
            }
        }

        return false;
    }

    /**
     * Fetch YouTube Feed and Store in Cache
     */
    public function fetchAndCache($cacheCount)
    {
        $lastCached = (int) get_option($this->_lastCachedKey);
        $cacheExpiry = ((int) get_field('youtube_feed_cache_expiry', 'option')) * 60;
        $cacheEnabled = get_field('youtube_feed_cache_enabled', 'option');

        if ($cacheExpiry && $lastCached < (time() - $cacheExpiry)) {

            $feedData = $this->fetch();

            if ($feedData) {
                $cached = $this->fetchFromCache();

                $this->process($feedData, $cached);

                if ($cacheCount) {
                    $this->data = array_slice($this->data, 0, $cacheCount);
                }

                $this->fetchImages();

                if ($cacheEnabled) {
                    $this->storeInCache($this->data);
                }

                return $this->data;

            } else
                throw new \Exception('Playlist is empty');
        }
    }

    public function storeInCache()
    {
        update_option($this->_cacheKey, $this->data);
        update_option($this->_lastCachedKey, time());
    }

    /**
     * Process the feed including fetching images
     * @param  SimpleXml $data    New feed
     * @param  array  $cachedData Cached Data
     */
    public function process($newData, $cachedData)
    {
        $cachedData = (array) $cachedData;
        $data = array();
        foreach ($newData as $item) {
            if ('public' == $item['status']->privacyStatus) {
                $exists = false;

                $videoId = (string) $item['snippet']['resourceId']['videoId'];

                foreach ($cachedData as $cachedItem) {
                    if ($cachedItem == $videoId) {
                        $exists = true;
                    }
                }

                if (!$exists) {
                    $data[$videoId] = array(
                        'videoId'        => $videoId,
                        'title'          => $item['snippet']['title'],
                        'description'    => $item['snippet']['description'],
                        'publishDate'    => $item['snippet']['publishedAt'],
                        'timestamp'      => strtotime($item['snippet']['publishedAt']),
                        'thumbnails'     => array(),
                        'localImage'     => '',
                        'cachedDateTime' => date('Y-M-d H:i:s'),
                    );

                    foreach ($this->thumbnailSizes as $thumbnailSize) {
                        if (isset($item['snippet']['thumbnails'][$thumbnailSize])) {
                            $data[$videoId]['thumbnails'][$thumbnailSize] = array(
                                'url'    => $item['snippet']['thumbnails'][$thumbnailSize]['url'],
                                'width'  => $item['snippet']['thumbnails'][$thumbnailSize]['width'],
                                'height' => $item['snippet']['thumbnails'][$thumbnailSize]['height'],
                            );
                        }
                    }
                }
            }
        }

        $this->data = array_merge($data, $cachedData);
    }

    /**
     * Fetch image for thumbnail
     */
    public function fetchImages()
    {
        foreach ($this->data as $index => $item) {
            if (!$item['localImage']) {
                foreach ($this->thumbnailSizes as $thumbnailSize) {
                    if (isset($item['thumbnails'][$thumbnailSize])) {
                        $item['localImage'] = $this->fetchImage($item['thumbnails'][$thumbnailSize]['url'], $item['videoId']);
                        break 1;
                    }
                }

                $this->data[$index] = $item;
            }
        }
    }

    /**
     * Fetch main article images for specified URL
     */
    public function fetchImage($url, $customFilename = '')
    {
        $remoteImageUpload = new RemoteImageUpload();
        $remoteImageUpload->customFilename = 'youtube_feed_' . $customFilename;
        $attachmentIdentifier = $remoteImageUpload->processImage($url);

        if ($attachmentIdentifier)
            return $attachmentIdentifier;
    }

    /**
     * Fetch current YouTube Feed stored in cache
     */
    public function fetchFromCache($count = 0, $offset = 0)
    {
        $data = get_option($this->_cacheKey, array());

        $count = (int) $count;
        $offset = (int) $offset;
        if ($count && count($data) >= $count) {
            return array_slice($data, $offset, $count);
        }

        return $data;
    }

    /**
     * Clear the stored cache
     */
    public function clearCache()
    {
        update_option($this->_cacheKey, array());
        update_option($this->_lastCachedKey, 0);
    }

    /**
     * Clear the stored cache
     */
    public function fetchUserPlaylists()
    {
        $this->connect();

        $playlistData = array();
        // Check to ensure that the access token was successfully acquired.
        if ($this->_client->getAccessToken()) {
            try {
                $youtube = new \Google_Service_YouTube($this->_client);

                // Call the channels.list method to retrieve information about the
                // currently authenticated user's channel.
                $channelsResponse = $youtube->channels->listChannels('contentDetails', array(
                  'mine' => 'true',
                ));

                foreach ($channelsResponse['items'] as $channel) {
                    $playlists = $channel['contentDetails']['relatedPlaylists'];

                    $playlistData = array();
                    foreach ($playlists as $playlistType => $playlistId) {
                        $playlistData[] = array(
                            'type' => $playlistType,
                            'playlistId' => $playlistId,
                        );
                    }
                }
            } catch (\Google_ServiceException $e) {
                $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
            } catch (\Google_Exception $e) {
                $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
            }
        }

        return $playlistData;
    }

    /**
     * Clear the stored settings
     */
    public function clearSettings()
    {
        $this->clearCache();

        foreach ($this->_keys as $keyName) {
            update_option($keyName, '');
        }
    }
}