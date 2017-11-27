# Youtube PHP Parser

Get datas form a Youtube video, without API account, directly form your browser url 

## Getting Started

You only need to include the /src/youtube.class.php in your website

### URL examples

* Simple video : https://www.youtube.com/watch?v=cIUoMsV80r8
* Playlist : https://www.youtube.com/watch?v=VIkr-RcQ4l0&list=RD2106qUYzqJg&index=2

### How to use

This is a simple php class so:

```
require_once 'src/youtube.class.php'
...
$videoUrl = 'https://www.youtube.com/watch?v=XXX'
$videoYoutube = new VideoYoutube($videoUrl);
```

## Methods

### Check your url validity 

And if the video still exists

```
<?php if ($videoYoutube->isValid()): ?>
    <!-- My treatments -->
<?php else: ?>
    <p>Invalid url or inexisting video</p>
<?php endif; ?>
```

### Get methods

```
$myData = $video->get('mytype');
```

#### Available datas types :

| Variable | Returned type | Description |
|---|---|---|
| url | string | used in the class constructor |
| id | int | Youtube video id |
| title | string | Youtube video title |
| thumbnail | array | Thumbnail details (such as src, width, ...) |
| author | array | Get author's name and channel url |
| iframe | array | Thumbnail details (such as src, width, ...) |

## Rendering methods

Render the thumbnail or an iframe of the video

```
$myType = 'thumbnail';
$myCustomAtributes = array('width' => 200, 'title' => 'My custom picture title') ;
echo $video->render('mytype', $myCustomAtributes);
```

| Parameter | Type | Description |
|---|---|---|
| type | string | thumbnail or iframe |
| parameters | array | Associative array of additionnal html attributes (or override existings)

### Default attributes

#### thumbnail

* src (required)
* alt (required)
* width

#### iframe

* src (required)
* width 
* height
* frameborder (= 0)
* allowfullscreen (= true)
