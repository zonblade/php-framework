<?php

foreach (glob(__DIR__."/vendor/*/*.php") as $filename)
{
    include $filename;
}
foreach (glob(__DIR__."/vendor/*/*/*.php") as $filename)
{
    include $filename;
}