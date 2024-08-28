<?php

use Illuminate\Support\Facades\Route;
use Cruzmediaorg\InertiaPageBlockBuilder\Http\Controllers\PageBuilderController;

Route::get('/', [PageBuilderController::class, 'index'])->name('ipbb.page-builder');