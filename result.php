<?php
// Получаем текст из формы
$text = isset($_POST['text']) ? trim($_POST['text']) : '';

// Функция для подсчета информации о тексте
function analyzeText($text) {
    $result = [];

    // Проверка наличия текста
    if (empty($text)) {
        return "Нет текста для анализа";
    }

    // Подсчет различных характеристик текста
    $result['char_count'] = mb_strlen($text); // Количество символов
    $result['letter_count'] = preg_match_all('/[a-zA-Zа-яА-ЯёЁ]/u', $text); // Количество букв
    $result['upper_count'] = preg_match_all('/[A-ZА-ЯЁ]/u', $text); // Количество заглавных букв
    $result['lower_count'] = preg_match_all('/[a-zа-яё]/u', $text); // Количество строчных букв
    $result['punct_count'] = preg_match_all('/[.,!?;:]/u', $text); // Количество знаков препинания
    $result['digit_count'] = preg_match_all('/\d/', $text); // Количество цифр
    
    // Подсчет слов с использованием регулярного выражения для поддержки многоязычного текста
    preg_match_all('/\b[\p{L}\'-]+\b/u', mb_strtolower($text), $words);
    $result['word_count'] = count($words[0]); // Количество слов
    $result['word_frequency'] = array_count_values($words[0]); // Частота слов
    ksort($result['word_frequency']); // Сортируем слова по алфавиту

    // Подсчет вхождений каждого символа (без различия регистра)
    $result['char_frequency'] = array_count_values(mb_str_split(mb_strtolower($text)));

    return $result;
}

// Выполняем анализ текста
$analysisResult = analyzeText($text);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результаты анализа</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .text-output {
            color: blue;
            font-style: italic;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Результаты анализа</h1>

    <div class="text-output">
        <?php
        if (is_string($analysisResult)) {
            echo $analysisResult;
        } else {
            echo "Исходный текст: <br><span class='text-output'>" . htmlspecialchars($text) . "</span>";
        }
        ?>
    </div>

    <?php if (!is_string($analysisResult)): ?>
        <table>
            <tr>
                <th>Показатель</th>
                <th>Значение</th>
            </tr>
            <tr>
                <td>Количество символов</td>
                <td><?= $analysisResult['char_count'] ?></td>
            </tr>
            <tr>
                <td>Количество букв</td>
                <td><?= $analysisResult['letter_count'] ?></td>
            </tr>
            <tr>
                <td>Количество заглавных букв</td>
                <td><?= $analysisResult['upper_count'] ?></td>
            </tr>
            <tr>
                <td>Количество строчных букв</td>
                <td><?= $analysisResult['lower_count'] ?></td>
            </tr>
            <tr>
                <td>Количество знаков препинания</td>
                <td><?= $analysisResult['punct_count'] ?></td>
            </tr>
            <tr>
                <td>Количество цифр</td>
                <td><?= $analysisResult['digit_count'] ?></td>
            </tr>
            <tr>
                <td>Количество слов</td>
                <td><?= $analysisResult['word_count'] ?></td>
            </tr>
        </table>

        <h2>Вхождения символов:</h2>
        <table>
            <tr>
                <th>Символ</th>
                <th>Количество</th>
            </tr>
            <?php foreach ($analysisResult['char_frequency'] as $char => $count): ?>
                <tr>
                    <td><?= htmlspecialchars($char) ?></td>
                    <td><?= $count ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Частота слов:</h2>
        <table>
            <tr>
                <th>Слово</th>
                <th>Количество</th>
            </tr>
            <?php foreach ($analysisResult['word_frequency'] as $word => $count): ?>
                <tr>
                    <td><?= htmlspecialchars($word) ?></td>
                    <td><?= $count ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <a href="index.html">Другой анализ</a>
</body>
</html>
