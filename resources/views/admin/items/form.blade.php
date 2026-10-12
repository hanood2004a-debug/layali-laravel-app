@extends('admin.layout')
@section('title', $item->exists ? 'تعديل صنف' : 'إضافة صنف')
@section('content')
<h1 class="h4 mb-3">{{ $item->exists ? 'تعديل: ' . $item->name : 'إضافة صنف جديد' }}</h1>
<form method="POST" enctype="multipart/form-data" class="card stat"
      action="{{ $item->exists ? route('admin.items.update', $item) : route('admin.items.store') }}">
    <div class="card-body row g-3">
        @csrf
        @if ($item->exists) @method('PUT') @endif
        <div class="col-md-6">
            <label class="form-label" for="name">اسم الصنف</label>
            <input class="form-control" id="name" name="name" value="{{ old('name', $item->name) }}" required maxlength="120">
        </div>
        <div class="col-md-3">
            <label class="form-label" for="category_id">القسم</label>
            <select class="form-select" id="category_id" name="category_id" required>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" @selected(old('category_id', $item->category_id) == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="price">السعر (₪)</label>
            <input class="form-control" id="price" name="price" type="number" step="0.5" min="0" value="{{ old('price', $item->exists ? $item->price + 0 : '') }}" required>
        </div>
        <div class="col-12">
            <label class="form-label" for="description">الوصف</label>
            <input class="form-control" id="description" name="description" value="{{ old('description', $item->description) }}" maxlength="255">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="image">الصورة (JPG / PNG / WEBP حتى 3MB)</label>
            <input class="form-control" id="image" name="image" type="file" accept="image/*">
            @if ($item->exists)<img class="thumb mt-2" src="{{ $item->image_url }}" alt="">@endif
        </div>
        <div class="col-md-3">
            <label class="form-label" for="sort">الترتيب</label>
            <input class="form-control" id="sort" name="sort" type="number" min="0" value="{{ old('sort', $item->sort ?? 0) }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <div class="form-check">
                <input type="hidden" name="active" value="0">
                <input class="form-check-input" type="checkbox" id="active" name="active" value="1" @checked(old('active', $item->active))>
                <label class="form-check-label" for="active">ظاهر في الموقع</label>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label" for="details">شرح الوجبة (يظهر عند الضغط على الصنف)</label>
            <textarea class="form-control" id="details" name="details" rows="3" maxlength="1000">{{ old('details', $item->details) }}</textarea>
        </div>
        <div class="col-12">
            <label class="form-label" for="ingredients">المكوّنات الأساسية (افصل بينها بفاصلة ،)</label>
            <input class="form-control" id="ingredients" name="ingredients" value="{{ old('ingredients', $item->ingredients) }}" maxlength="300">
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-dark">حفظ</button>
            <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary">إلغاء</a>
        </div>
    </div>
</form>
@endsection
