<?php
define("APPS",__DIR__.'/apps'       );
define("URLS",__DIR__.'/urls.php'   );
include __DIR__.'/.system/system.php';
use phpr\page\display as display;

display\RunAll('home');
display\Display(false,__DIR__.'/.system/vendor/phpr/default_page/error.html');
