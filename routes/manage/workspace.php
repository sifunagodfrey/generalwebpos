<?php
use Illuminate\Support\Facades\Route;
foreach (['sale','dashboard','inventory','customers','suppliers','staff','cashbox','documents','reports','settings'] as $module) { require __DIR__.'/workspace/'.$module.'.php'; }
