@props(['section' => null, 'courses' => collect()])

<div class="space-y-4">
    <div>
        <x-forms.label for="name">Nome</x-forms.label>
        <x-forms.input id="name" name="name" type="text" :value="old('name', optional($section)->name)" required />
    </div>

    <div>
        <x-forms.label for="course_id">Curso</x-forms.label>
        <x-forms.select id="course_id" name="course_id" required>
            <option value="">Selecione um curso</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id', optional($section)->course_id) == $course->id)>
                    {{ $course->name }}
                </option>
            @endforeach
        </x-forms.select>
    </div>

    @if($errors->any())
        <ul class="text-sm text-red-600">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</div>

