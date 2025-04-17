@props(['name', 'value' => null])

@push('scripts')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
@endpush

<div x-data="{
    value: '{{ $value}}',
    init() {
        let quill = new Quill(this.$refs.quill, { theme: 'snow' });
        quill.root.innerHTML = this.value;
        quill.on('text-change', () => this.value = quill.root.innerHTML);
    },
}">
    <input type="text" name="{{ $name }}" x-model="value" class="bg-black" />
    <div x-ref="quill"></div>
</div>

{{--<textarea @disabled($disabled) {{ $attributes->merge([
    'class' =>
     'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm'
]) }}>{{ $slot }}</textarea> --}}
