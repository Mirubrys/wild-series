<?php


namespace App\Service;


class Slugify
{
    public function generate(string $input) : string
    {
        $search  = array(
            'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'à', 'á', 'â', 'ã', 'ä', 'å',
            'Ç', 'ç',
            'È', 'É', 'Ê', 'Ë', 'è', 'é', 'ê', 'ë',
            'Ì', 'Í', 'Î', 'Ï', 'ì', 'í', 'î', 'ï',
            'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö',
            'Ù', 'Ú', 'Û', 'Ü', 'ù', 'ú', 'û', 'ü',
            'Ý', 'Ÿ', 'ý', 'ÿ'
        );

        $replace = array(
            'A', 'A', 'A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a', 'a',
            'C', 'c',
            'E', 'E', 'E', 'E', 'e', 'e', 'e', 'e',
            'I', 'I', 'I', 'I', 'i', 'i', 'i', 'i',
            'O', 'O', 'O', 'O', 'O', 'o', 'o', 'o', 'o', 'o', 'o',
            'U', 'U', 'U', 'U', 'u', 'u', 'u', 'u',
            'Y', 'Y', 'y', 'y'
        );

        $slug = strtolower(str_replace($search, $replace, $input));
        $slug = preg_replace('/[[:punct:]]+/', '', $slug);

        return preg_replace('/[^\w]+/', '-',  $slug);
    }
}