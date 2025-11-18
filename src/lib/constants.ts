import type { Category, Country, Province, MembershipConfig, MembershipType } from '@/types';

// Categorías de modelos
export const CATEGORIES: Category[] = [
  {
    id: '1',
    name: 'Mujeres',
    slug: 'mujeres',
    description: 'Modelos femeninas',
    icon: '👩'
  },
  {
    id: '2',
    name: 'Hombres',
    slug: 'hombres',
    description: 'Modelos masculinos',
    icon: '👨'
  },
  {
    id: '3',
    name: 'Trans',
    slug: 'trans',
    description: 'Modelos trans',
    icon: '⚧️'
  },
];

// Países con provincias
export const COUNTRIES: Record<string, { country: Country; provinces: Province[] }> = {
  'espana': {
    country: { id: 'es', name: 'España', slug: 'espana', code: 'ES' },
    provinces: [
      { id: 'es-1', name: 'Madrid', slug: 'madrid', countryId: 'es' },
      { id: 'es-2', name: 'Barcelona', slug: 'barcelona', countryId: 'es' },
      { id: 'es-3', name: 'Valencia', slug: 'valencia', countryId: 'es' },
      { id: 'es-4', name: 'Sevilla', slug: 'sevilla', countryId: 'es' },
      { id: 'es-5', name: 'Zaragoza', slug: 'zaragoza', countryId: 'es' },
      { id: 'es-6', name: 'Málaga', slug: 'malaga', countryId: 'es' },
      { id: 'es-7', name: 'Murcia', slug: 'murcia', countryId: 'es' },
      { id: 'es-8', name: 'Palma de Mallorca', slug: 'palma', countryId: 'es' },
      { id: 'es-9', name: 'Las Palmas', slug: 'las-palmas', countryId: 'es' },
      { id: 'es-10', name: 'Bilbao', slug: 'bilbao', countryId: 'es' },
    ]
  },
  'portugal': {
    country: { id: 'pt', name: 'Portugal', slug: 'portugal', code: 'PT' },
    provinces: [
      { id: 'pt-1', name: 'Lisboa', slug: 'lisboa', countryId: 'pt' },
      { id: 'pt-2', name: 'Oporto', slug: 'oporto', countryId: 'pt' },
      { id: 'pt-3', name: 'Braga', slug: 'braga', countryId: 'pt' },
      { id: 'pt-4', name: 'Coimbra', slug: 'coimbra', countryId: 'pt' },
      { id: 'pt-5', name: 'Faro', slug: 'faro', countryId: 'pt' },
    ]
  },
  'francia': {
    country: { id: 'fr', name: 'Francia', slug: 'francia', code: 'FR' },
    provinces: [
      { id: 'fr-1', name: 'París', slug: 'paris', countryId: 'fr' },
      { id: 'fr-2', name: 'Marsella', slug: 'marsella', countryId: 'fr' },
      { id: 'fr-3', name: 'Lyon', slug: 'lyon', countryId: 'fr' },
      { id: 'fr-4', name: 'Toulouse', slug: 'toulouse', countryId: 'fr' },
      { id: 'fr-5', name: 'Niza', slug: 'niza', countryId: 'fr' },
      { id: 'fr-6', name: 'Burdeos', slug: 'burdeos', countryId: 'fr' },
    ]
  },
  'alemania': {
    country: { id: 'de', name: 'Alemania', slug: 'alemania', code: 'DE' },
    provinces: [
      { id: 'de-1', name: 'Berlín', slug: 'berlin', countryId: 'de' },
      { id: 'de-2', name: 'Hamburgo', slug: 'hamburgo', countryId: 'de' },
      { id: 'de-3', name: 'Múnich', slug: 'munich', countryId: 'de' },
      { id: 'de-4', name: 'Colonia', slug: 'colonia', countryId: 'de' },
      { id: 'de-5', name: 'Frankfurt', slug: 'frankfurt', countryId: 'de' },
    ]
  },
  'italia': {
    country: { id: 'it', name: 'Italia', slug: 'italia', code: 'IT' },
    provinces: [
      { id: 'it-1', name: 'Roma', slug: 'roma', countryId: 'it' },
      { id: 'it-2', name: 'Milán', slug: 'milan', countryId: 'it' },
      { id: 'it-3', name: 'Nápoles', slug: 'napoles', countryId: 'it' },
      { id: 'it-4', name: 'Turín', slug: 'turin', countryId: 'it' },
      { id: 'it-5', name: 'Florencia', slug: 'florencia', countryId: 'it' },
      { id: 'it-6', name: 'Venecia', slug: 'venecia', countryId: 'it' },
    ]
  },
  'mexico': {
    country: { id: 'mx', name: 'México', slug: 'mexico', code: 'MX' },
    provinces: [
      { id: 'mx-1', name: 'Ciudad de México', slug: 'ciudad-de-mexico', countryId: 'mx' },
      { id: 'mx-2', name: 'Guadalajara', slug: 'guadalajara', countryId: 'mx' },
      { id: 'mx-3', name: 'Monterrey', slug: 'monterrey', countryId: 'mx' },
      { id: 'mx-4', name: 'Puebla', slug: 'puebla', countryId: 'mx' },
      { id: 'mx-5', name: 'Cancún', slug: 'cancun', countryId: 'mx' },
    ]
  },
  'argentina': {
    country: { id: 'ar', name: 'Argentina', slug: 'argentina', code: 'AR' },
    provinces: [
      { id: 'ar-1', name: 'Buenos Aires', slug: 'buenos-aires', countryId: 'ar' },
      { id: 'ar-2', name: 'Córdoba', slug: 'cordoba', countryId: 'ar' },
      { id: 'ar-3', name: 'Rosario', slug: 'rosario', countryId: 'ar' },
      { id: 'ar-4', name: 'Mendoza', slug: 'mendoza', countryId: 'ar' },
      { id: 'ar-5', name: 'La Plata', slug: 'la-plata', countryId: 'ar' },
    ]
  },
  'colombia': {
    country: { id: 'co', name: 'Colombia', slug: 'colombia', code: 'CO' },
    provinces: [
      { id: 'co-1', name: 'Bogotá', slug: 'bogota', countryId: 'co' },
      { id: 'co-2', name: 'Medellín', slug: 'medellin', countryId: 'co' },
      { id: 'co-3', name: 'Cali', slug: 'cali', countryId: 'co' },
      { id: 'co-4', name: 'Barranquilla', slug: 'barranquilla', countryId: 'co' },
      { id: 'co-5', name: 'Cartagena', slug: 'cartagena', countryId: 'co' },
    ]
  },
};

// Configuración de membresías
export const MEMBERSHIP_CONFIG: Record<MembershipType, MembershipConfig> = {
  free: {
    name: 'Gratis',
    price: 0,
    features: {
      postsLimit: 5,
      canPostImages: false,
      canPostVideos: false,
      canVideochat: false,
      canStream: false,
      canReceiveReviews: false,
      canSetSchedule: false,
      visibility: 'basic',
    }
  },
  bronze: {
    name: 'Bronce',
    price: 20,
    features: {
      postsLimit: 50,
      canPostImages: true,
      canPostVideos: true,
      canVideochat: true,
      canStream: false,
      canReceiveReviews: true,
      canSetSchedule: true,
      visibility: 'medium',
    }
  },
  silver: {
    name: 'Plata',
    price: 45,
    features: {
      postsLimit: 200,
      canPostImages: true,
      canPostVideos: true,
      canVideochat: true,
      canStream: true,
      canReceiveReviews: true,
      canSetSchedule: true,
      visibility: 'high',
    }
  },
  gold: {
    name: 'Oro',
    price: 65,
    features: {
      postsLimit: -1,
      canPostImages: true,
      canPostVideos: true,
      canVideochat: true,
      canStream: true,
      canReceiveReviews: true,
      canSetSchedule: true,
      visibility: 'premium',
      featured: true,
      prioritySupport: true,
    }
  },
};

// Moneda
export const CURRENCY = {
  code: 'EUR',
  symbol: '€',
  name: 'Euro',
};

// Nombre de créditos
export const CREDITS_NAME = 'PASIONES';
