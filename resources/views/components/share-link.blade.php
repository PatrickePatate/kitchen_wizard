@props(['url', 'suffix' => ''])
<div x-data="{ link: @js(__('Chargement...')), copied: false, async copy() { try { await navigator.clipboard.writeText(this.link + @js($suffix)); this.copied = true; setTimeout(() => this.copied = false, 1500) } catch (e) {} } }"
     x-init="$watch('modalOpen', async (open) => { if (open && !link.startsWith('http')) { try { link = (await (await fetch(@js($url))).json()).url } catch (e) { link = @js(__('Erreur de chargement')) } } })">
    {{ $slot }}
    <div class="relative mt-2 bg-gray-100 p-2 rounded-md text-sm font-mono flex items-center gap-2">
        <div class="grow text-wrap break-all"><span x-text="link + (link.startsWith('http') ? @js($suffix) : '')"></span></div>
        <button type="button" @click="copy()" class="shrink-0 relative p-1" aria-label="{{ __('Copy link') }}">
            <x-tabler-clipboard x-show="!copied" class="text-gray-800" />
            <x-tabler-check x-show="copied" x-cloak class="text-green-600" />
        </button>
        <span class="sr-only" aria-live="polite" x-text="copied ? @js(__('Copié !')) : ''"></span>
    </div>
</div>
