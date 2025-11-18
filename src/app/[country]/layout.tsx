import { CountryProvider } from '@/contexts/CountryContext';
import { getCountryByCode, COUNTRIES } from '@/lib/countries/countries';
import { notFound } from 'next/navigation';
import type { Metadata } from 'next';

interface CountryLayoutProps {
  children: React.ReactNode;
  params: { country: string };
}

// Generar metadata dinámicamente por país
export async function generateMetadata({ params }: CountryLayoutProps): Promise<Metadata> {
  const countryCode = params.country.toUpperCase();
  const country = getCountryByCode(countryCode);

  if (!country) {
    return {
      title: 'País no encontrado',
    };
  }

  return {
    title: `Pasiones ${country.name} - Conecta con Profesionales`,
    description: `Plataforma de videochat y streaming con profesionales verificados en ${country.name}. Chat en vivo, contenido exclusivo y mucho más.`,
    keywords: `profesionales ${country.name}, videochat ${country.name}, streaming, modelos verificados`,
  };
}

// Generar rutas estáticas para todos los países habilitados
export function generateStaticParams() {
  return Object.values(COUNTRIES)
    .filter(c => c.enabled)
    .map(c => ({
      country: c.code.toLowerCase(),
    }));
}

export default function CountryLayout({ children, params }: CountryLayoutProps) {
  const countryCode = params.country.toUpperCase();
  const country = getCountryByCode(countryCode);

  // Si el país no existe o no está habilitado, mostrar 404
  if (!country || !country.enabled) {
    notFound();
  }

  return (
    <CountryProvider>
      <div data-country={countryCode} data-language={country.language}>
        {children}
      </div>
    </CountryProvider>
  );
}
