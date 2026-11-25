<section>
    <h2 class="font-semibold">Eliminar cuenta</h2>
    <p class="mt-1 text-sm text-ink-2">Al eliminar tu cuenta se borran tus datos de acceso de forma permanente.</p>

    <x-danger-button class="mt-5" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">Eliminar cuenta</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8">
            @csrf
            @method('delete')
            <h2 class="font-display text-xl font-semibold">¿Seguro que quieres eliminar tu cuenta?</h2>
            <p class="mt-2 text-sm text-ink-2">Escribe tu contraseña para confirmar. Esta acción no se puede deshacer.</p>
            <div class="mt-6">
                <x-input-label for="password" value="Contraseña" class="sr-only" />
                <x-text-input id="password" name="password" type="password" placeholder="Contraseña" />
                <x-input-error :messages="$errors->userDeletion->get('password')" />
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-danger-button>Eliminar cuenta</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
