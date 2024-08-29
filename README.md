
# Inertia Page Block Builder (WIP) ⚠️ 

Inertia Page Block Builder is a lightweight drag-and-drop page block builder. This package provides a fully customizable visual page builder for developers, allowing them to create custom blocks and options. 

I started this project  because I often need to build custom landing pages without too much configuration or database structure. This helps me adding flexibility by just building "Customizable blocks" and "Options", and build the website using a UI Drag and Drop tool.

Built with Laravel 11, Inertia.js, Vue 3.

## Compatibility
- PHP ^8.0
- Vue 3
- Inertia.js 1.0
- Laravel ^10
- 
## Usage/Examples

#### Creating a New Block:
```cli
php artisan make:block ExampleBlock
```
#### This creates a new ExampleBlock.php block class:
```php
<?php

namespace App\IPBB;

use Cruzmediaorg\InertiaPageBlockBuilder\Block;
use Cruzmediaorg\InertiaPageBlockBuilder\Options\Input;
use Cruzmediaorg\InertiaPageBlockBuilder\Options\Textarea;

class ExampleBlock extends Block
{
    public string $name = 'Example';

    public static string $reference = 'ipbb.example';

    public array $data = [
        'title' => 'A default title',
        'content' => 'Default content',
        'backgroundColor' => '#000000',
    ];

    public function options(): array
    {
        return [
            Input::make('Title', 'title'),
            Textarea::make('Content', 'content'),
            Input::make('Background Color', 'backgroundColor', ['type' => 'color']),
        ];
    }

    public function render(): string
    {
        return 'Example/Render';
    }
}
```

#### And a Example/Render.vue file
```vue
<template>
    <header :style="{ backgroundColor: backgroundColor }" class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl md:text-6xl">
                {{ title }}
            </h1>
            <h2 v-if="content" class="mt-3 text-xl text-gray-300 sm:text-2xl md:text-3xl">
                {{ content }}
            </h2>
        </div>
    </header>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    content: {
        type: String,
        default: '',
    },
    name: {
        type: String,
        required: true,
    },
    backgroundColor: {
        type: String,
        default: '#000000',
    },
});
</script>
```

#### Then, we need to register the block in the IPBBServiceProvider:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Cruzmediaorg\InertiaPageBlockBuilder\BlockManager;
use App\IPBB\ExampleBlock;
use App\IPBB\MegaCustomizableHeaderBlock;

class IPBBServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->afterResolving(BlockManager::class, function (BlockManager $blockManager) {
            // Register your blocks here
            $blockManager->registerBlock(ExampleBlock::class);
            $blockManager->registerBlock(MegaCustomizableHeaderBlock::class);
        });
    }
}
```
That's it, we got our block registered, now, in the frontend:

#### Using the Page Builder in Vue 3:
```vue
<template>
            <PageBuilderComponent :registeredBlocks="blocks" :data="page.content" @save="saveBlocks" />
</template>

<script setup>
import PageBuilderComponent from '@/../../packages/cruzmediaorg/inertia-page-block-builder/resources/js/components/PageBuilder.vue';
import { router } from '@inertiajs/vue3';
const props = defineProps(['blocks', 'page']);

const saveBlocks = (blocks) => {
    router.put(route('pages.update', props.page.id), {
        content: JSON.parse(blocks)
    });
}
</script>
```

#### Rendering the page:
```vue
<template>
    <PageBlockRenderer :blocks="page.content" />
</template>

<script setup>
import PageBlockRenderer from '@/../../packages/cruzmediaorg/inertia-page-block-builder/resources/js/components/PageBlockRenderer.vue';
const props = defineProps(['page']);
</script>
```


## Screenshots

<img width="1713" alt="image" src="https://github.com/user-attachments/assets/74aa0af1-a3cd-43bb-bcc9-61cd9d7c8b81">



## License

[MIT](https://choosealicense.com/licenses/mit/)


## Authors

- [@cruzmediaorg](https://www.github.com/cruzmediaorg)

