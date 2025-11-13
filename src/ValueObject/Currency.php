<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\ValueObject;

use Symfony\Component\Serializer\Attribute\Groups;

/**
 * ISO 4217 Currency Codes Enum
 */
#[Groups([MoneyInterface::class, Currency::class])]
enum Currency: string
{
    // Major Currencies
    case USD = 'USD'; // US Dollar
    case EUR = 'EUR'; // Euro
    case GBP = 'GBP'; // British Pound Sterling
    case JPY = 'JPY'; // Japanese Yen
    case CHF = 'CHF'; // Swiss Franc
    case CAD = 'CAD'; // Canadian Dollar
    case AUD = 'AUD'; // Australian Dollar
    case NZD = 'NZD'; // New Zealand Dollar

    // Asia-Pacific
    case CNY = 'CNY'; // Chinese Yuan
    case HKD = 'HKD'; // Hong Kong Dollar
    case SGD = 'SGD'; // Singapore Dollar
    case KRW = 'KRW'; // South Korean Won
    case TWD = 'TWD'; // New Taiwan Dollar
    case THB = 'THB'; // Thai Baht
    case MYR = 'MYR'; // Malaysian Ringgit
    case IDR = 'IDR'; // Indonesian Rupiah
    case PHP = 'PHP'; // Philippine Peso
    case VND = 'VND'; // Vietnamese Dong
    case INR = 'INR'; // Indian Rupee
    case PKR = 'PKR'; // Pakistani Rupee
    case BDT = 'BDT'; // Bangladeshi Taka
    case LKR = 'LKR'; // Sri Lankan Rupee
    case NPR = 'NPR'; // Nepalese Rupee
    case MMK = 'MMK'; // Myanmar Kyat
    case KHR = 'KHR'; // Cambodian Riel
    case LAK = 'LAK'; // Lao Kip
    case BND = 'BND'; // Brunei Dollar

    // Middle East
    case AED = 'AED'; // UAE Dirham
    case SAR = 'SAR'; // Saudi Riyal
    case QAR = 'QAR'; // Qatari Riyal
    case KWD = 'KWD'; // Kuwaiti Dinar
    case BHD = 'BHD'; // Bahraini Dinar
    case OMR = 'OMR'; // Omani Rial
    case JOD = 'JOD'; // Jordanian Dinar
    case ILS = 'ILS'; // Israeli Shekel
    case IQD = 'IQD'; // Iraqi Dinar
    case LBP = 'LBP'; // Lebanese Pound
    case SYP = 'SYP'; // Syrian Pound

    // Europe (non-Euro)
    case NOK = 'NOK'; // Norwegian Krone
    case SEK = 'SEK'; // Swedish Krona
    case DKK = 'DKK'; // Danish Krone
    case ISK = 'ISK'; // Icelandic Króna
    case PLN = 'PLN'; // Polish Zloty
    case CZK = 'CZK'; // Czech Koruna
    case HUF = 'HUF'; // Hungarian Forint
    case RON = 'RON'; // Romanian Leu
    case BGN = 'BGN'; // Bulgarian Lev
    case HRK = 'HRK'; // Croatian Kuna
    case RSD = 'RSD'; // Serbian Dinar
    case TRY = 'TRY'; // Turkish Lira
    case RUB = 'RUB'; // Russian Ruble
    case UAH = 'UAH'; // Ukrainian Hryvnia
    case BYN = 'BYN'; // Belarusian Ruble
    case MDL = 'MDL'; // Moldovan Leu
    case GEL = 'GEL'; // Georgian Lari
    case AMD = 'AMD'; // Armenian Dram
    case AZN = 'AZN'; // Azerbaijani Manat
    case KZT = 'KZT'; // Kazakhstani Tenge
    case UZS = 'UZS'; // Uzbekistani Som
    case KGS = 'KGS'; // Kyrgyzstani Som
    case TJS = 'TJS'; // Tajikistani Somoni
    case TMT = 'TMT'; // Turkmenistani Manat

    // Americas
    case MXN = 'MXN'; // Mexican Peso
    case BRL = 'BRL'; // Brazilian Real
    case ARS = 'ARS'; // Argentine Peso
    case CLP = 'CLP'; // Chilean Peso
    case COP = 'COP'; // Colombian Peso
    case PEN = 'PEN'; // Peruvian Sol
    case VES = 'VES'; // Venezuelan Bolívar
    case UYU = 'UYU'; // Uruguayan Peso
    case PYG = 'PYG'; // Paraguayan Guarani
    case BOB = 'BOB'; // Bolivian Boliviano
    case CRC = 'CRC'; // Costa Rican Colón
    case GTQ = 'GTQ'; // Guatemalan Quetzal
    case HNL = 'HNL'; // Honduran Lempira
    case NIO = 'NIO'; // Nicaraguan Córdoba
    case PAB = 'PAB'; // Panamanian Balboa
    case DOP = 'DOP'; // Dominican Peso
    case JMD = 'JMD'; // Jamaican Dollar
    case TTD = 'TTD'; // Trinidad and Tobago Dollar
    case BBD = 'BBD'; // Barbadian Dollar
    case BSD = 'BSD'; // Bahamian Dollar
    case BZD = 'BZD'; // Belize Dollar
    case XCD = 'XCD'; // East Caribbean Dollar
    case AWG = 'AWG'; // Aruban Florin
    case ANG = 'ANG'; // Netherlands Antillean Guilder
    case SRD = 'SRD'; // Surinamese Dollar
    case GYD = 'GYD'; // Guyanese Dollar

    // Africa
    case ZAR = 'ZAR'; // South African Rand
    case NGN = 'NGN'; // Nigerian Naira
    case EGP = 'EGP'; // Egyptian Pound
    case MAD = 'MAD'; // Moroccan Dirham
    case TND = 'TND'; // Tunisian Dinar
    case DZD = 'DZD'; // Algerian Dinar
    case LYD = 'LYD'; // Libyan Dinar
    case KES = 'KES'; // Kenyan Shilling
    case TZS = 'TZS'; // Tanzanian Shilling
    case UGX = 'UGX'; // Ugandan Shilling
    case GHS = 'GHS'; // Ghanaian Cedi
    case ETB = 'ETB'; // Ethiopian Birr
    case ZMW = 'ZMW'; // Zambian Kwacha
    case MWK = 'MWK'; // Malawian Kwacha
    case BWP = 'BWP'; // Botswana Pula
    case NAD = 'NAD'; // Namibian Dollar
    case MUR = 'MUR'; // Mauritian Rupee
    case SCR = 'SCR'; // Seychellois Rupee
    case MZN = 'MZN'; // Mozambican Metical
    case AOA = 'AOA'; // Angolan Kwanza
    case XOF = 'XOF'; // West African CFA Franc
    case XAF = 'XAF'; // Central African CFA Franc
    case RWF = 'RWF'; // Rwandan Franc
    case BIF = 'BIF'; // Burundian Franc
    case DJF = 'DJF'; // Djiboutian Franc
    case SOS = 'SOS'; // Somali Shilling
    case SDG = 'SDG'; // Sudanese Pound
    case SSP = 'SSP'; // South Sudanese Pound
    case GMD = 'GMD'; // Gambian Dalasi
    case SLL = 'SLL'; // Sierra Leonean Leone
    case LRD = 'LRD'; // Liberian Dollar
    case CVE = 'CVE'; // Cape Verdean Escudo
    case STN = 'STN'; // São Tomé and Príncipe Dobra
    case MGA = 'MGA'; // Malagasy Ariary

    // Oceania
    case FJD = 'FJD'; // Fijian Dollar
    case PGK = 'PGK'; // Papua New Guinean Kina
    case WST = 'WST'; // Samoan Tala
    case TOP = 'TOP'; // Tongan Paʻanga
    case VUV = 'VUV'; // Vanuatu Vatu
    case SBD = 'SBD'; // Solomon Islands Dollar

    // Cryptocurrencies
    case BTC = 'BTC'; // Bitcoin
    case ETH = 'ETH'; // Ethereum
    case USDT = 'USDT'; // Tether
    case USDC = 'USDC'; // USD Coin

    public function getSymbol(): string
    {
        return match ($this) {
            self::USD => '$',
            self::EUR => '€',
            self::GBP => '£',
            self::JPY => '¥',
            self::CHF => 'CHF',
            self::CAD => 'C$',
            self::AUD => 'A$',
            self::NZD => 'NZ$',
            self::CNY => '¥',
            self::INR => '₹',
            self::BRL => 'R$',
            self::ZAR => 'R',
            self::RUB => '₽',
            self::MXN => '$',
            self::SGD => 'S$',
            self::HKD => 'HK$',
            self::NOK => 'kr',
            self::SEK => 'kr',
            self::DKK => 'kr',
            self::PLN => 'zł',
            self::TRY => '₺',
            self::KRW => '₩',
            self::THB => '฿',
            self::IDR => 'Rp',
            self::PHP => '₱',
            self::VND => '₫',
            self::ILS => '₪',
            self::AED => 'د.إ',
            self::SAR => 'ر.س',
            self::QAR => 'ر.ق',
            self::KWD => 'د.ك',
            self::BHD => 'د.ب',
            self::OMR => 'ر.ع.',
            self::JOD => 'د.ا',
            self::CZK => 'Kč',
            self::HUF => 'Ft',
            self::RON => 'lei',
            self::BGN => 'лв',
            self::UAH => '₴',
            self::GEL => '₾',
            self::ARS => '$',
            self::CLP => '$',
            self::COP => '$',
            self::PEN => 'S/',
            self::UYU => '$U',
            self::EGP => 'E£',
            self::NGN => '₦',
            self::KES => 'KSh',
            self::GHS => '₵',
            self::BTC => '₿',
            self::ETH => 'Ξ',
            default => $this->value,
        };
    }

    public function getName(): string
    {
        return match ($this) {
            self::USD => 'US Dollar',
            self::EUR => 'Euro',
            self::GBP => 'British Pound Sterling',
            self::JPY => 'Japanese Yen',
            self::CHF => 'Swiss Franc',
            self::CAD => 'Canadian Dollar',
            self::AUD => 'Australian Dollar',
            self::NZD => 'New Zealand Dollar',
            self::CNY => 'Chinese Yuan',
            self::HKD => 'Hong Kong Dollar',
            self::SGD => 'Singapore Dollar',
            self::KRW => 'South Korean Won',
            self::TWD => 'New Taiwan Dollar',
            self::THB => 'Thai Baht',
            self::MYR => 'Malaysian Ringgit',
            self::IDR => 'Indonesian Rupiah',
            self::PHP => 'Philippine Peso',
            self::VND => 'Vietnamese Dong',
            self::INR => 'Indian Rupee',
            self::PKR => 'Pakistani Rupee',
            self::BDT => 'Bangladeshi Taka',
            self::LKR => 'Sri Lankan Rupee',
            self::NPR => 'Nepalese Rupee',
            self::MMK => 'Myanmar Kyat',
            self::KHR => 'Cambodian Riel',
            self::LAK => 'Lao Kip',
            self::BND => 'Brunei Dollar',
            self::AED => 'UAE Dirham',
            self::SAR => 'Saudi Riyal',
            self::QAR => 'Qatari Riyal',
            self::KWD => 'Kuwaiti Dinar',
            self::BHD => 'Bahraini Dinar',
            self::OMR => 'Omani Rial',
            self::JOD => 'Jordanian Dinar',
            self::ILS => 'Israeli Shekel',
            self::IQD => 'Iraqi Dinar',
            self::LBP => 'Lebanese Pound',
            self::SYP => 'Syrian Pound',
            self::NOK => 'Norwegian Krone',
            self::SEK => 'Swedish Krona',
            self::DKK => 'Danish Krone',
            self::ISK => 'Icelandic Króna',
            self::PLN => 'Polish Zloty',
            self::CZK => 'Czech Koruna',
            self::HUF => 'Hungarian Forint',
            self::RON => 'Romanian Leu',
            self::BGN => 'Bulgarian Lev',
            self::HRK => 'Croatian Kuna',
            self::RSD => 'Serbian Dinar',
            self::TRY => 'Turkish Lira',
            self::RUB => 'Russian Ruble',
            self::UAH => 'Ukrainian Hryvnia',
            self::BYN => 'Belarusian Ruble',
            self::MDL => 'Moldovan Leu',
            self::GEL => 'Georgian Lari',
            self::AMD => 'Armenian Dram',
            self::AZN => 'Azerbaijani Manat',
            self::KZT => 'Kazakhstani Tenge',
            self::UZS => 'Uzbekistani Som',
            self::KGS => 'Kyrgyzstani Som',
            self::TJS => 'Tajikistani Somoni',
            self::TMT => 'Turkmenistani Manat',
            self::MXN => 'Mexican Peso',
            self::BRL => 'Brazilian Real',
            self::ARS => 'Argentine Peso',
            self::CLP => 'Chilean Peso',
            self::COP => 'Colombian Peso',
            self::PEN => 'Peruvian Sol',
            self::VES => 'Venezuelan Bolívar',
            self::UYU => 'Uruguayan Peso',
            self::PYG => 'Paraguayan Guarani',
            self::BOB => 'Bolivian Boliviano',
            self::CRC => 'Costa Rican Colón',
            self::GTQ => 'Guatemalan Quetzal',
            self::HNL => 'Honduran Lempira',
            self::NIO => 'Nicaraguan Córdoba',
            self::PAB => 'Panamanian Balboa',
            self::DOP => 'Dominican Peso',
            self::JMD => 'Jamaican Dollar',
            self::TTD => 'Trinidad and Tobago Dollar',
            self::BBD => 'Barbadian Dollar',
            self::BSD => 'Bahamian Dollar',
            self::BZD => 'Belize Dollar',
            self::XCD => 'East Caribbean Dollar',
            self::AWG => 'Aruban Florin',
            self::ANG => 'Netherlands Antillean Guilder',
            self::SRD => 'Surinamese Dollar',
            self::GYD => 'Guyanese Dollar',
            self::ZAR => 'South African Rand',
            self::NGN => 'Nigerian Naira',
            self::EGP => 'Egyptian Pound',
            self::MAD => 'Moroccan Dirham',
            self::TND => 'Tunisian Dinar',
            self::DZD => 'Algerian Dinar',
            self::LYD => 'Libyan Dinar',
            self::KES => 'Kenyan Shilling',
            self::TZS => 'Tanzanian Shilling',
            self::UGX => 'Ugandan Shilling',
            self::GHS => 'Ghanaian Cedi',
            self::ETB => 'Ethiopian Birr',
            self::ZMW => 'Zambian Kwacha',
            self::MWK => 'Malawian Kwacha',
            self::BWP => 'Botswana Pula',
            self::NAD => 'Namibian Dollar',
            self::MUR => 'Mauritian Rupee',
            self::SCR => 'Seychellois Rupee',
            self::MZN => 'Mozambican Metical',
            self::AOA => 'Angolan Kwanza',
            self::XOF => 'West African CFA Franc',
            self::XAF => 'Central African CFA Franc',
            self::RWF => 'Rwandan Franc',
            self::BIF => 'Burundian Franc',
            self::DJF => 'Djiboutian Franc',
            self::SOS => 'Somali Shilling',
            self::SDG => 'Sudanese Pound',
            self::SSP => 'South Sudanese Pound',
            self::GMD => 'Gambian Dalasi',
            self::SLL => 'Sierra Leonean Leone',
            self::LRD => 'Liberian Dollar',
            self::CVE => 'Cape Verdean Escudo',
            self::STN => 'São Tomé and Príncipe Dobra',
            self::MGA => 'Malagasy Ariary',
            self::FJD => 'Fijian Dollar',
            self::PGK => 'Papua New Guinean Kina',
            self::WST => 'Samoan Tala',
            self::TOP => 'Tongan Paʻanga',
            self::VUV => 'Vanuatu Vatu',
            self::SBD => 'Solomon Islands Dollar',
            self::BTC => 'Bitcoin',
            self::ETH => 'Ethereum',
            self::USDT => 'Tether',
            self::USDC => 'USD Coin',
        };
    }

    public function getDecimalPlaces(): int
    {
        return match ($this) {
            // Zero decimal places
            self::JPY, self::KRW, self::VND, self::CLP, self::PYG,
            self::UGX, self::RWF, self::BIF, self::DJF, self::XOF,
            self::XAF, self::MGA, self::VUV => 0,
            // Three decimal places
            self::BHD, self::IQD, self::JOD, self::KWD, self::OMR, self::TND, self::LYD => 3,
            // Eight decimal places (cryptocurrencies)
            self::BTC, self::ETH, self::USDT, self::USDC => 8,
            // Two decimal places (default)
            default => 2,
        };
    }
}
