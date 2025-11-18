/**
 * Prioridad de membresías para ordenamiento
 * ORO > PLATA > BRONCE > GRATIS
 */
export const MEMBERSHIP_PRIORITY: Record<string, number> = {
  GOLD: 1,
  SILVER: 2,
  BRONZE: 3,
  FREE: 4,
};

/**
 * Ordena un array de profesionales por prioridad de membresía
 * Dentro de cada nivel de membresía, ordena por rating
 */
export function sortByMembershipPriority<T extends { membershipType: string; rating?: number }>(
  professionals: T[]
): T[] {
  return [...professionals].sort((a, b) => {
    const priorityA = MEMBERSHIP_PRIORITY[a.membershipType] || 999;
    const priorityB = MEMBERSHIP_PRIORITY[b.membershipType] || 999;

    // Primero ordenar por prioridad de membresía
    if (priorityA !== priorityB) {
      return priorityA - priorityB;
    }

    // Si tienen la misma membresía, ordenar por rating (mayor a menor)
    const ratingA = a.rating || 0;
    const ratingB = b.rating || 0;
    return ratingB - ratingA;
  });
}

/**
 * Obtiene el label de prioridad para una membresía
 */
export function getMembershipPriorityLabel(membershipType: string): string {
  const labels: Record<string, string> = {
    GOLD: 'Prioridad Alta',
    SILVER: 'Prioridad Media-Alta',
    BRONZE: 'Prioridad Media',
    FREE: 'Prioridad Básica',
  };

  return labels[membershipType] || 'Sin prioridad';
}

/**
 * Filtra y ordena profesionales por provincia/categoría con prioridad de membresía
 */
export function filterAndSortProfessionals<T extends {
  membershipType: string;
  rating?: number;
  provinceId?: string;
  categoryId?: string;
}>(
  professionals: T[],
  filters?: {
    provinceId?: string;
    categoryId?: string;
    countryId?: string;
  }
): T[] {
  let filtered = [...professionals];

  // Aplicar filtros
  if (filters?.provinceId) {
    filtered = filtered.filter(p => p.provinceId === filters.provinceId);
  }

  if (filters?.categoryId) {
    filtered = filtered.filter(p => p.categoryId === filters.categoryId);
  }

  // Ordenar por prioridad de membresía
  return sortByMembershipPriority(filtered);
}
