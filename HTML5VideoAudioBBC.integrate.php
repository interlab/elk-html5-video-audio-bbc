<?php

class HTML5VideoAudioBBC
{
    static public function integrate_load_theme()
    {
        loadCSSFile('html5videoaudiobbc.css');
    }

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
                elseif (!in_array(strrchr($data, "."), [\'.mp3\', \'.ogg\']))
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
                elseif (!in_array(strrchr($data, \'.\'), [\'.mp4\', \'.webm\', \'.ogg\']))
                    $data = \'[html5video]\' . $data . \'[/html5video]\';
                else {
                    $type = HTML5VideoAudioBBC::getHtml5MediaType($data, \'video\');
                    $data = \'src="\' . $data . \'" type="\' . $type . \'"\';
                }

                $data = \'
<video class="elk-html5videoaudiobbc" controls>
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
