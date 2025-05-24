<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Редактировать бюджет
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
        .form-textarea,
        .form-select {
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
        .form-textarea:focus,
         .form-select:focus {
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
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('budgets.update', $budget->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="year" class="form-label">Год</label>
                        <input type="number" name="year" id="year" class="form-input" value="{{ old('year', $budget->year) }}" required>
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="month" class="form-label">Месяц (опционально)</label>
                        <input type="number" name="month" id="month" class="form-input" value="{{ old('month', $budget->month) }}" min="1" max="12">
                        @error('month')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="amount" class="form-label">Плановый бюджет (₽)</label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-input" value="{{ old('amount', $budget->amount) }}" required>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="comment" class="form-label">Комментарий</label>
                        <input type="text" name="comment" id="comment" class="form-input" value="{{ old('comment', $budget->comment) }}">
                        @error('comment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-actions">
                        <a href="{{ route('budgets.index') }}" class="btn btn-secondary mr-2">Отмена</a>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 