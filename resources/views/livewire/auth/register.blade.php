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

    public $a = 5;
    public function send()
    {
        $this->a = 1;
    }
}; ?>

<div class="flex flex-col gap-6">
    {{$a}}
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


    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" wire:click="send">
            {{ __('Delete account') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Are you sure you want to delete your account?') }}</flux:heading>

                <flux:subheading>
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="password" :label="__('Password')" type="password" />

            <div class="flex justify-end space-x-2">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit">{{ __('Delete account') }}</flux:button>
            </div>
        </form>
    </flux:modal>




    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('قبلا ثبت نام کرده اید؟') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('وارد شوید') }}</flux:link>
    </div>
</div>
