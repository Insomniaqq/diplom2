<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Отчет по поставщикам</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 14px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Отчет по поставщикам за {{ $month }}.{{ $year }}</h1>
        <p>Сгенерировано: {{ $generatedAt }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Поставщик</th>
                <th>ИНН</th>
                <th>Количество контрактов</th>
                <th>Сумма контрактов</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
                @php
                    $contractsCount = $supplier->contracts->count();
                    $contractsSum = $supplier->contracts->sum('amount');
                @endphp
                <tr>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->inn }}</td>
                    <td>{{ $contractsCount }}</td>
                    <td>{{ number_format($contractsSum, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Общая сумма контрактов: {{ number_format($totalAmount, 2) }} руб.
    </div>

    <div class="footer">
        <p>Документ сгенерирован автоматически</p>
    </div>
</body>
</html> 