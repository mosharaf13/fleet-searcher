<?php

namespace App\Traits;

trait ManipulatesDom
{
    private function changeSrcAttributes($elementTagName, $dom, $currentUrl)
    {
        foreach ($dom->getElementsByTagName($elementTagName) as $img) {
            $src = $img->getAttribute('src');
            if (!filter_var($src, FILTER_VALIDATE_URL) && !str_starts_with($src, '//')) {
                $absoluteUrl = $currentUrl . $src;
                $img->setAttribute('src', $absoluteUrl);
            }
        }
    }

    private function changeSrcOfImgs($dom, $currentUrl)
    {
        // Loop through all <img> tags and convert their src attributes to absolute URLs
        foreach ($dom->getElementsByTagName('img') as $img) {
            $src = $img->getAttribute('src');
            if (str_ends_with($src, '.webp')) {
                $absoluteUrl = $currentUrl . $src;
                $img->setAttribute('src', $absoluteUrl);
            }

            if (!str_starts_with($src, 'data:') && !filter_var($src, FILTER_VALIDATE_URL) && !str_starts_with($src, '//')) {
                $absoluteUrl = $currentUrl . $src;
                $img->setAttribute('src', $absoluteUrl);
            }
        }
    }


    private function changeHrefOfLinks($dom, $currentUrl)
    {
        // Loop through all <a> tags and convert their href attributes to absolute URLs
        foreach ($dom->getElementsByTagName('a') as $a) {
            $href = $a->getAttribute('href');
            if (str_starts_with($href, 'xjs/') ||
                (!filter_var($href, FILTER_VALIDATE_URL) &&
                    !str_starts_with($href, '//'))) {

                $absoluteUrl = $currentUrl . $href;
                $a->setAttribute('href', $absoluteUrl);
            }
        }
    }
}
