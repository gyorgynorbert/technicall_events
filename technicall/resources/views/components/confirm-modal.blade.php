<div
    x-data="{
        show: false,
        title: '',
        message: '',
        formId: ''
    }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('overflow-hidden');
        } else {
            document.body.classList.remove('overflow-hidden');
        }
    })"
    x-on:open-confirm-modal.window="
        show = true;
        title = $event.detail.title;
        message = $event.detail.message;
        formId = $event.detail.formId;
    "
    x-on:keydown.escape.window="show = false"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    ...
    style="display: none;"
>
    <div x-on:click="show = false" class="fixed inset-0 bg-black/70 backdrop-blur-sm"></div>

    <div class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="title"></h3>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400" x-text="message"></p>

        <div class="mt-6 flex justify-end space-x-3">
            <x-secondary-button x-on:click="show = false">
                Cancel
            </x-secondary-button>
            
            <x-primary-button @click="document.getElementById(formId).submit(); show = false;">
                Confirm
            </x-primary-button>
        </div>
    </div>
</div>