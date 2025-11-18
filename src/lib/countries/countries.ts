/**
 * Configuración de países soportados por la plataforma
 * Cada país tiene su home, idioma, provincias y profesionales exclusivos
 */

export interface CountryConfig {
  code: string; // Código ISO del país (ES, MX, AR, etc.)
  slug: string; // Slug para URLs (espana, mexico, argentina)
  name: string; // Nombre del país
  flag: string; // Emoji de bandera
  language: string; // Código de idioma (es, pt, en, fr)
  currency: {
    code: string; // EUR, USD, MXN, ARS
    symbol: string; // €, $, etc.
    position: 'before' | 'after';
  };
  timezone: string; // Europe/Madrid, America/Mexico_City
  phonePrefix: string; // +34, +52, +54
  enabled: boolean; // Si está activo en la plataforma
}

export const COUNTRIES: Record<string, CountryConfig> = {
  // Europa
  ES: {
    code: 'ES',
    slug: 'espana',
    name: 'España',
    flag: '🇪🇸',
    language: 'es',
    currency: { code: 'EUR', symbol: '€', position: 'after' },
    timezone: 'Europe/Madrid',
    phonePrefix: '+34',
    enabled: true,
  },
  PT: {
    code: 'PT',
    slug: 'portugal',
    name: 'Portugal',
    flag: '🇵🇹',
    language: 'pt',
    currency: { code: 'EUR', symbol: '€', position: 'after' },
    timezone: 'Europe/Lisbon',
    phonePrefix: '+351',
    enabled: true,
  },
  FR: {
    code: 'FR',
    slug: 'francia',
    name: 'Francia',
    flag: '🇫🇷',
    language: 'fr',
    currency: { code: 'EUR', symbol: '€', position: 'after' },
    timezone: 'Europe/Paris',
    phonePrefix: '+33',
    enabled: true,
  },
  DE: {
    code: 'DE',
    slug: 'alemania',
    name: 'Alemania',
    flag: '🇩🇪',
    language: 'de',
    currency: { code: 'EUR', symbol: '€', position: 'after' },
    timezone: 'Europe/Berlin',
    phonePrefix: '+49',
    enabled: true,
  },
  IT: {
    code: 'IT',
    slug: 'italia',
    name: 'Italia',
    flag: '🇮🇹',
    language: 'it',
    currency: { code: 'EUR', symbol: '€', position: 'after' },
    timezone: 'Europe/Rome',
    phonePrefix: '+39',
    enabled: true,
  },
  GB: {
    code: 'GB',
    slug: 'reino-unido',
    name: 'Reino Unido',
    flag: '🇬🇧',
    language: 'en',
    currency: { code: 'GBP', symbol: '£', position: 'before' },
    timezone: 'Europe/London',
    phonePrefix: '+44',
    enabled: true,
  },

  // América Latina
  MX: {
    code: 'MX',
    slug: 'mexico',
    name: 'México',
    flag: '🇲🇽',
    language: 'es',
    currency: { code: 'MXN', symbol: '$', position: 'before' },
    timezone: 'America/Mexico_City',
    phonePrefix: '+52',
    enabled: true,
  },
  AR: {
    code: 'AR',
    slug: 'argentina',
    name: 'Argentina',
    flag: '🇦🇷',
    language: 'es',
    currency: { code: 'ARS', symbol: '$', position: 'before' },
    timezone: 'America/Argentina/Buenos_Aires',
    phonePrefix: '+54',
    enabled: true,
  },
  CO: {
    code: 'CO',
    slug: 'colombia',
    name: 'Colombia',
    flag: '🇨🇴',
    language: 'es',
    currency: { code: 'COP', symbol: '$', position: 'before' },
    timezone: 'America/Bogota',
    phonePrefix: '+57',
    enabled: true,
  },
  CL: {
    code: 'CL',
    slug: 'chile',
    name: 'Chile',
    flag: '🇨🇱',
    language: 'es',
    currency: { code: 'CLP', symbol: '$', position: 'before' },
    timezone: 'America/Santiago',
    phonePrefix: '+56',
    enabled: true,
  },
  PE: {
    code: 'PE',
    slug: 'peru',
    name: 'Perú',
    flag: '🇵🇪',
    language: 'es',
    currency: { code: 'PEN', symbol: 'S/', position: 'before' },
    timezone: 'America/Lima',
    phonePrefix: '+51',
    enabled: true,
  },
  VE: {
    code: 'VE',
    slug: 'venezuela',
    name: 'Venezuela',
    flag: '🇻🇪',
    language: 'es',
    currency: { code: 'USD', symbol: '$', position: 'before' },
    timezone: 'America/Caracas',
    phonePrefix: '+58',
    enabled: true,
  },
  UY: {
    code: 'UY',
    slug: 'uruguay',
    name: 'Uruguay',
    flag: '🇺🇾',
    language: 'es',
    currency: { code: 'UYU', symbol: '$', position: 'before' },
    timezone: 'America/Montevideo',
    phonePrefix: '+598',
    enabled: true,
  },
  PY: {
    code: 'PY',
    slug: 'paraguay',
    name: 'Paraguay',
    flag: '🇵🇾',
    language: 'es',
    currency: { code: 'PYG', symbol: '₲', position: 'before' },
    timezone: 'America/Asuncion',
    phonePrefix: '+595',
    enabled: true,
  },
  BR: {
    code: 'BR',
    slug: 'brasil',
    name: 'Brasil',
    flag: '🇧🇷',
    language: 'pt',
    currency: { code: 'BRL', symbol: 'R$', position: 'before' },
    timezone: 'America/Sao_Paulo',
    phonePrefix: '+55',
    enabled: true,
  },

  // América del Norte
  US: {
    code: 'US',
    slug: 'estados-unidos',
    name: 'Estados Unidos',
    flag: '🇺🇸',
    language: 'en',
    currency: { code: 'USD', symbol: '$', position: 'before' },
    timezone: 'America/New_York',
    phonePrefix: '+1',
    enabled: true,
  },
  CA: {
    code: 'CA',
    slug: 'canada',
    name: 'Canadá',
    flag: '🇨🇦',
    language: 'en',
    currency: { code: 'CAD', symbol: '$', position: 'before' },
    timezone: 'America/Toronto',
    phonePrefix: '+1',
    enabled: true,
  },
};

// País por defecto (España)
export const DEFAULT_COUNTRY = 'ES';

// Obtener país por slug
export function getCountryBySlug(slug: string): CountryConfig | null {
  const country = Object.values(COUNTRIES).find(c => c.slug === slug);
  return country || null;
}

// Obtener país por código
export function getCountryByCode(code: string): CountryConfig | null {
  return COUNTRIES[code] || null;
}

// Obtener países habilitados
export function getEnabledCountries(): CountryConfig[] {
  return Object.values(COUNTRIES).filter(c => c.enabled);
}

// Obtener países por idioma
export function getCountriesByLanguage(language: string): CountryConfig[] {
  return Object.values(COUNTRIES).filter(c => c.language === language && c.enabled);
}

// Detectar país por IP (usando headers de Vercel/Netlify)
export function detectCountryFromHeaders(headers: Headers): string {
  // Vercel proporciona x-vercel-ip-country
  const vercelCountry = headers.get('x-vercel-ip-country');
  if (vercelCountry && COUNTRIES[vercelCountry]?.enabled) {
    return vercelCountry;
  }

  // Cloudflare proporciona cf-ipcountry
  const cfCountry = headers.get('cf-ipcountry');
  if (cfCountry && COUNTRIES[cfCountry]?.enabled) {
    return cfCountry;
  }

  return DEFAULT_COUNTRY;
}

// Formatear precio según país
export function formatPrice(amount: number, countryCode: string): string {
  const country = getCountryByCode(countryCode);
  if (!country) return `${amount}`;

  const { symbol, position } = country.currency;
  const formatted = amount.toFixed(2);

  return position === 'before' ? `${symbol}${formatted}` : `${formatted}${symbol}`;
}
