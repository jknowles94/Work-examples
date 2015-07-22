<?php
namespace CPFCMembers;

class Vimeo {

    protected $_cacheKey;
    protected $_lastCachedKey;
    protected $_data;

    /**
     * Constructor
     * @param string $playlistId URL of the YouTube Feed
     */
    public function __construct()
    {
        $this->_cacheKey = 'cpfc_vimeo_feed';
        $this->_lastCachedKey = $this->_cacheKey . '_last_cached';
        $this->_data = array();
    }

    /**
     * Fetch YouTube Feed and Store in Cache
     */
    public function fetch()
    {
        $oldData = get_field('homepage_logged_in_exclusive_videos_videos', 8);

        $homepageSlides = get_field('homepage_carousel_slides', 8);

        return $homepageSlides;
    }

    /**
     * Fetch Vimeo Feed and Store in Cache
     */
    public function fetchAndCache()
    {
        $feedData = $this->fetch();

        if ($feedData) {
            $cached = $this->fetchFromCache();

            $this->process($feedData, $cached);

            $this->fetchImages();

            $this->storeInCache($this->data);

        }

        return $this->data;
    }

    public function storeInCache()
    {
        update_option($this->_cacheKey, $this->data);
    }

    /**
     * Process the feed including fetching images
     * @param  array  $slides     New Data
     * @param  array  $cachedData Cached Data
     */
    public function process($slides, $cachedData)
    {
        $data = array();
        foreach ($slides as $slide) {
            if (isset($slide['videos'])) {
                foreach ($slide['videos'] as $video) {
                    if ($video['type'] == 'vimeo') {
                        $exists = false;
                        $videoId = (string) $video['video_id'];

                        $videoJson = file_get_contents('http://vimeo.com/api/oembed.json?url=http%3A//vimeo.com/' . $videoId);

                        if ($videoJson) {
                            $videoData = json_decode($videoJson);

                            $data['vimeo_' . $videoData->video_id] = array(
                                'videoId'        => $videoId,
                                'title'          => $videoData->title,
                                'description'    => $videoData->description,
                                'thumbnail_url'  => $videoData->thumbnail_url,
                                'localImage'     => '',
                                'cachedDateTime' => date('Y-M-d H:i:s'),
                            );
                        }
                    }
                }
            }
        }

        $this->data = $data;
    }

    /**
     * Fetch image for thumbnail
     */
    public function fetchImages()
    {
        foreach ($this->data as $index => $item) {
            if (!$item['localImage']) {
                $item['localImage'] = $this->fetchImage($item['thumbnail_url'], $item['videoId']);

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
        $remoteImageUpload->customFilename = 'vimeo_feed_' . $customFilename;
        $attachmentIdentifier = $remoteImageUpload->processImage($url);

        if ($attachmentIdentifier)
            return $attachmentIdentifier;
    }

    /**
     * Fetch current YouTube Feed stored in cache
     */
    public function fetchVideoInfoFromCache($videoId)
    {
        $cachedData = $this->fetchFromCache();

        return isset($cachedData['vimeo_' . $videoId]) ? $cachedData['vimeo_' . $videoId] : false;
    }

    /**
     * Fetch current YouTube Feed stored in cache
     */
    public function fetchFromCache()
    {
        return get_option($this->_cacheKey, array());
    }

    /**
     * Clear the stored cache
     */
    public function clearCache()
    {
        update_option($this->_cacheKey, array());
    }
}