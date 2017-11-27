<?php

/**
 * Class Youtube
 */
class VideoYoutube
{
    public $url;
    public $id;
    public $title;
    public $thumbnail;
    public $author;
    public $iframe;

    private $apiDatas;


    /**
     * Youtube constructor.
     *
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->id = null;
        $this->title = '';
        $this->thumbnail = array();
        $this->author = array();
        $this->iframe = null;

        $this->apiDatas = array();

        $this->setDatas();
    }

    /**
     * Get datas form youtube video url (such as thumbnail)
     * @param string $url Youtube Video URL (ex : https://www.youtube.com/watch?v=2106qUYzqJg&list=RD2106qUYzqJg&t=3)
     * @return array
     */
    public function setDatas()
    {
        $apiUrl = sprintf('http://www.youtube.com/oembed?url=%s&format=json', urlencode($this->url));
        $apiDatas = @file_get_contents($apiUrl);

        if ($apiDatas !== false) {

            $apiDatas = json_decode($apiDatas);

            $this->id = $this->extractVideoId();

            if ($apiDatas) {

                $this->apiDatas = $apiDatas;

                $this->title = $apiDatas->title;

                $this->author = array(
                    'name' => $apiDatas->author_name,
                    'channel' => $apiDatas->author_url
                );

                $this->thumbnail = array(
                    'src' => $apiDatas->thumbnail_url,
                    'alt' => $this->title,
                    'width' => $apiDatas->thumbnail_width
                );
                $this->iframe = array(
                    'default' => $apiDatas->html,
                    'attributes' => array(
                        'width' => $apiDatas->width,
                        'height' => $apiDatas->height,
                        'src' => $this->extractIframeSrc($apiDatas->html)
                    )
                );
            }
        } else {
            echo sprintf('%s is not a valid Youtube url or the video does no longer exist', $this->url);
        }
    }

    /**
     * Check if video url is valid
     * @return bool
     */
    public function isValid()
    {
        return (empty($this->apiDatas)) ? false : true;
    }

    /**
     * Get a public data from video (url / id / title / thumbnail / author / iframe)
     * @param $index
     * @return null
     */
    public function get($index)
    {
        return (isset($this->{$index}) && (!empty($this->{$index}))) ? $this->{$index} : null;
    }

    /**
     * Render thumbnail or iframe with custom facultative attributes
     * @param string $type
     * @param array $attributes
     */
    public function render(string $type, $attributes = array())
    {
        if (empty($this->apiDatas) || !in_array($type, array('thumbnail', 'iframe'))) {
            return;
        }

        $method = 'render' . ucfirst($type);
        $output = $this->$method($attributes);

        return $output;
    }



    /**
     * Render a thumbnail element
     * @param array $attributes
     * @return string
     */
    private function renderThumbnail($attributes = array())
    {
        $output = '<img ';
        $this->renderAttributes($output, $this->thumbnail, $attributes);
        $output .= '/>';

        return $output;
    }

    /**
     * Render an iframe element
     * @param array $attributes
     * @return mixed|string
     */
    private function renderIframe($attributes = array())
    {
        $output = '<iframe ';

        $attributes['frameborder'] = 0;
        $attributes['allowfullscreen'] = true;

        $this->renderAttributes($output, $this->iframe['attributes'], $attributes);

        $output .= '></iframe>';

        return $output;
    }

    /**
     * Echoes html5 attributes tags
     * @param $output
     * @param $defaultAttributes
     * @param $customAttributes
     */
    private function renderAttributes(&$output, $defaultAttributes, $customAttributes)
    {
        if (!empty($defaultAttributes)) {
            $attributes = array_merge($defaultAttributes, $customAttributes);
            foreach ($attributes as $attribute => $value) {
                $output .= sprintf('%s="%s" ', $attribute, $value);
            }
        }
    }

    /**
     * Extract video ID from any Youtube URL
     * @param $url
     * @return mixed
     */
    private function extractVideoId()
    {
        preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $this->videoUrl, $matches);
        return (is_array($matches)) ? $matches[0] : $matches;
    }

    /**
     * Extract src value from an iframe html tag
     * @param $iframeHtml
     * @return mixed
     */
    private function extractIframeSrc($iframeHtml)
    {
        preg_match('/src="([^"]+)"/', $iframeHtml, $iframeSrc);
        return $iframeSrc[1];
    }

}