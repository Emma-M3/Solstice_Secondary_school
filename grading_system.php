<?php

function calculateGrade($marks, $class_level) {
    if ($class_level === 'junior') {
        // Junior Secondary: Division System (Forms 1-2)
        if ($marks >= 75) return 'A';
        elseif ($marks >= 60) return 'B';
        elseif ($marks >= 50) return 'C';
        elseif ($marks >= 40) return 'D';
        else return 'F';
    } else {
        // Senior Secondary: Grade System (Forms 3-4)
        if ($marks >= 80) return '1';
        elseif ($marks >= 70) return '2';
        elseif ($marks >= 65) return '3';
        elseif ($marks >= 60) return '4';
        elseif ($marks >= 55) return '5';
        elseif ($marks >= 50) return '6';
        elseif ($marks >= 45) return '7';
        elseif ($marks >= 40) return '8';
        else return '9';
    }
}

function getGradeDescription($grade, $class_level) {
    if ($class_level === 'junior') {
        $descriptions = [
            '1' => 'Division A - Excellent',
            '2' => 'Division B - Very Good',
            '3' => 'Division C - Good',
            '4' => 'Division D - Pass',
            'F' => 'Fail'
        ];
    } else {
        $descriptions = [
            '1' => 'Grade 1 - Distinction',
            '2' => 'Grade 2 - Distinction',
            '3' => 'Grade 3 - Credit',
            '4' => 'Grade 4 - Credit',
            '5' => 'Grade 5 - Credit',
            '6' => 'Grade 6 - Pass',
            '7' => 'Grade 7 - Pass',
            '8' => 'Grade 8 - Pass',
            '9' => 'Fail'
        ];
    }
    return $descriptions[$grade] ?? 'Unknown';
}
?>