@extends('manage.layouts.workspace')
@section('title', isset($category) ? 'Edit category' : 'Add category')
@section('heading', isset($category) ? 'Edit category' : 'Add category')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/suweka-inventory.css') }}?v={{ filemtime(public_path('css/suweka-inventory.css')) }}">
@endpush

@section('content')
@php
    $action = isset($category)
        ? route('manage.inventory.categories.update', $category->id)
        : route('manage.inventory.categories.store');
    $initialImage = isset($category) && $category->image_path ? asset($category->image_path) : null;
@endphp

<div class="swic-form-page">
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="swic-form" x-data="{
        preview: @js($initialImage),
        name: '',
        handleFile(e) {
            const file = e.target.files[0];
            if (!file) { this.preview = @js($initialImage); this.name = ''; return; }
            this.name = file.name;
            const reader = new FileReader();
            reader.onload = ev => this.preview = ev.target.result;
            reader.readAsDataURL(file);
        }
    }">
        @csrf
        @if(isset($category))
            @method('PUT')
        @endif
        
        @if($errors->any())
            <div class="swi-error">{{ $errors->first() }}</div>
        @endif

        <div class="swic-image-upload">
            <template x-if="! preview">
                <div class="swic-image-placeholder">
                    <i class="fa-solid fa-shapes"></i>
                </div>
            </template>
            <template x-if="preview">
                <img :src="preview" alt="Preview" class="swic-image-preview">
            </template>

            <label class="swic-add-image-btn">
                <i class="fa-regular fa-image"></i>
                <span x-text="name || (preview ? 'Change image' : 'Add image')">Add image</span>
                <input type="file" name="image" accept="image/*" hidden @change="handleFile">
            </label>
        </div>

        <div class="swic-fields">
            <label class="swic-field">
                <span>Category name</span>
                <input name="name" required maxlength="100" value="{{ old('name', $category->name ?? '') }}">
            </label>

            <label class="swic-field">
                <span>Description (optional)</span>
                <textarea name="description" rows="1" maxlength="2000">{{ old('description', $category->description ?? '') }}</textarea>
            </label>

            <label class="swic-field swic-field--select">
                <span>Parent category (optional, makes this a subcategory)</span>
                <select name="parent_id">
                    <option value="">None</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id ?? '') == $parent->id)>{{ $parent->name }}</option>
                    @endforeach
                </select>
            </label>
        </div>

        <div class="swic-actions">
            <a href="{{ route('manage.workspace.inventory', ['page' => 'categories']) }}" class="swic-btn-cancel">Cancel</a>
            <button type="submit" class="swic-btn-save">Save</button>
        </div>
    </form>
</div>
@endsection