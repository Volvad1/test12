<?php

$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];


function getPartsFromFullname($fullname) {
    $parts = explode(' ', $fullname);
    return [
        'surname' => $parts[0],
        'name' => $parts[1],
        'patronomyc' => $parts[2],
    ];
}

$fullname = "Иванов Иван Иванович";
$parts = getPartsFromFullname($fullname);

echo "Фамилия: " . $parts['surname'] . "\n";
echo "Имя: " . $parts['name'] . "\n";
echo "Отчество: " . $parts['patronomyc'] . "\n";


function getFullnameFromParts($surname, $name, $patronomyc) {
    return "$surname $name $patronomyc";
}

$surname = "Иванов";
$name = "Иван";
$patronomyc = "Иванович";

$fullname = getFullnameFromParts($surname, $name, $patronomyc);

echo "Полное имя: " . $fullname;

function getShortName($fullname) {
    $parts = getPartsFromFullname($fullname);
    return $parts['name'] . ' ' . mb_substr($parts['surname'], 0, 1) . '.';
}

$fullname = "Иванов Иван Иванович";
$shortName = getShortName($fullname);

echo "Сокращенное ФИО: " . $shortName;

function getGenderFromName($fullname) {
    $parts = getPartsFromFullname($fullname);
    
    $surname = mb_strtolower($parts['surname']);
    $name = mb_strtolower($parts['name']);
    $patronomyc = mb_strtolower($parts['patronomyc']);

    $malePatterns = [
        'вич', 
        
    ];

    $femalePatterns = [
        'вна',  
          
    ];

    foreach ($malePatterns as $pattern) {
        if (mb_substr($patronomyc, -mb_strlen($pattern)) === $pattern || 
            mb_substr($name, -mb_strlen($pattern)) === $pattern || 
            mb_substr($surname, -mb_strlen($pattern)) === $pattern) {
            return 1; 
        }
    }

    foreach ($femalePatterns as $pattern) {
        if (mb_substr($patronomyc, -mb_strlen($pattern)) === $pattern || 
            mb_substr($name, -mb_strlen($pattern)) === $pattern || 
            mb_substr($surname, -mb_strlen($pattern)) === $pattern) {
            return -1; 
        }
    }

    return 0; 
}

$fullname = "Иванов Иван Иванович";
$gender = getGenderFromName($fullname);

if ($gender === 1) {
    echo "Мужской пол";
} elseif ($gender === -1) {
    echo "Женский пол";
} else {
    echo "Не удалось определить пол";
}

function getGenderDescription($persons) {
    $total = count($persons);
    $maleCount = 0;
    $femaleCount = 0;
    $undefinedCount = 0;

    foreach ($persons as $person) {
        $gender = getGenderFromName($person['fullname']);
        if ($gender === 1) {
            $maleCount++;
        } elseif ($gender === -1) {
            $femaleCount++;
        } else {
            $undefinedCount++;
        }
    }

    $malePercentage = round(($maleCount / $total) * 100, 1);
    $femalePercentage = round(($femaleCount / $total) * 100, 1);
    $undefinedPercentage = round(($undefinedCount / $total) * 100, 1);

    echo "Гендерный состав аудитории:\n";
    echo "---------------------------\n";
    echo "Мужчины - $malePercentage%\n";
    echo "Женщины - $femalePercentage%\n";
    echo "Не удалось определить - $undefinedPercentage%\n";
}

function getPerfectPartner($surname, $name, $patronomyc, $persons) {
    $surname = mb_strtolower($surname);
    $name = mb_strtolower($name);
    $patronomyc = mb_strtolower($patronomyc);
    
    $fullname = getFullnameFromParts($surname, $name, $patronomyc);

    $gender = getGenderFromName($fullname);
    
    while (true) {
        $randomIndex = array_rand($persons);
        $randomPerson = $persons[$randomIndex];
        
        $partnerGender = getGenderFromName($randomPerson['fullname']);
        
        if ($gender !== $partnerGender && ($gender === 1 || $partnerGender === 1)) {
            $compatibilityPercentage = rand(5000, 10000) / 100;
            
            $shortName = mb_convert_case(getShortName($fullname), MB_CASE_TITLE);
            $partnerShortName = mb_convert_case(getShortName($randomPerson['fullname']), MB_CASE_TITLE);

            echo "$shortName + $partnerShortName = \n";
            echo "♡ Идеально на $compatibilityPercentage% ♡\n";
            
            break;
        }
    }
}

getGenderDescription($example_persons_array);

getPerfectPartner('Иванов', 'Иван', 'Иванович', $example_persons_array);