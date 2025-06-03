<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Отчет по бюджету</title>
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
        <h1>Отчет по бюджету за {{ $month }}.{{ $year }}</h1>
        <p>Сгенерировано: {{ $generatedAt }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Материал</th>
                <th>Количество</th>
                <th>Цена</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials as $material)
                @php
                    $quantity = $material->distributions->sum('quantity');
                    $sum = $quantity * $material->price;
                @endphp
                <tr>
                    <td>{{ $material->name }}</td>
                    <td>{{ $quantity }}</td>
                    <td>{{ number_format($material->price, 2) }}</td>
                    <td>{{ number_format($sum, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Общая сумма: {{ number_format($totalCost, 2) }} руб.
    </div>

    <div class="footer">
        <p>Документ сгенерирован автоматически</p>
    </div>
</body>
</html> 