<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = User::query();
        if (!empty($this->filters['role'])) {
            $query->where('role', $this->filters['role']);
        }
        if (!empty($this->filters['email'])) {
            $query->where('email', 'like', '%'.$this->filters['email'].'%');
        }
        if (!empty($this->filters['search'])) {
            $query->where(function($q) {
                $q->where('name', 'like', '%'.$this->filters['search'].'%')
                  ->orWhere('email', 'like', '%'.$this->filters['search'].'%');
            });
        }
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }
        return $query->get();
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->role,
            $user->created_at->format('d.m.Y H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Имя',
            'Email',
            'Роль',
            'Дата регистрации',
        ];
    }
} 