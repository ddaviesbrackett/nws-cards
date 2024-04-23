<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2 dark:text-white">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        <!-- Phone -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="phone" value="{{ __('Phone Number') }}" />
            <x-input id="phone" type="tel" class="mt-1 block w-full" wire:model="state.phone" />
            <x-input-error for="phone" class="mt-2" />
        </div>

        <!-- Address1 -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="address1" value="{{ __('Address 1') }}" />
            <x-input id="address1" type="text" class="mt-1 block w-full" wire:model="state.address1" />
            <x-input-error for="address1" class="mt-2" />
        </div>

        <!-- Address2 -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="address2" value="{{ __('Address 2') }}" />
            <x-input id="address2" type="text" class="mt-1 block w-full" wire:model="state.address2" />
            <x-input-error for="address2" class="mt-2" />
        </div>

        <!-- City -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="city" value="{{ __('City') }}" />
            <x-input id="city" type="text" class="mt-1 block w-full" wire:model="state.city" />
            <x-input-error for="city" class="mt-2" />
        </div>

        <!-- Postal Code -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="postal_code" value="{{ __('Postal Code') }}" />
            <x-input id="postal_code" type="text" class="mt-1 block w-full" wire:model="state.postal_code" />
            <x-input-error for="postal_code" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
