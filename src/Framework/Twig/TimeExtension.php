<?php
namespace App\Framework\Twig;

use DateTime;

class TimeExtension extends \Twig_Extension
{

    /**
     * @return \Twig_SimpleFilter[]
     */
    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter('timeago', [$this, 'timeago'], ['is_safe' => ['html']])
        ];
    }

    public function timeago(DateTime $date, string $format = 'd/m/Y H:i')
    {
        return $result = '<span class="timeago" datetime="' .
            $date->format(DateTime::ISO8601) . '">' .
            $date->format($format).'</span>';
    }
}
