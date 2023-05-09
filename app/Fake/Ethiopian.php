<?php

namespace App\Fake;

class Ethiopian
{
    private static bool $unique = false;

    const FIRST_NAMES = [
        'Abebe', 'Temesgen', 'Selamawit', 'Muluken', 'Hirut', 'Yonas', 'Eleni', 'Girma', 'Adanech', 'Tigist',
        'Berhanu', 'Dawit', 'Frehiwot', 'Kalkidan', 'Fasil', 'Meron', 'Zerihun', 'Abenet', 'Mahlet', 'Tamirat',
        'Genet', 'Mikael', 'Sosina', 'Eyasu', 'Tewodros', 'Rediet', 'Yohannes', 'Belaynesh', 'Seble', 'Henok',
        'Hiwot', 'Aster', 'Biruk', 'Wubet', 'Alemayehu', 'Meseret', 'Gebre', 'Tirunesh', 'Samuel', 'Kidan',
        'Mesfin', 'Betelhem', 'Abraham', 'Rahel', 'Tsegaye', 'Semira', 'Dereje', 'Almaz', 'Yared', 'Tsion',
    ];

    const LAST_NAMES = [
        'Fikre', 'Gebremariam', 'Kebede', 'Tesfaye', 'Tadesse', 'Mulugeta', 'Wolde', 'Bekele', 'Abebe', 'Assefa',
        'Getachew', 'Mekonnen', 'Yirga', 'Mengistu', 'Alemu', 'Teshome', 'Worku', 'Demissie', 'Haile', 'Desta',
        'Demeke', 'Gebre', 'Zewde', 'Lemma', 'Hailu', 'Girma', 'Tsegaye', 'Fisseha', 'Tilahun', 'Abera',
        'Ayalew', 'Bekele', 'Dejene', 'Tafesse', 'Desalegn', 'Amare', 'Meskel', 'Tefera', 'Belay', 'Tadesse',
        'Tekle', 'Seyoum', 'Asrat', 'Teka', 'Bishaw', 'Negash', 'Tsegaw', 'Woldu', 'Tesema', 'Alemayehu',
        'Abel', 'Abdi', 'Abraha', 'Adem', 'Adinew', 'Aklilu', 'Alem', 'Alemayehu', 'Amare', 'Amanuel', 'Amde',
        'Amdework', 'Andinet', 'Asfaw', 'Asrat', 'Aster', 'Bamlak', 'Berhan', 'Biruk', 'Brook', 'Daniel', 'Dawit',
        'Desta', 'Ermias', 'Eyob', 'Fikadu', 'Fisseha', 'Girma', 'Haben', 'Habtamu', 'Henok', 'Isaac', 'Israel',
        'Jemal', 'Kaleb', 'Kalkidan', 'Kebede', 'Kirubel', 'Leul', 'Mahari', 'Mathewos', 'Mesfin', 'Michael', 'Million',
        'Mulugeta', 'Nahom', 'Natnael', 'Nebiyu', 'Nega', 'Nigussie', 'Nuredin', 'Samuel', 'Seble', 'Seifu', 'Semere',
        'Solomon', 'Surafel', 'Tamirat', 'Tefere', 'Tekle', 'Tesfaye', 'Tesfayehegn', 'Tewodros', 'Tilahun', 'Tinsae',
        'Yared', 'Yohannes', 'Yoseph', 'Zebene', 'Zerihun', 'Abebe', 'Temesgen', 'Selamawit', 'Muluken', 'Hirut', 'Yonas',
        'Eleni', 'Girma', 'Adanech', 'Tigist', 'Berhanu', 'Dawit', 'Frehiwot', 'Kalkidan', 'Fasil', 'Meron', 'Zerihun',
        'Abenet', 'Mahlet', 'Tamirat', 'Genet', 'Mikael', 'Sosina', 'Eyasu', 'Tewodros', 'Rediet', 'Yohannes', 'Belaynesh',
        'Seble', 'Henok', 'Hiwot', 'Aster', 'Biruk', 'Wubet', 'Alemayehu', 'Meseret', 'Gebre', 'Tirunesh', 'Samuel', 'Kidan',
        'Mesfin', 'Betelhem', 'Abraham', 'Rahel', 'Tsegaye', 'Semira', 'Dereje', 'Almaz', 'Yared', 'Tsion',
    ];

    const MALE_FIRST_NAMES = [
        'Abel', 'Abdi', 'Abraha', 'Adem', 'Adinew', 'Aklilu', 'Alem', 'Alemayehu', 'Amare', 'Amanuel', 'Amde',
        'Amdework', 'Andinet', 'Asfaw', 'Asrat', 'Aster', 'Bamlak', 'Berhan', 'Biruk', 'Brook', 'Daniel', 'Dawit',
        'Desta', 'Ermias', 'Eyob', 'Fikadu', 'Fisseha', 'Girma', 'Haben', 'Habtamu', 'Henok', 'Isaac', 'Israel',
        'Jemal', 'Kaleb', 'Kalkidan', 'Kebede', 'Kirubel', 'Leul', 'Mahari', 'Mathewos', 'Mesfin', 'Michael', 'Million',
        'Mulugeta', 'Nahom', 'Natnael', 'Nebiyu', 'Nega', 'Nigussie', 'Nuredin', 'Samuel', 'Seble', 'Seifu', 'Semere',
        'Solomon', 'Surafel', 'Tamirat', 'Tefere', 'Tekle', 'Tesfaye', 'Tesfayehegn', 'Tewodros', 'Tilahun', 'Tinsae',
        'Yared', 'Yohannes', 'Yoseph', 'Zebene', 'Zerihun',
    ];

    const FEMALE_FIRST_NAMES = [
        'Abelone', 'Abenet', 'Abrehet', 'Adanech', 'Adey', 'Adiam', 'Adina', 'Almaz', 'Aman', 'Ameha', 'Amira',
        'Aschalew', 'Asfawosen', 'Asnakech', 'Asrat', 'Aster', 'Atsede', 'Ayantu', 'Ayda', 'Ayenew', 'Azmera',
        'Banchi', 'Bati', 'Beza', 'Bezawit', 'Bilen', 'Birhan', 'Bizuayehu', 'Bruktawit', 'Dagmawi', 'Dagmawit',
        'Dara', 'Dehab', 'Dehaba', 'Dihnet', 'Eden', 'Edom', 'Efrata', 'Egziabher', 'Emebet', 'Emnet', 'Erehima',
        'Etsegenet', 'Eyerusalem', 'Fetiya', 'Firehiwot', 'Frehiwot', 'Genet', 'Gete', 'Ginchi', 'Ginette', 'Ginika',
        'Girma', 'Gizeyat', 'Gloria', 'Gulit', 'Habiba', 'Habtam', 'Hanna', 'Hannan', 'Helina', 'Heran', 'Hirut',
        'Ibtihal', 'Inat', 'Irean', 'Kaliti', 'Kalitiya',
    ];

    const PHONE_PREFIXES = [
        '+2519', '+2517', '09', '07',
    ];

    const MALE_RELATIVES = [
        'Father', 'Step Father', 'Brother', 'Uncle', 'Cousin', 'Grandfather',
    ];

    const FEMALE_RELATIVES = [
        'Mother', 'Step Mother', 'Sister', 'Aunt', 'Cousin', 'Grandmother',
    ];

    public function unique(): bool
    {
        return Ethiopian::class;
    }

    public function firstName($gender = 'mixed'): string
    {
        if ($gender === 'male') {
            return self::MALE_FIRST_NAMES[array_rand(self::MALE_FIRST_NAMES)];
        } elseif ($gender === 'female') {
            return self::FEMALE_FIRST_NAMES[array_rand(self::FEMALE_FIRST_NAMES)];
        }

        return self::FIRST_NAMES[array_rand(self::FIRST_NAMES)];
    }

    public function lastName(): string
    {
        return self::LAST_NAMES[array_rand(self::LAST_NAMES)];
    }

    public function fullName($gender = 'mixed'): string
    {
        return $this->firstName($gender).' '.$this->lastName();
    }

    public function email($name = null): string
    {
        return $this->username($name).(self::rand() ? '@outlook.com' : '@gmail.com');
    }

    public function username($name = null): string
    {
        $name = $name ?? $this->fullName();
        $name = str_replace(' ', self::rand() ? '.' : '_', $name);

        return strtolower($name).(self::rand('int'));
    }

    public function phoneNumber(): string
    {
        $prefix = self::PHONE_PREFIXES[array_rand(self::PHONE_PREFIXES)];

        return $prefix.rand(10000000, 99999999);
    }

    public function relative($gender = 'female'): string
    {
        if ($gender === 'male') {
            return self::MALE_RELATIVES[array_rand(self::MALE_RELATIVES)];
        }

        return self::FEMALE_RELATIVES[array_rand(self::FEMALE_RELATIVES)];
    }

    private static function rand($type = 'bool'): int
    {
        if ($type === 'int') {
            return rand(1000, 9999);
        }

        return rand(0, 1);
    }
}
