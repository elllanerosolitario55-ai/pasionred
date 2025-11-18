'use client';

import { useState, useEffect } from 'react';
import { sortByMembershipPriority } from '@/lib/utils/prioritySort';

interface Professional {
  id: string;
  membershipType: string;
  rating: number;
  [key: string]: any;
}

interface UseProfessionalsOptions {
  categoryId?: string;
  countryId?: string;
  provinceId?: string;
  isOnline?: boolean;
  limit?: number;
  sortBy?: 'membership' | 'rating' | 'createdAt';
}

export function useProfessionals(options: UseProfessionalsOptions = {}) {
  const [professionals, setProfessionals] = useState<Professional[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    async function fetchProfessionals() {
      try {
        setLoading(true);
        setError(null);

        // Construir query params
        const params = new URLSearchParams();

        if (options.categoryId) params.append('categoryId', options.categoryId);
        if (options.countryId) params.append('countryId', options.countryId);
        if (options.provinceId) params.append('provinceId', options.provinceId);
        if (options.isOnline) params.append('isOnline', 'true');
        if (options.limit) params.append('limit', options.limit.toString());

        // Por defecto ordenar por membresía
        params.append('sortBy', options.sortBy || 'membership');

        const response = await fetch(`/api/professionals?${params.toString()}`);

        if (!response.ok) {
          throw new Error('Error al cargar profesionales');
        }

        const data = await response.json();

        // Si el ordenamiento es por membresía, ya viene ordenado del servidor
        // pero podemos re-ordenar en el cliente para asegurar
        if (options.sortBy === 'membership' || !options.sortBy) {
          setProfessionals(sortByMembershipPriority(data.professionals));
        } else {
          setProfessionals(data.professionals);
        }

      } catch (err) {
        setError(err instanceof Error ? err.message : 'Error desconocido');
      } finally {
        setLoading(false);
      }
    }

    fetchProfessionals();
  }, [
    options.categoryId,
    options.countryId,
    options.provinceId,
    options.isOnline,
    options.limit,
    options.sortBy,
  ]);

  return { professionals, loading, error };
}
