<?php

class HTML5VideoAudioBBC
{
    static public function integrate_load_theme()
    {
        loadCSSFile('html5videoaudiobbc.css');
    }

    // hook integrate_additional_bbc /sources/subs/BBC/ParserWrapper.php
    static public function integrate_additional_bbc(&$additional_bbc)
    {
        global $modSettings;

        loadLanguage('HTML5VideoAudioBBC');

        if (empty($modSettings['enableBBC'])) {
            return;
        }

        $additional_bbc[] = [
            BBC\Codes::ATTR_TAG => 'html5audio',
            BBC\Codes::ATTR_TYPE => BBC\Codes::TYPE_UNPARSED_CONTENT,
            BBC\Codes::ATTR_CONTENT => '$1',
            BBC\Codes::ATTR_VALIDATE => function(&$tag, &$data, $disabled) {
                global $txt;

                $link = $data;

                if (in_array('html5audio', $disabled)) {
                    $data = '[html5audio]' . $data . '[/html5audio]';
                } elseif (empty($data)) {
                    $data = '[html5audio]' . $data . '[/html5audio]';
                } elseif (!in_array(strrchr($data, '.'), ['.mp3', '.ogg', '.wav'])) {
                    $data = '[html5audio]' . $data . '[/html5audio]';
                } else {
                    $type = HTML5VideoAudioBBC::getHtml5MediaType($data, 'audio');
                    $data = 'src="' . $data . '" type="' . $type . '"';
                    $data = '
<audio controls>
    <source ' . $data . '>
    ' . sprintf($txt['html5audiobbc_not_support'], $link) . '<br>
</audio>';
                }
            },
            // BBC\Codes::ATTR_DISABLED_CONTENT => '($1)',
            BBC\Codes::ATTR_BLOCK_LEVEL => false,
            BBC\Codes::ATTR_AUTOLINK => false,
            BBC\Codes::ATTR_LENGTH => 10,
        ];

        $additional_bbc[] = [
            BBC\Codes::ATTR_TAG => 'html5video',
            BBC\Codes::ATTR_TYPE => BBC\Codes::TYPE_UNPARSED_CONTENT,
            BBC\Codes::ATTR_CONTENT => '$1',
            BBC\Codes::ATTR_VALIDATE => function(&$tag, &$data, $disabled) {
                global $txt;

                $link = $data;

                if (in_array('html5video', $disabled)) {
                    $data = '[html5video]' . $data . '[/html5video]';
                } elseif (empty($data)) {
                    $data = '[html5video]' . $data . '[/html5video]';
                } elseif (!in_array(strrchr($data, '.'), ['.mp4', '.webm', '.ogg', '.ogv'])) {
                    $data = '[html5video]' . $data . '[/html5video]';
                } else {
                    $type = HTML5VideoAudioBBC::getHtml5MediaType($data, 'video');
                    $data = 'src="' . $data . '" type="' . $type . '"';
                    $data = '
<video class="elk-html5videoaudiobbc" controls>
    <source ' . $data . '>
    ' . sprintf($txt['html5videobbc_not_support'], $link) . '<br>
</video>';
                }
            },
            // BBC\Codes::ATTR_DISABLED_CONTENT => '($1)',
            BBC\Codes::ATTR_BLOCK_LEVEL => false,
            BBC\Codes::ATTR_AUTOLINK => false,
            BBC\Codes::ATTR_LENGTH => 10,
        ];
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

        loadJavascriptFile('html5videoaudiobbc.js');
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
            elseif ('.ogv' === $ext)
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
