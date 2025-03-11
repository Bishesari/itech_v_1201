<?php

use App\Models\User;
use App\Rules\NCode;
use App\Services\ParsGreenService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component
{
    public string $f_name_fa = '';
    public string $l_name_fa = '';
    public string $n_code = '';
    public string $mobile = '';
    public int $remainingTime = 0;

    protected function rules(): array
    {
        return [
        'f_name_fa' => ['required', 'string', 'min:2'],
        'l_name_fa' => ['required', 'string', 'min:2'],
        'n_code' => ['required', 'digits:10', new NCode, Rule::unique('profiles', 'n_code')],
        'mobile' => ['required', 'starts_with:09', 'digits:11']
        ];
    }
    #[Computed]
    public function remainingTimeProperty()
    {
        $lastSent = Cache::get('last_otp_sent_' . $this->mobile);
        return $lastSent ? max(0, 120 - now()->diffInSeconds($lastSent)) : 0;
    }

    public function sendOtp(): void
    {
        $this->validate();
        // محدود کردن تعداد درخواست‌ها با Cache
        $cacheKey = 'otp_attempts_' . $this->mobile;
        $attempts = Cache::get($cacheKey, 0);
        if ($attempts >= 3) {
            $this->errorMessage = 'تعداد درخواست‌های شما زیاد است. لطفاً بعداً امتحان کنید.';
            return;
        }

        // بررسی فاصله زمانی ارسال OTP
        if (Cache::has('last_otp_sent_' . $this->mobile)) {
            $this->errorMessage = 'لطفاً 120 ثانیه صبر کنید.';
            return;
        }
        // تولید OTP و ذخیره در Cache
        $otp = numericOtp(6);
        Cache::put('otp_' . $this->mobile, $otp, now()->addMinutes(2)); // اعتبار 2 دقیقه
        Cache::put('last_otp_sent_' . $this->mobile, now());
        Cache::put($cacheKey, $attempts + 1, now()->addMinutes(10));  // افزایش تعداد درخواست‌های OTP
        // ارسال پیامک
        $smsService = new ParsGreenService();
        try {
            if (!$smsService->sendOtp($this->mobile, $otp)) {
                $this->errorMessage = 'خطایی در ارسال پیامک رخ داده است.';
                $this->remainingTime = 120;
                return;
            }
        } catch (\Exception $e) {
            $this->errorMessage = 'مشکلی در ارتباط با سرویس پیامک رخ داد.';
            return;
        }
        $this->step = 2; // تغییر مرحله به ورود OTP
        $this->errorMessage = ''; // پاک کردن پیام خطا
        $this->modal('confirm-user-deletion')->show();
    }


//    public function register(): void
//    {
//        $validated = $this->validate([
//            'f_name_fa' => ['required', 'string', 'min:2', 'max:255'],
//            'l_name_fa' => ['required', 'string', 'min:2', 'max:255'],
//            'n_code' => ['required', 'string', 'max:10', 'unique:' . User::class],
//        ]);
//
//        $validated['password'] = Hash::make($validated['password']);
//
//        event(new Registered(($user = User::create($validated))));
//
//        Auth::login($user);
//
//        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
//    }

//    public function sendOtp(): void
//    {
//        $this->validate([
//            'mobile' => ['required', 'regex:/^09[0-9]{9}$/']
//        ]);
//        $this->modal('confirm-user-deletion')->show();
//    }
}; ?>

<div class="flex flex-col gap-6">

    <div class="flex flex-col gap-6" x-data="{ remainingTime: @entangle('remainingTimeProperty') }" x-init="
        if (remainingTime > 0) {
            let timer = setInterval(() => {
                if (remainingTime > 0) {
                    remainingTime--;
                } else {
                    clearInterval(timer);
                }
            }, 1000);
        }
    ">
        <p class="text-green-200">
            زمان ارسال مجدد:
            <span x-text="remainingTime > 0 ? 'لطفاً ' + remainingTime + ' ثانیه صبر کنید...' : 'اکنون می‌توانید مجدداً کد درخواست کنید.'"></span>
        </p>



    <x-auth-header :title="__('فرم ثبت نام')" :description="__('اطلاعات ثبت نام را وارد کنید.')"/>
    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')"/>

    <form wire:submit="sendOtp" class="flex flex-col gap-6">

        <flux:input wire:model="f_name_fa" :label="__('نام:')" type="text" autofocus autocomplete="off"
                    :placeholder="__('نام فارسی')"
        />
        <flux:input wire:model="l_name_fa" :label="__('نام خانوادگی:')" type="text" autocomplete="off"
                    :placeholder="__('نام خانوادگی فارسی')"
        />
        <flux:input style="direction:ltr" wire:model="n_code" :label="__('کدملی:')" type="text" autocomplete="off"
                    :placeholder="__('کدملی')"
        />
        <flux:input style="direction:ltr" wire:model="mobile" :label="__('موبایل:')" type="text" autocomplete="off"
                    :placeholder="__('موبایل')"
        />
        <flux:button variant="primary" type="submit">{{ __('بررسی اطلاعات') }}</flux:button>

    </form>

    <flux:modal name="confirm-otp" :show="$errors->isNotEmpty()" focusable class="max-w-sm"
                :dismissible="false">
    </flux:modal>


    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('قبلا ثبت نام کرده اید؟') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('وارد شوید') }}</flux:link>
    </div>
</div>
