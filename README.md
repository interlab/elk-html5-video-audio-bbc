# HTML5 video and audio bbcode support for ElkArte

## Examples

### Audio
```
[html5audio]http://your-url.mp3[/html5audio]
[html5audio]http://your-url.ogg[/html5audio]
[html5audio]http://your-url.wav[/html5audio]
```
### Video
```
[html5video]http://your-url.mp4[/html5video]
[html5video]http://your-url.webm[/html5video]
[html5video]http://your-url.ogg[/html5video]
[html5video]http://your-url.ogv[/html5video]
```

## ElkArte site
[Support and comments for this mod](http://www.elkarte.net/community/index.php?topic=3976.0)

## License
MIT

## Icons license
icons by Nick Roach  http://www.elegantthemes.com/ License: GPL

## Problems
if no effect and your server is apache, add this code to your .htaccess file:

```
# Multimedia MIME types
AddType audio/mpeg .mp3
AddType audio/ogg .ogg
AddType audio/wav .wav
AddType video/mp4 .mp4
AddType video/webm .webm
AddType video/ogg .ogv
```
