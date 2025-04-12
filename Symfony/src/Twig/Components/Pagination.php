<?php

namespace App\Twig\Components;

use Pagerfanta\Pagerfanta;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('pagination')]
final class Pagination
{
    public Pagerfanta $pager;
    public string $template = 'twitter_bootstrap5';
    public string $prevText = 'Previous';
    public string $nextText = 'Next';
    public string $cssClass = 'pagination';
    public string $alignment = 'center';

    public array $perPageOptions = [1, 2, 3, 5, 10];
    public string $perPageParam = 'perPage';
    public string $pageParam = 'page';
}
