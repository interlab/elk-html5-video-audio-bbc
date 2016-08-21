<?php

class HTML5VideoAudioBBC
{
    // Subs.php
    static public function integrate_bbc_codes(&$codes, &$no_autolink_tags, &$itemcodes)
    {
        global $modSettings;

        loadLanguage('HTML5VideoAudioBBC');
        
        // Only for when bbc is on
        if (empty($modSettings['enableBBC'])) {
            return;
        }

        // Make sure the admin has not disabled the gist tag
        if (!empty($modSettings['disabledBBC']))
        {
            foreach (explode(',', $modSettings['disabledBBC']) as $tag)
            {
                if ('html5video' === $tag || 'html5audio' === $tag)
                    return;
            }
        }

        // audio
        $codes[] = [
            'tag' => 'html5audio',
            'type' => 'unparsed_content',
            'content' => '$1',
            'validate' => create_function('&$tag, &$data, $disabled', '
                global $txt;

                $link = $data;

                if (empty($data))
                    $data = \'[html5audio]\' . $data . \'[/html5audio]\';
                elseif (!in_array(strrchr($data, "."), array(".mp3", ".ogg")))
                    $data = \'[html5audio]\' . $data . \'[/html5audio]\';
                else {
                    $type = HTML5VideoAudioBBC::getHtml5MediaType($data, "audio");
                    $data = \'src="\' . $data . \'" type="\' . $type . \'"\';
                }
                $data = \'
<audio controls>
    <source \' . $data . \'>
    \' . sprintf($txt[\'html5audiobbc_not_support\'], $link) . \'<br>

</audio>\';
            '),
        ];
            
        // video
        $codes[] = [
            'tag' => 'html5video',
            'type' => 'unparsed_content',
            'content' => '$1',
            'validate' => create_function('&$tag, &$data, $disabled', '
                global $txt;

                $link = $data;

                if (empty($data))
                    $data = \'[html5video]\' . $data . \'[/html5video]\';
                elseif (!in_array(strrchr($data, \'.\'), array(\'.mp4\', \'.webm\', \'.ogg\')))
                    $data = \'[html5video]\' . $data . \'[/html5video]\';
                else {

                    $type = HTML5VideoAudioBBC::getHtml5MediaType($data, \'video\');

                    $data = \'src="\' . $data . \'" type="\' . $type . \'"\';
                }

                # <video width="320" height="240" controls>
                $data = \'
<video controls>
    <source \' . $data . \'>
    \' . sprintf($txt[\'html5videobbc_not_support\'], $link) . \'<br>
</video>\';
            '),
        ];
        
        $no_autolink_tags[] = 'html5audio';
        $no_autolink_tags[] = 'html5video';
        // $codes = array_merge($codes, $ary);
    }

    // Editor.subs.php
    static public function integrate_bbc_buttons(&$bbc_tags)
    {
        global $context;
        
        $where = $bbc_tags['row2'][3];
        // And here we insert the new value after code
        $bbc_tags['row2'][3] = elk_array_insert($where, 'image', array('html5video'), 'after', false);
        
        
        $where = $bbc_tags['row2'][3];
        // And here we insert the new value after code
        $bbc_tags['row2'][3] = elk_array_insert($where, 'html5video', array('html5audio'), 'after', false);
        //$where = $bbc_tags['row2'][5];
        //$bbc_tags['row2'][5] = elk_array_insert($where, 'html5video', array('html5audio'), 'after', false);
        //echo '<pre>', print_r( $bbc_tags['row2'], 1), '</pre>';
        // $bbc_tags['row2'][0] = elk_array_insert($where, 'html5video', array('html5audio'), 'after', false);

        // Add the javascript, this tells the editor what to do with the new button
        loadJavascriptFile('HTML5VideoAudioBBCButton.js', [], 'HTML5VideoAudioBBCButton');

        // We need to supply the css for the button image, here we use a data-url to save an image call
        $context['html_headers'] .= '<style>.sceditor-button-html5video div {background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEwAACxMBAJqcGAAAAi9JREFUOI2Fk81LVFEYxn/nzL0z44yO+FEhGmYU2CIsoY2I0SxCV1EbVy1qoUEboXQrbUOYQBCb/0EECSRI0XJpIWMfGxsMGRtDLcdm7vV+nNOivDnS5LM673ne93fOc+AIjml4capTwKASJKWSrQBI1jV6XksxOdZ9e+VovzhcjC7PxKyiP47g/nFombRKu6Y3lOrqtwLA6PJMrFj0X4ZDsrujsZmacJRPu3m2Svv/ZqBfe4bbm+rqtwwAq+iPhwTdd9uv0d7QBMDN1kukM0v8cCw6TrXw3S6xup1DAwLRY3hmCngghhenOoWQb5viCYY6k2UnreY3aK1rJBGpAuDFWoY3X7OBryRXpYDBSnGjISMYBriQaCjzhc+AVIIkQL5Y4P3ml8D0lM+rzx/Ys4rB3sf8RjlAq6QYWZh2kJgA56TJxVgdVZEom4UdiBpo26W5ppGfdomsu8+6e/A3gsIxDou++tO0VVcHZkv8THme6gRNBxHOOjbnq+LMbm+Rtw+QSNYBDK0qPUWg2kiEyzW1xA0DQwiEJCs1eh7g3dY39mz7RMgxzUktxSRALiwpuM5/uzcKezzLrDC7tsau56KRaQEwsjj1HCEHmh1FvWlWBOy4Dpvh0O9C64mn1+88NABc0xsyPLM9F5Y9OfzKV/gzrLVaiKnCIwAJkOrqtzzD7UWr9ImptZ6IqULfkxv3bDjyGw/1eGn6ivAZEFolFbINQEiywJxGpsd6bmWO9v8Cie7TtzGJXbAAAAAASUVORK5CYII=)}</style>';
        $context['html_headers'] .= '<style>.sceditor-button-html5audio div {background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEwAACxMBAJqcGAAAAgFJREFUOI2Fk89LVFEUxz/nzHvO+GxyZHJhDNgUEhGEiatBjIaEqU3UxnUtxkUboayltA1hFkLo7PoDhsBdCyXFVRiFqxYhk+WilERzeNqbd2+LyWl+iJ3VvYfv53vOuYcrtMTUcmlIYMIIWTXaD4BSttglqzI3M3LvY6Nejg/TawueXwlnER62mjaFNcXArU4WMuN+3WB6bcGrVMI3KoycCh97YFeqTpArZMZ9BfAr4expsKsRUvEeXI38bVtGnapbAJCp5dKQiL5vBLqjnQz2pujz4pzvStDbFUdFmF9fZWNvp64zynVHYKK14lBPH7n01aZcEIZtnUlI3jFCVtuGtKx/K/N1d5ut/V22DvZIdp0hejbebGBN1lGj/bQ5wKtP73A6XBABz8WJxdo0Bk2fgIIgONGOGgyoCBe6kydJUZRyY+JS4hzDqXT93hPzeDY8xp2Ba+2FlA3HYpcEGQAYS13mVvoKQVitiy56CRIxjyAM2fYPWj0WHasyJ6a2iaRbm3Pty+e64sOPTYx/xOb+T365tom2aFEAni6X5hHNy+FvkpEY380Rkah74sz/aPvyxY37jxQgcKuTFrtiYx3suOa/sLXmbafZewzUFljIjPtVJ8hhTfH0srXKntm//fzmg0No+I3H8WT19aCE5MWarEHTUHttYNGixZnRu+uN+j/gNKlVpmHQxwAAAABJRU5ErkJggg==)}</style>';
    }

    static public function getHtml5MediaType($data, $av)
    {
        $ext = strrchr($data, '.');
        if (!$ext)
            return 'text/plain';

        if ('audio' === $av) {
            if ('.mp3' === $ext)
                return 'audio/mpeg';
            elseif ('.ogg' === $ext)
                return 'audio/ogg';
            elseif ('.wav' === $ext)
                return 'audio/wav';
            else
                return 'text/plain';
        } elseif ('video' === $av) {
            if ('.mp4' === $ext)
                return 'video/mp4';
            elseif ('.webm' === $ext)
                return 'video/webm';
            elseif ('.ogg' === $ext)
                return 'video/ogg';
            else
                return 'text/plain';
        } else {
            return 'text/plain';
        }

        /*
        $ext = strtolower(array_pop(explode('.', $filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
         */
    }
}
