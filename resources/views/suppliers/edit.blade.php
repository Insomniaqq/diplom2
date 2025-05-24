<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Редактировать поставщика') }}
        </h2>
    </x-slot>

    <style>
        /* Basic form styling */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-group:last-of-type {
            margin-bottom: 0.5rem;
        }

        .form-label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #4a5568; /* gray-700 */
            font-size: 0.875rem; /* sm */
        }

        .form-input,
        .form-textarea {
            display: block;
            width: 100%;
            max-width: 400px; /* Added max-width */
            padding: 0.5rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-input:focus,
        .form-textarea:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-textarea {
            min-height: 80px;
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            color: #fff;
            background-color: #0056b3;
            border-color: #0056b3;
        }

         .btn-secondary {
             color: #fff;
             background-color: #6c757d;
             border-color: #6c757d;
         }

         .btn-secondary:hover {
             color: #fff;
             background-color: #545b62;
             border-color: #545b62;
         }

        .form-actions {
            display: flex;
            justify-content: flex-start;
            margin-top: 1rem;
        }

        .mr-2 {
            margin-right: 0.5rem;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="inn" class="form-label">ИНН</label>
                            <input type="text" name="inn" id="inn" class="form-input" value="{{ old('inn', $supplier->inn) }}" required>
                            @error('inn')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name" class="form-label">Наименование</label>
                            <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $supplier->name) }}" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="contact_person" class="form-label">Контактное лицо</label>
                            <input type="text" name="contact_person" id="contact_person" class="form-input" value="{{ old('contact_person', $supplier->contact_person) }}" required>
                            @error('contact_person')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">Телефон</label>
                            <input type="text" name="phone" id="phone" class="form-input" value="{{ old('phone', $supplier->phone) }}" required>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-input" value="{{ old('email', $supplier->email) }}" required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label">Адрес</label>
                            <textarea name="address" id="address" rows="3" class="form-textarea" required>{{ old('address', $supplier->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="additional_info" class="form-label">Дополнительная информация</label>
                            <textarea name="additional_info" id="additional_info" rows="3" class="form-textarea">{{ old('additional_info', $supplier->additional_info) }}</textarea>
                            @error('additional_info')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary mr-2">Отмена</a>
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 