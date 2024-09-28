@use(Carbon\Carbon)
@use(App\MealTypeEnum)
<div class="container mx-auto">
    <section class="max-w-2xl mx-auto mt-6 mb-7">
        <h1 class="font-sans font-medium text-xl mb-3"><span class="font-semibold">Aujourd'hui,</span> {{Carbon::now()->translatedFormat('l d F')}}.</h1>
        <h2 class="font-sans uppercase text-md mb-1">Plat</h2>
        <x-recipe-card :recipe="$main" />
    </section>
    <div class="my-12">
        <div class=" mx-auto w-3 h-3 bg-neutral-950 rounded-full"></div>
    </div>
    <section class="max-w-xl mx-auto mt-6 mb-7">
        <h2 class="font-sans uppercase text-md mb-1">Entrée</h2>
        <x-recipe-card :recipe="$starter" />
    </section>
    <div class="my-12">
        <div class=" mx-auto w-3 h-3 bg-neutral-950 rounded-full"></div>
    </div>
    <section class="max-w-xl mx-auto mt-6 mb-7">
        <h2 class="font-sans uppercase text-md mb-1">Dessert</h2>
        <x-recipe-card :recipe="$dessert" />
    </section>
</div>
