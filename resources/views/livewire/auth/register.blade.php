<?php

use App\Services\ParsGreen;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {


}; ?>


<div>
    <p style="direction: ltr" class="text-green-200">Ip Attempts: </p>
    <p style="direction: ltr" class="text-green-200">Nu Attempts: </p>


    <form wire:submit="sendOtp" class="flex flex-col gap-6">

        <flux:input style="direction:ltr" wire:model="mobile" :label="__('موبایل:')" type="text" autocomplete="off"
                    :placeholder="__('موبایل')"
        />
        <flux:button variant="primary" type="submit">{{ __('بررسی اطلاعات') }}</flux:button>
    </form>
</div>
