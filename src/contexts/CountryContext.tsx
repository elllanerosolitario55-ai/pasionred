'use client';

import { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import { CountryConfig, getCountryByCode, DEFAULT_COUNTRY, COUNTRIES } from '@/lib/countries/countries';
import { usePathname, useRouter } from 'next/navigation';

interface CountryContextType {
  country: CountryConfig;
  countryCode: string;
  setCountry: (countryCode: string) => void;
  availableCountries: CountryConfig[];
}

const CountryContext = createContext<CountryContextType | undefined>(undefined);

export function CountryProvider({ children }: { children: ReactNode }) {
  const pathname = usePathname();
  const router = useRouter();

  // Detectar país de la URL o usar el guardado
  const getInitialCountry = (): string => {
    // Si estamos en una ruta con país: /mx, /ar, /es
    const pathParts = pathname.split('/').filter(Boolean);
    const firstPart = pathParts[0]?.toUpperCase();

    if (firstPart && COUNTRIES[firstPart]?.enabled) {
      return firstPart;
    }

    // Intentar leer de localStorage
    if (typeof window !== 'undefined') {
      const saved = localStorage.getItem('selectedCountry');
      if (saved && COUNTRIES[saved]?.enabled) {
        return saved;
      }
    }

    return DEFAULT_COUNTRY;
  };

  const [countryCode, setCountryCode] = useState<string>(getInitialCountry);
  const country = getCountryByCode(countryCode) || getCountryByCode(DEFAULT_COUNTRY)!;
  const availableCountries = Object.values(COUNTRIES).filter(c => c.enabled);

  // Cambiar país y navegar a la home de ese país
  const handleSetCountry = (newCountryCode: string) => {
    if (!COUNTRIES[newCountryCode]?.enabled) return;

    setCountryCode(newCountryCode);

    // Guardar en localStorage
    if (typeof window !== 'undefined') {
      localStorage.setItem('selectedCountry', newCountryCode);
    }

    // Redirigir a la home del nuevo país
    const slug = COUNTRIES[newCountryCode].slug;
    router.push(`/${newCountryCode.toLowerCase()}`);
  };

  // Actualizar localStorage cuando cambia el país
  useEffect(() => {
    if (typeof window !== 'undefined') {
      localStorage.setItem('selectedCountry', countryCode);
    }
  }, [countryCode]);

  return (
    <CountryContext.Provider
      value={{
        country,
        countryCode,
        setCountry: handleSetCountry,
        availableCountries,
      }}
    >
      {children}
    </CountryContext.Provider>
  );
}

// Hook para usar el contexto
export function useCountry() {
  const context = useContext(CountryContext);
  if (!context) {
    throw new Error('useCountry debe usarse dentro de CountryProvider');
  }
  return context;
}
