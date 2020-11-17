<?php

return [
    "prefix" => 'atrochar',
    "iframe" => 'i',
    "iconsPath" => "media/svg/icons",
    "iconsAllowedExtension" => ['svg'],
    "defaultTheme" => [
        "linkTag" => "a",
        "class" => "",
        "activeClass" => "",
        "listStartTag" => "<ul>",
        "listEndTag" => "</ul>",
        "itemStartTag" => "<li>",
        "itemEndTag" => "</li>",
        "submenusOptions" => [
            "linkTag" => "a",
            "class" => "",
            "activeClass" => "",
            "listStartTag" => "<div>",
            "listEndTag" => "</div>",
            "itemStartTag" => "",
            "itemEndTag" => "",
        ]
    ],
    "themes" => [
        "jetstream" => [
            "linkTag" => "a",
            "class" =>       "inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out",
            "activeClass" => "inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out",
            "listStartTag" => "",
            "listEndTag" => "",
            "itemStartTag" => "",
            "itemEndTag" => "",
        ]
    ]
];
