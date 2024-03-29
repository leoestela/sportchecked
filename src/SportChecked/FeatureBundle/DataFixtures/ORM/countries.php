<?php

namespace SportChecked\FeatureBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SportChecked\FeatureBundle\Entity\Country;

class countries extends AbstractFixture implements OrderedFixtureInterface {

    public function getOrder() {
        return 21;
    }

    public function load(ObjectManager $manager) {
        $countries = array(
            array('id' => 'AF', 'name' => 'Afganistán (افغانستان‎)'),
            array('id' => 'AL', 'name' => 'Albania (Shqipëria)'),
            array('id' => 'DE', 'name' => 'Alemania (Deutschland)'),
            array('id' => 'AD', 'name' => 'Andorra'),
            array('id' => 'AO', 'name' => 'Angola'),
            array('id' => 'AI', 'name' => 'Anguila'),
            array('id' => 'AG', 'name' => 'Antigua y Barbuda'),
            array('id' => 'AQ', 'name' => 'Antártida'),
            array('id' => 'SA', 'name' => 'Arabia Saudita (المملكة العربية السعودية‎)'),
            array('id' => 'DZ', 'name' => 'Argelia (الجزائر‎)'),
            array('id' => 'AR', 'name' => 'Argentina'),
            array('id' => 'AM', 'name' => 'Armenia (Հայաստան)'),
            array('id' => 'AW', 'name' => 'Aruba'),
            array('id' => 'AU', 'name' => 'Australia'),
            array('id' => 'AT', 'name' => 'Austria (Österreich)'),
            array('id' => 'AZ', 'name' => 'Azerbaiyán (Azərbaycan)'),
            array('id' => 'BS', 'name' => 'Bahamas'),
            array('id' => 'BH', 'name' => 'Bahrein (البحرين‎)'),
            array('id' => 'BD', 'name' => 'Bangladesh (বাংলাদেশ)'),
            array('id' => 'BB', 'name' => 'Barbados'),
            array('id' => 'BZ', 'name' => 'Belice'),
            array('id' => 'BJ', 'name' => 'Benín (Bénin)'),
            array('id' => 'BM', 'name' => 'Bermudas'),
            array('id' => 'BY', 'name' => 'Bielorrusia (Беларусь)'),
            array('id' => 'MM', 'name' => 'Birmania (မြန်မာ)'),
            array('id' => 'BQ', 'name' => 'Bonaire, San Eustaquio y Saba'),
            array('id' => 'BA', 'name' => 'Bosnia-Herzegovina (Босна и Херцеговина)'),
            array('id' => 'BW', 'name' => 'Botsuana'),
            array('id' => 'BR', 'name' => 'Brasil (Brasil)'),
            array('id' => 'BN', 'name' => 'Brunéi'),
            array('id' => 'BG', 'name' => 'Bulgaria (България)'),
            array('id' => 'BF', 'name' => 'Burkina Faso'),
            array('id' => 'BI', 'name' => 'Burundi (Uburundi)'),
            array('id' => 'BT', 'name' => 'Bután (འབྲུག)'),
            array('id' => 'BE', 'name' => 'Bélgica (België)'),
            array('id' => 'CV', 'name' => 'Cabo Verde (Kabu Verdi)'),
            array('id' => 'KH', 'name' => 'Camboya (កម្ពុជា)'),
            array('id' => 'CM', 'name' => 'Camerún (Cameroun)'),
            array('id' => 'CA', 'name' => 'Canadá'),
            array('id' => 'QA', 'name' => 'Catar (قطر‎)'),
            array('id' => 'TD', 'name' => 'Chad (Tchad)'),
            array('id' => 'CL', 'name' => 'Chile'),
            array('id' => 'CN', 'name' => 'China (中国)'),
            array('id' => 'CY', 'name' => 'Chipre (Κύπρος)'),
            array('id' => 'CO', 'name' => 'Colombia'),
            array('id' => 'KM', 'name' => 'Comoras (جزر القمر‎)'),
            array('id' => 'CG', 'name' => 'Congo (Congo-Brazzaville)'),
            array('id' => 'CR', 'name' => 'Costa Rica'),
            array('id' => 'CI', 'name' => 'Costa de Marfil'),
            array('id' => 'HR', 'name' => 'Croacia (Hrvatska)'),
            array('id' => 'CU', 'name' => 'Cuba'),
            array('id' => 'CW', 'name' => 'Curazao'),
            array('id' => 'DK', 'name' => 'Dinamarca (Danmark)'),
            array('id' => 'DM', 'name' => 'Dominica'),
            array('id' => 'EC', 'name' => 'Ecuador'),
            array('id' => 'EG', 'name' => 'Egipto (مصر‎)'),
            array('id' => 'SV', 'name' => 'El Salvador'),
            array('id' => 'AE', 'name' => 'Emiratos Árabes Unidos (الإمارات العربية المتحدة‎)'),
            array('id' => 'ER', 'name' => 'Eritrea'),
            array('id' => 'SK', 'name' => 'Eslovaquia (Slovensko)'),
            array('id' => 'SI', 'name' => 'Eslovenia (Slovenija)'),
            array('id' => 'ES', 'name' => 'España'),
            array('id' => 'BO', 'name' => 'Estado Plurinacional de Bolivia'),
            array('id' => 'PS', 'name' => 'Estado de Palestina (فلسطين‎)'),
            array('id' => 'FM', 'name' => 'Estados Federados de Micronesia'),
            array('id' => 'US', 'name' => 'Estados Unidos'),
            array('id' => 'EE', 'name' => 'Estonia (Eesti)'),
            array('id' => 'ET', 'name' => 'Etiopía'),
            array('id' => 'RU', 'name' => 'Federación Rusa (Россия)'),
            array('id' => 'PH', 'name' => 'Filipinas'),
            array('id' => 'FI', 'name' => 'Finlandia (Suomi)'),
            array('id' => 'FJ', 'name' => 'Fiyi'),
            array('id' => 'FR', 'name' => 'Francia'),
            array('id' => 'GA', 'name' => 'Gabón'),
            array('id' => 'GM', 'name' => 'Gambia'),
            array('id' => 'GE', 'name' => 'Georgia (საქართველო)'),
            array('id' => 'GH', 'name' => 'Ghana (Gaana)'),
            array('id' => 'GI', 'name' => 'Gibraltar'),
            array('id' => 'GD', 'name' => 'Granada'),
            array('id' => 'GR', 'name' => 'Grecia (Ελλάδα)'),
            array('id' => 'GL', 'name' => 'Groenlandia (Kalaallit Nunaat)'),
            array('id' => 'GP', 'name' => 'Guadalupe'),
            array('id' => 'GU', 'name' => 'Guam'),
            array('id' => 'GT', 'name' => 'Guatemala'),
            array('id' => 'GY', 'name' => 'Guayana'),
            array('id' => 'GF', 'name' => 'Guayana Francesa (Guyane française)'),
            array('id' => 'GG', 'name' => 'Guernsey'),
            array('id' => 'GQ', 'name' => 'Guinea Ecuatorial (Guinea Ecuatorial)'),
            array('id' => 'GW', 'name' => 'Guinea-Bisáu (Guiné Bissau)'),
            array('id' => 'HT', 'name' => 'Haití'),
            array('id' => 'HN', 'name' => 'Honduras'),
            array('id' => 'HK', 'name' => 'Hong Kong (香港)'),
            array('id' => 'HU', 'name' => 'Hungría (Magyarország)'),
            array('id' => 'IN', 'name' => 'India (भारत)'),
            array('id' => 'ID', 'name' => 'Indonesia'),
            array('id' => 'IQ', 'name' => 'Irak (العراق‎)'),
            array('id' => 'IE', 'name' => 'Irlanda'),
            array('id' => 'BV', 'name' => 'Isla Bouvet'),
            array('id' => 'NF', 'name' => 'Isla Norfolk'),
            array('id' => 'IM', 'name' => 'Isla de Man'),
            array('id' => 'CX', 'name' => 'Isla de Navidad'),
            array('id' => 'IS', 'name' => 'Islandia (Ísland)'),
            array('id' => 'KY', 'name' => 'Islas Caimán'),
            array('id' => 'CC', 'name' => 'Islas Cocos'),
            array('id' => 'CK', 'name' => 'Islas Cook'),
            array('id' => 'FO', 'name' => 'Islas Feroe (Føroyar)'),
            array('id' => 'GS', 'name' => 'Islas Georgias del Sur y Sandwich del Sur'),
            array('id' => 'HM', 'name' => 'Islas Heard y McDonald'),
            array('id' => 'FK', 'name' => 'Islas Malvinas (Islas Malvinas)'),
            array('id' => 'MH', 'name' => 'Islas Marshall'),
            array('id' => 'SB', 'name' => 'Islas Salomón'),
            array('id' => 'TC', 'name' => 'Islas Turcas y Caicos'),
            array('id' => 'UM', 'name' => 'Islas Ultramarinas Menores de Estados Unidos'),
            array('id' => 'VG', 'name' => 'Islas Vírgenes Británicas'),
            array('id' => 'VI', 'name' => 'Islas Vírgenes de los Estados Unidos'),
            array('id' => 'AX', 'name' => 'Islas de Aland'),
            array('id' => 'IL', 'name' => 'Israel (ישראל‎)'),
            array('id' => 'IT', 'name' => 'Italia (Italia)'),
            array('id' => 'JM', 'name' => 'Jamaica'),
            array('id' => 'JP', 'name' => 'Japón (日本)'),
            array('id' => 'JE', 'name' => 'Jersey'),
            array('id' => 'JO', 'name' => 'Jordania (الأردن‎)'),
            array('id' => 'KZ', 'name' => 'Kazajistán (Казахстан)'),
            array('id' => 'KE', 'name' => 'Kenia'),
            array('id' => 'KG', 'name' => 'Kirguistán'),
            array('id' => 'KI', 'name' => 'Kiribati'),
            array('id' => 'KW', 'name' => 'Kuwait (الكويت‎)'),
            array('id' => 'LS', 'name' => 'Lesoto'),
            array('id' => 'LV', 'name' => 'Letonia (Latvija)'),
            array('id' => 'LR', 'name' => 'Liberia'),
            array('id' => 'LY', 'name' => 'Libia (ليبيا‎)'),
            array('id' => 'LI', 'name' => 'Liechtenstein'),
            array('id' => 'LT', 'name' => 'Lituania (Lietuva)'),
            array('id' => 'LU', 'name' => 'Luxemburgo'),
            array('id' => 'LB', 'name' => 'Líbano (لبنان‎)'),
            array('id' => 'MO', 'name' => 'Macao (澳門)'),
            array('id' => 'MG', 'name' => 'Madagascar (Madagasikara)'),
            array('id' => 'MY', 'name' => 'Malasia'),
            array('id' => 'MW', 'name' => 'Malaui'),
            array('id' => 'MV', 'name' => 'Maldivas'),
            array('id' => 'MT', 'name' => 'Malta'),
            array('id' => 'ML', 'name' => 'Malí'),
            array('id' => 'MP', 'name' => 'Marianas del Norte'),
            array('id' => 'MA', 'name' => 'Marruecos (المغرب‎)'),
            array('id' => 'MQ', 'name' => 'Martinica'),
            array('id' => 'MU', 'name' => 'Mauricio (Moris)'),
            array('id' => 'MR', 'name' => 'Mauritania (موريتانيا‎)'),
            array('id' => 'YT', 'name' => 'Mayotte'),
            array('id' => 'MN', 'name' => 'Mongolia (Монгол)'),
            array('id' => 'ME', 'name' => 'Montenegro (Crna Gora)'),
            array('id' => 'MS', 'name' => 'Montserrat'),
            array('id' => 'MZ', 'name' => 'Mozambique (Moçambique)'),
            array('id' => 'MX', 'name' => 'México (México)'),
            array('id' => 'MC', 'name' => 'Mónaco'),
            array('id' => 'NA', 'name' => 'Namibia'),
            array('id' => 'NR', 'name' => 'Nauru'),
            array('id' => 'NP', 'name' => 'Nepal (नेपाल)'),
            array('id' => 'NI', 'name' => 'Nicaragua'),
            array('id' => 'NG', 'name' => 'Nigeria'),
            array('id' => 'NU', 'name' => 'Niue'),
            array('id' => 'NO', 'name' => 'Noruega (Norge)'),
            array('id' => 'NC', 'name' => 'Nueva Caledonia (Nouvelle-Calédonie)'),
            array('id' => 'NZ', 'name' => 'Nueva Zelanda'),
            array('id' => 'NE', 'name' => 'Níger (Nijar)'),
            array('id' => 'OM', 'name' => 'Omán (عُمان‎)'),
            array('id' => 'PK', 'name' => 'Pakistán (پاکستان‎)'),
            array('id' => 'PW', 'name' => 'Palaos'),
            array('id' => 'PA', 'name' => 'Panamá (Panamá)'),
            array('id' => 'PG', 'name' => 'Papúa-Nueva Guinea'),
            array('id' => 'PY', 'name' => 'Paraguay'),
            array('id' => 'NL', 'name' => 'Países Bajos (Nederland)'),
            array('id' => 'PE', 'name' => 'Perú'),
            array('id' => 'PN', 'name' => 'Pitcairn'),
            array('id' => 'PF', 'name' => 'Polinesia Francesa (Polynésie française)'),
            array('id' => 'PL', 'name' => 'Polonia (Polska)'),
            array('id' => 'PT', 'name' => 'Portugal'),
            array('id' => 'PR', 'name' => 'Puerto Rico'),
            array('id' => 'GB', 'name' => 'Reino Unido'),
            array('id' => 'VE', 'name' => 'República Bolivariana de Venezuela'),
            array('id' => 'CF', 'name' => 'República Centroafricana (République centrafricaine)'),
            array('id' => 'CZ', 'name' => 'República Checa (Česká republika)'),
            array('id' => 'LA', 'name' => 'República Democrática Popular Lao (ສ.ປ.ປ ລາວ)'),
            array('id' => 'CD', 'name' => 'República Democrática del Congo (Jamhuri ya Kidemokrasia ya Kongo)'),
            array('id' => 'DO', 'name' => 'República Dominicana (República Dominicana)'),
            array('id' => 'IR', 'name' => 'República Islámica de Irán (ایران‎)'),
            array('id' => 'KP', 'name' => 'República Popular Democrática de Corea (조선 민주주의 인민 공화국)'),
            array('id' => 'TZ', 'name' => 'República Unida de Tanzania'),
            array('id' => 'KR', 'name' => 'República de Corea (대한민국)'),
            array('id' => 'GN', 'name' => 'República de Guinea (Guinée)'),
            array('id' => 'MK', 'name' => 'República de Macedonia (Македонија)'),
            array('id' => 'MD', 'name' => 'República de Moldavia (Republica Moldova)'),
            array('id' => 'SY', 'name' => 'República Árabe de Siria (سوريا‎)'),
            array('id' => 'RE', 'name' => 'Reunión'),
            array('id' => 'RW', 'name' => 'Ruanda'),
            array('id' => 'RO', 'name' => 'Rumanía (România)'),
            array('id' => 'WS', 'name' => 'Samoa'),
            array('id' => 'AS', 'name' => 'Samoa Americana'),
            array('id' => 'BL', 'name' => 'San Bartolomé (Saint-Barthélémy)'),
            array('id' => 'KN', 'name' => 'San Cristóbal y Nevis'),
            array('id' => 'SM', 'name' => 'San Marino'),
            array('id' => 'MF', 'name' => 'San Martín (parte francesa) (Saint-Martin [partie française])'),
            array('id' => 'SX', 'name' => 'San Martín (parte holandesa)'),
            array('id' => 'PM', 'name' => 'San Pedro y Miquelón (Saint-Pierre-et-Miquelon)'),
            array('id' => 'VC', 'name' => 'San Vincente y Granadinas'),
            array('id' => 'SH', 'name' => 'Santa Elena, Ascensión y Tristán de Acuña'),
            array('id' => 'LC', 'name' => 'Santa Lucía'),
            array('id' => 'VA', 'name' => 'Santa Sede (Ciudad Estado del Vaticano) (Città del Vaticano)'),
            array('id' => 'ST', 'name' => 'Santo Tomé y Príncipe (São Tomé e Príncipe)'),
            array('id' => 'SN', 'name' => 'Senegal (Sénégal)'),
            array('id' => 'RS', 'name' => 'Serbia (Србија)'),
            array('id' => 'SC', 'name' => 'Seychelles'),
            array('id' => 'SL', 'name' => 'Sierra Leona'),
            array('id' => 'SG', 'name' => 'Singapur'),
            array('id' => 'SO', 'name' => 'Somalia (Soomaaliya)'),
            array('id' => 'LK', 'name' => 'Sri Lanka (ශ්‍රී ලංකාව))'),
            array('id' => 'SZ', 'name' => 'Suazilandia'),
            array('id' => 'ZA', 'name' => 'Sudáfrica'),
            array('id' => 'SD', 'name' => 'Sudán (السودان‎)'),
            array('id' => 'SS', 'name' => 'Sudán del Sur (جنوب السودان‎)'),
            array('id' => 'SE', 'name' => 'Suecia (Sverige)'),
            array('id' => 'CH', 'name' => 'Suiza (Schweiz)'),
            array('id' => 'SR', 'name' => 'Surinam'),
            array('id' => 'SJ', 'name' => 'Svalbard y Jan Mayen (Svalbard og Jan Mayen)'),
            array('id' => 'EH', 'name' => 'Sáhara Occidental (الصحراء الغربية‎)'),
            array('id' => 'TH', 'name' => 'Tailandia (ไทย)'),
            array('id' => 'TW', 'name' => 'Taiwán (台灣)'),
            array('id' => 'TJ', 'name' => 'Tayikistán'),
            array('id' => 'IO', 'name' => 'Territorio Británico del Océano Índico'),
            array('id' => 'TF', 'name' => 'Tierras Australes y Antárticas Francesas (Terres australes françaises)'),
            array('id' => 'TL', 'name' => 'Timor Oriental'),
            array('id' => 'TG', 'name' => 'Togo'),
            array('id' => 'TK', 'name' => 'Tokelau'),
            array('id' => 'TO', 'name' => 'Tonga'),
            array('id' => 'TT', 'name' => 'Trinidad y Tobago'),
            array('id' => 'TM', 'name' => 'Turkmenistán'),
            array('id' => 'TR', 'name' => 'Turquía (Türkiye)'),
            array('id' => 'TV', 'name' => 'Tuvalu'),
            array('id' => 'TN', 'name' => 'Túnez (تونس‎)'),
            array('id' => 'UA', 'name' => 'Ucrania (Україна)'),
            array('id' => 'UG', 'name' => 'Uganda'),
            array('id' => 'UY', 'name' => 'Uruguay'),
            array('id' => 'UZ', 'name' => 'Uzbekistán (Ўзбекистон)'),
            array('id' => 'VU', 'name' => 'Vanuatu'),
            array('id' => 'VN', 'name' => 'Vietnam (Việt Nam)'),
            array('id' => 'WF', 'name' => 'Wallis y Futuna'),
            array('id' => 'YE', 'name' => 'Yemen (اليمن‎)'),
            array('id' => 'DJ', 'name' => 'Yibuti'),
            array('id' => 'ZM', 'name' => 'Zambia'),
            array('id' => 'ZW', 'name' => 'Zimbabue'),
        );

        foreach ($countries as $country) {
            $entity = new Country();
             $entity->setId($country['id']);
            $entity->setName($country['name']);
            $manager->persist($entity);
        }
        $manager->flush();
    }

}

