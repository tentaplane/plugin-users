@extends('tentapress-admin::layouts.shell')

@section('title', $mode === 'create' ? 'Add New Role' : 'Edit Role')

@section('content')
    <div class="tp-page-header">
        <div>
            <h1 class="tp-page-title">{{ $mode === 'create' ? 'Add New Role' : 'Edit Role' }}</h1>
            <p class="tp-description">Tick capabilities to grant access.</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('tp.roles.index') }}" class="tp-button-secondary">Back</a>
        </div>
    </div>

    @php
        $selected = is_array($selected ?? null) ? $selected : [];
    @endphp

    <div class="tp-metabox">
        <div class="tp-metabox__body">
            <form
                method="POST"
                action="{{ $mode === 'create' ? route('tp.roles.store') : route('tp.roles.update', ['role' => $role->id]) }}"
                class="space-y-5">
                @csrf
                @if ($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                    <div class="tp-field">
                        <label class="tp-label">Name</label>
                        <input name="name" class="tp-input" value="{{ old('name', $role?->name) }}" required />
                    </div>

                    <div class="tp-field">
                        <label class="tp-label">Slug</label>
                        <input
                            name="slug"
                            class="tp-input"
                            value="{{ old('slug', $role?->slug) }}"
                            placeholder="administrator"
                            required />
                        <div class="tp-help">Lowercase, numbers and dashes only.</div>
                    </div>
                </div>

                <div class="tp-divider"></div>

                <div>
                    <div class="tp-label mb-2">Capabilities</div>

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        @foreach ($capabilities as $cap)
                            @php
                                $key = (string) $cap->key;
                                $label = (string) $cap->label;
                                $group = (string) ($cap->group ?? '');
                                $desc = (string) ($cap->description ?? '');
                                $isChecked = in_array($key, $selected, true) || in_array($key, (array) old('capabilities', []), true);
                            @endphp

                            <label class="tp-panel flex cursor-pointer items-start gap-3">
                                <input
                                    type="checkbox"
                                    class="tp-checkbox mt-1"
                                    name="capabilities[]"
                                    value="{{ $key }}"
                                    @checked($isChecked) />
                                <span class="block">
                                    <span class="block text-sm font-semibold">{{ $label }}</span>
                                    <span class="tp-muted mt-1 block text-xs">
                                        <code class="tp-code">{{ $key }}</code>
                                        @if ($group !== '')
                                            <span class="mx-1">·</span>
                                            {{ $group }}
                                        @endif
                                    </span>
                                    @if ($desc !== '')
                                        <span class="tp-muted mt-1 block text-xs">{{ $desc }}</span>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="tp-button-primary">
                        {{ $mode === 'create' ? 'Create Role' : 'Save Changes' }}
                    </button>
                    <a href="{{ route('tp.roles.index') }}" class="tp-button-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
