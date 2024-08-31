<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Cruzmediaorg\InertiaPageBlockBuilder\BlockManager;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{

    protected $blockManager;

    public function __construct(BlockManager $blockManager)
    {
        $this->blockManager = $blockManager;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page = Page::create([
            'content' => []
        ]);

        return redirect()->route('pages.edit', $page->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        return Inertia::render('Pages/Show', [
            'page' => $page,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        $registeredBlocks = $this->blockManager->getRegisteredBlocks();
        
        $blocks = array_values(array_map(function ($key, $block) {
            return [
                'name' => $block['name'],
                'options' => $block['options'],
                'render' => $block['render'],
                'data' => $block['data'],
                'icon' => $block['icon'],
            ];
        }, array_keys($registeredBlocks), $registeredBlocks));

        return Inertia::render('Pages/Edit', [
            'blocks' => $blocks,
            'page' => $page,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $page->update([
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Page updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        //
    }
}
