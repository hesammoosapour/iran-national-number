<?php

namespace App\Utils;

class ValidateNationalNumber
{
    private function isValidIranianNationalCode(string $input): bool {

        // کد ملی فرد// natural person
        if (!preg_match("/^\d{10}$/", $input)) {
            return false;
        }

        $check = (int) $input[9];
        $sum = array_sum(array_map(function ($x) use ($input) {
                return ((int) $input[$x]) * (10 - $x);
            }, range(0, 8))) % 11;

        return $sum < 2 ? $check == $sum : $check + $sum == 11;
    }
    private function isValidIranianNationalId($nationalId) {
        //شناسه ملی شرکت // legal person

        // بررسی طول و ساختار عددی
        if (!preg_match("/^\d{11}$/", $nationalId)) {
            return false;
        }

        $coefficients = [29, 27, 23, 19, 17, 29, 27, 23, 19, 17];
        $tensPlusTwo = (int)$nationalId[9] + 2; // رقم دهگان + 2
        $sum = 0;

        // محاسبه حاصل ضرب ارقام
        for ($i = 0; $i < 10; $i++) {
            $digit = (int)$nationalId[$i];
            $sum += ($digit + $tensPlusTwo) * $coefficients[$i];
        }

        // محاسبه باقی‌مانده
        $remainder = $sum % 11;
        if ($remainder == 10) {
            $remainder = 0;
        }

        // مقایسه رقم کنترل
        return $remainder == (int)$nationalId[10];
    }

    public static function validateNumber($number)
    {
        if (strlen((string)$number) == 10) {
            return self::isValidIranianNationalCode($number);
        }
        if (strlen((string)$number) == 11) {
            return self::isValidIranianNationalId($number);
        }
        else
            throw new \Exception('Invalid Number');
    }
}
