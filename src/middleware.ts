import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';
import { COUNTRIES, DEFAULT_COUNTRY, detectCountryFromHeaders } from '@/lib/countries/countries';

// Mapeo de países a idiomas
const countryToLanguage: Record<string, string> = {
  ES: 'es', // España
  MX: 'es', // México
  AR: 'es', // Argentina
  CO: 'es', // Colombia
  CL: 'es', // Chile
  PE: 'es', // Perú
  VE: 'es', // Venezuela
  EC: 'es', // Ecuador
  GT: 'es', // Guatemala
  CU: 'es', // Cuba
  BO: 'es', // Bolivia
  DO: 'es', // República Dominicana
  HN: 'es', // Honduras
  PY: 'es', // Paraguay
  SV: 'es', // El Salvador
  NI: 'es', // Nicaragua
  CR: 'es', // Costa Rica
  PA: 'es', // Panamá
  UY: 'es', // Uruguay

  US: 'en', // Estados Unidos
  GB: 'en', // Reino Unido
  CA: 'en', // Canadá
  AU: 'en', // Australia
  NZ: 'en', // Nueva Zelanda
  IE: 'en', // Irlanda

  FR: 'fr', // Francia
  BE: 'fr', // Bélgica
  CH: 'fr', // Suiza
  LU: 'fr', // Luxemburgo
  MC: 'fr', // Mónaco

  DE: 'de', // Alemania
  AT: 'de', // Austria

  IT: 'it', // Italia

  PT: 'pt', // Portugal
  BR: 'pt', // Brasil

  RU: 'ru', // Rusia

  CN: 'zh', // China
  TW: 'zh', // Taiwán
  HK: 'zh', // Hong Kong

  JP: 'ja', // Japón
  KR: 'ko', // Corea del Sur

  // Por defecto: español
};

export function middleware(request: NextRequest) {
  const { pathname } = request.nextUrl;

  // Detectar país del usuario (IP, headers)
  const detectedCountry = detectCountryFromHeaders(request.headers);

  // Obtener país de la cookie o del detectado
  const savedCountry = request.cookies.get('PASIONES_COUNTRY')?.value || detectedCountry;

  // Verificar si estamos en una ruta con país: /es, /mx, /ar, etc.
  const pathParts = pathname.split('/').filter(Boolean);
  const firstPart = pathParts[0]?.toUpperCase();

  // Lista de rutas que NO necesitan redirección de país
  const excludedPaths = ['/api', '/_next', '/favicon.ico', '/static'];
  const isExcluded = excludedPaths.some(path => pathname.startsWith(path));

  if (isExcluded) {
    return NextResponse.next();
  }

  // Si no hay código de país en la URL, redirigir al país detectado/guardado
  if (!firstPart || firstPart.length !== 2 || !COUNTRIES[firstPart]) {
    const targetCountry = COUNTRIES[savedCountry]?.enabled ? savedCountry : DEFAULT_COUNTRY;
    const countrySlug = targetCountry.toLowerCase();

    // Redirigir a la home del país
    const url = request.nextUrl.clone();
    url.pathname = `/${countrySlug}${pathname === '/' ? '' : pathname}`;

    const response = NextResponse.redirect(url);

    // Guardar país en cookie
    response.cookies.set('PASIONES_COUNTRY', targetCountry, {
      maxAge: 60 * 60 * 24 * 365, // 1 año
      path: '/',
    });

    return response;
  }

  // Si hay código de país válido, continuar normalmente
  const response = NextResponse.next();

  // Actualizar cookie con el país actual
  response.cookies.set('PASIONES_COUNTRY', firstPart, {
    maxAge: 60 * 60 * 24 * 365,
    path: '/',
  });

  // Detectar idioma basado en el país
  const countryConfig = COUNTRIES[firstPart];
  if (countryConfig) {
    response.cookies.set('NEXT_LOCALE', countryConfig.language, {
      maxAge: 60 * 60 * 24 * 365,
      path: '/',
    });

    response.headers.set('X-Country-Code', firstPart);
    response.headers.set('X-Country-Language', countryConfig.language);
    response.headers.set('X-Country-Currency', countryConfig.currency.code);
  }

  return response;
}

export const config = {
  matcher: [
    /*
     * Match all request paths except for the ones starting with:
     * - api (API routes)
     * - _next/static (static files)
     * - _next/image (image optimization files)
     * - favicon.ico (favicon file)
     */
    '/((?!api|_next/static|_next/image|favicon.ico).*)',
  ],
};
