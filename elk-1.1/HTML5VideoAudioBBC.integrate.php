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
        loadLanguage('HTML5VideoAudioBBC');

        $additional_bbc[] = [
            BBC\Codes::ATTR_TAG => 'html5audio',
            BBC\Codes::ATTR_TYPE => BBC\Codes::TYPE_UNPARSED_CONTENT,
            BBC\Codes::ATTR_CONTENT => '$1',
            BBC\Codes::ATTR_VALIDATE => function(&$tag, &$data, $disabled) {
                if (in_array('html5audio', $disabled)) {
                    return;
                }

                global $txt;

                $link = $data;

                if (!empty($data) && in_array(strrchr($data, '.'), ['.mp3', '.ogg', '.wav'])) {
                    $type = HTML5VideoAudioBBC::getHtml5MediaType($data, 'audio');
                    $data = 'src="' . $data . '" type="' . $type . '"';
                    $data = '<audio controls><source ' . $data . '><p>' .
                        sprintf($txt['html5audiobbc_not_support'], $link) . '</p></audio>';
                }
            },
            BBC\Codes::ATTR_DISABLED_CONTENT => '[html5audio]$1[/html5audio]',
            BBC\Codes::ATTR_BLOCK_LEVEL => false,
            BBC\Codes::ATTR_AUTOLINK => false,
            BBC\Codes::ATTR_LENGTH => 10,
        ];

        $additional_bbc[] = [
            BBC\Codes::ATTR_TAG => 'html5video',
            BBC\Codes::ATTR_TYPE => BBC\Codes::TYPE_UNPARSED_CONTENT,
            BBC\Codes::ATTR_CONTENT => '$1',
            BBC\Codes::ATTR_VALIDATE => function(&$tag, &$data, $disabled) {
                if (in_array('html5video', $disabled)) {
                    return;
                }

                global $txt;

                $link = $data;

                if (!empty($data) && in_array(strrchr($data, '.'), ['.mp4', '.webm', '.ogg', '.ogv'])) {
                    $type = HTML5VideoAudioBBC::getHtml5MediaType($data, 'video');
                    $data = 'src="' . $data . '" type="' . $type . '"';
                    $data = '<video class="elk-html5videoaudiobbc" controls>'.
                        '<source ' . $data . '><p>' .
                        sprintf($txt['html5videobbc_not_support'], $link) . '</p></video>';
                }
            },
            BBC\Codes::ATTR_DISABLED_CONTENT => '[html5video]$1[/html5video]',
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
        $bbc_tags['row2'][3] = elk_array_insert($where, 'image', ['html5video'], 'after', false);

        $where = $bbc_tags['row2'][3];
        // And here we insert the new value after code
        $bbc_tags['row2'][3] = elk_array_insert($where, 'html5video', ['html5audio'], 'after', false);

        loadJavascriptFile('html5videoaudiobbc.js');
    }

    static public function getHtml5MediaType($data, $av)
    {
        $ext = strrchr($data, '.');
        if (!$ext) {
            return 'text/plain';
        }

        $ext = substr($ext, 1);

        $arr = [
            'audio' => ['mp3' => 'audio/mpeg', 'ogg' => 'audio/ogg', 'wav' => 'audio/wav'],
            'video' => [
                'mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg', 'ogv' => 'video/ogg',
             ]
        ];

        return isset($arr[$av][$ext]) ? $arr[$av][$ext] : 'text/plain';
    }
}
