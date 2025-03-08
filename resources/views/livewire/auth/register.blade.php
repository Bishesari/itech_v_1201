<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $f_name_fa = '';
    public string $l_name_fa = '';
    public string $n_code = '';
    public string $mobile = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'f_name_fa' => ['required', 'string', 'min:2', 'max:255'],
            'l_name_fa' => ['required', 'string', 'min:2', 'max:255'],
            'n_code' => ['required', 'string', 'max:10', 'unique:' . User::class],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('فرم ثبت نام')" :description="__('اطلاعات خواسته شده را جهت ثبت نام وارد کنید.')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- First Name Fa-->
        <flux:input
            wire:model="f_name_fa"
            :label="__('نام:')"
            type="text"
            required
            autofocus
            autocomplete="off"
            :placeholder="__('نام فارسی')"
        />

        <!-- Last Name Fa-->
        <flux:input
            wire:model="l_name_fa"
            :label="__('نام خانوادگی:')"
            type="text"
            required
            autocomplete="off"
            :placeholder="__('نام خانوادگی فارسی')"
        />

        <!-- National Code -->
        <flux:input style="direction:ltr"
            wire:model="n_code"
            :label="__('کدملی:')"
            type="text"
            required
            autocomplete="off"
            :placeholder="__('کدملی')"
        />

        <!-- Mobile Phone -->
        <flux:input style="direction:ltr"
                    wire:model="mobile"
                    :label="__('موبایل:')"
                    type="text"
                    required
                    autocomplete="off"
                    :placeholder="__('موبایل')"
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('ایجاد حساب') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Already have an account?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
