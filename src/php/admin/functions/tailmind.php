<?php
function pageTitle($title = 'Başlık Yok', $subtitle = '')
{
    echo <<<HTML
    <section class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">{$title}</h2>
        <p class="text-gray-500 mt-1">{$subtitle}</p>
    </section>
    HTML;
}
?>