<?php

namespace Pracapl\ZnajdzPraceZPracapl;

class HtmlSanitizer
{
    public static function sanitizeHtml(string $html): string
    {
        $allowedTags = [
            'a' => [
                'class' => [],
                'href'  => [],
                'rel'   => [],
                'title' => [],
                'alt' => [],
                'target' => [],
                'id' => [],
                'style' => [],
            ],
            'abbr' => [
                'title' => [],
            ],
            'b' => [],
            'blockquote' => [
                'cite'  => [],
            ],
            'cite' => [
                'title' => [],
            ],
            'code' => [],
            'del' => [
                'datetime' => [],
                'title' => [],
            ],
            'dd' => [],
            'div' => [
                'class' => [],
                'title' => [],
                'style' => [],
                'id' => [],
            ],
            'dl' => [],
            'dt' => [],
            'em' => [],
            'h1' => [],
            'h2' => [],
            'h3' => [],
            'h4' => [],
            'h5' => [],
            'h6' => [],
            'i' => [],
            'img' => [
                'alt'    => [],
                'class'  => [],
                'height' => [],
                'src'    => [],
                'width'  => [],
            ],
            'li' => [
                'class' => [],
            ],
            'ol' => [
                'class' => [],
            ],
            'p' => [
                'class' => [],
                'style' => [],
                'id' => [],
            ],
            'q' => [
                'cite' => [],
                'title' => [],
            ],
            'span' => [
                'class' => [],
                'title' => [],
                'style' => [],
            ],
            'strike' => [],
            'strong' => [],
            'ul' => [
                'class' => [],
            ],
            'input' => [
                'type' => [],
                'name' => [],
                'id' => [],
                'value' => [],
                'checked' => [],
                'class' => [],
                'list' => [],
                'max' => [],
            ],
            'label' => [
                'for' => [],
            ],
            'form' => [
                'method' => [],
                'action' => [],
                'id' => [],
            ],
            'tbody' => [],
            'tr' => [],
            'th' => [],
            'td' => [],
            'table' => [
                'class' => [],
                'role' => [],
            ],
            'option' => [
                'value' => [],
            ],
            'datalist' => [
                'id' => []
            ],
        ];

        return wp_kses($html, $allowedTags);
    }
}