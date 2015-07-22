<?php
namespace CPFCMembers;

class Rss_Feed {

    protected $_url;
    protected $_cacheKey;
    protected $_lastCachedKey;
    protected $_data;
    protected $_cacheCount;

    /**
     * Constructor
     * @param string $url URL of the RSS Feed
     */
    public function __construct($url)
    {
        if (!extension_loaded('simplexml') && !extension_loaded('xml')) {
            throw new \Exception('No suitable XML Parser exists.');
        }

        $this->_url = $url;
        $this->_cacheKey = 'cpfc_rss_' . sha1($url);
        $this->_lastCachedKey = $this->_cacheKey . '_last_cached';
        $this->_data = array();
    }

    /**
     * Fetch RSS Feed and Store in Cache
     */
    public function fetchAndCache($cacheCount)
    {
        $lastCached = (int) get_option($this->_lastCachedKey);
        $cacheExpiry = ((int) get_field('rss_feed_cache_expiry', 'option')) * 60;
        $cacheEnabled = get_field('rss_feed_cache_enabled', 'option');
        $this->_cacheCount = (int) $cacheCount;

        if ($cacheExpiry && $lastCached < (time() - $cacheExpiry)) {
            $xml = file_get_contents($this->_url, false);

            if ($xml !== false) {
                $parsedXml = simplexml_load_string($xml);

                if ($parsedXml !== false) {

                    $this->process($parsedXml, $this->fetchFromCache());

                    if ($cacheCount) {
                        $this->data = array_slice($this->data, 0, $cacheCount);
                    }

                    $this->fetchImages();

                    if ($cacheEnabled) {
                        $this->storeInCache($this->data);
                    }

                    return $this->data;

                } else
                    throw new \Exception('RSS Feed couldn\'t be parsed.');

            } else
                throw new \Exception('RSS Feed couldn\'t be retrieved.');
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
        $i = 0;
        foreach ($newData->channel->item as $item) {
            if ($i >= $this->_cacheCount) {
                break;
            }
            $exists = false;
            foreach ($cachedData as $cachedItem) {
                if ($cachedItem == $item->link) {
                    $exists = true;
                }
            }

            if (!$exists) {
                $url = (string) $item->link;
                $index = sha1($url);
                $data[$index] = array(
                    'title'          => (string) $item->title[0],
                    'url'            => (string) $item->link,
                    'content'        => $this->fetchContent((string) $item->link),
                    'timestamp'      => strtotime((string) $item->pubDate),
                    'publishDate'    => (string) $item->pubDate,
                    'localImage'     => '',
                    'cachedDateTime' => date('Y-M-d H:i:s'),
                );

            }
            $i++;
        }

        $this->data = array_merge($data, $cachedData);
    }

    /**
     * Fetch main article localImages for listed URLs
     */
    public function fetchImages()
    {
        foreach ($this->data as $index => $item) {
            if (!$item['localImage']) {
                $item['localImage'] = $this->fetchImage($item['url']);

                $this->data[$index] = $item;
            }
        }
    }

    /**
     * Fetch main article images for specified URL
     */
    public function fetchImage($url)
    {
        $pageSource = @file_get_contents($url);

        if ($pageSource !== false) {
            preg_match_all('/<img[^>]+>/i', $pageSource, $images);

            foreach ($images[0] as $image) {
                $xml = @simplexml_load_string($image);
                if ($xml) {
                    $classes = $xml->xpath("//@class");

                    $class = $classes ? (string) $classes[0]['class'] : '';

                    // If the image is the main article image
                    if ($class == 'article_img') {
                        $src = $xml->xpath("//@src");
                        $src = $src ? (string) $src[0]['src'] : '';

                        // If the image path doesn't include the domain...
                        if (strpos($src, 'http') === false) {

                            $urlParts = parse_url($url);
                            $src = $urlParts['scheme'] . '://' . $urlParts['host'] . $src;

                            $remoteImageUpload = new RemoteImageUpload();
                            $attachmentIdentifier = $remoteImageUpload->processImage($src);

                            if ($attachmentIdentifier) {
                                return $attachmentIdentifier;
                            } else
                                return false;
                        }

                        return $src;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Fetch main article content for specified URL
     */
    public function fetchContent($url)
    {
        $pageSource = @file_get_contents($url);

        if ($pageSource !== false) {
            preg_match_all('/<div id="articleContentWrapper">((?s).*)<ul id="paginationButton">/i', $pageSource, $divs);

            if (isset($divs[1][0])) {
                //return trim(strip_tags($divs[1][0], '<br><br/><a></a>'));
                return trim(strip_tags($divs[1][0]));
            }
        }

        return '';
    }

    /**
     * Fetch current RSS Feed stored in cache
     */
    public function fetchFromCache($count = 0, $offset = 0)
    {
        $data = get_option($this->_cacheKey, array());

        $count = (int) $count;
        if ($count && count($data) >= $count) {
            return array_slice($data, $offset, $count);
        }

        return $data;
    }

    /**
     * Fetch current RSS Feed stored in cache
     */
    public function fetchSingleFromCache($identifier)
    {
        $data = $this->fetchFromCache();

        return isset($data[$identifier]) ? $data[$identifier] : false;
    }
}