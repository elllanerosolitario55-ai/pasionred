/**
 * Mock data para desarrollo sin base de datos
 * Usado cuando Prisma no puede conectarse a la BD
 */

export const mockProfessionals = [
  {
    id: '1',
    userId: 'user-1',
    bio: 'Psicóloga especializada en terapia cognitivo-conductual con más de 10 años de experiencia',
    categoryId: 'psicologo',
    countryId: 'ES',
    provinceId: 'madrid',
    costPerMinute: 2.5,
    avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400',
    coverImage: 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?w=1200',
    membershipType: 'GOLD',
    rating: 4.9,
    reviewsCount: 243,
    isOnline: true,
    isVerified: true,
    user: {
      id: 'user-1',
      name: 'María García',
      email: 'maria@example.com',
      image: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400',
    },
    category: {
      id: 'psicologo',
      name: 'Psicólogos',
      slug: 'psicologo',
    },
    country: {
      id: 'ES',
      name: 'España',
      code: 'ES',
    },
    province: {
      id: 'madrid',
      name: 'Madrid',
      countryId: 'ES',
    },
  },
  {
    id: '2',
    userId: 'user-2',
    bio: 'Coach de vida y desarrollo personal certificado internacionalmente',
    categoryId: 'coach',
    countryId: 'ES',
    provinceId: 'barcelona',
    costPerMinute: 3.0,
    avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400',
    coverImage: 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1200',
    membershipType: 'SILVER',
    rating: 4.7,
    reviewsCount: 156,
    isOnline: true,
    isVerified: true,
    user: {
      id: 'user-2',
      name: 'Carlos Ruiz',
      email: 'carlos@example.com',
      image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400',
    },
    category: {
      id: 'coach',
      name: 'Coaches',
      slug: 'coach',
    },
    country: {
      id: 'ES',
      name: 'España',
      code: 'ES',
    },
    province: {
      id: 'barcelona',
      name: 'Barcelona',
      countryId: 'ES',
    },
  },
  {
    id: '3',
    userId: 'user-3',
    bio: 'Nutricionista deportiva especializada en alto rendimiento',
    categoryId: 'nutricionista',
    countryId: 'MX',
    provinceId: 'cdmx',
    costPerMinute: 2.0,
    avatar: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400',
    coverImage: 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=1200',
    membershipType: 'BRONZE',
    rating: 4.8,
    reviewsCount: 89,
    isOnline: false,
    isVerified: true,
    user: {
      id: 'user-3',
      name: 'Ana López',
      email: 'ana@example.com',
      image: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400',
    },
    category: {
      id: 'nutricionista',
      name: 'Nutricionistas',
      slug: 'nutricionista',
    },
    country: {
      id: 'MX',
      name: 'México',
      code: 'MX',
    },
    province: {
      id: 'cdmx',
      name: 'Ciudad de México',
      countryId: 'MX',
    },
  },
  {
    id: '4',
    userId: 'user-4',
    bio: 'Abogado especializado en derecho laboral y empresarial',
    categoryId: 'abogado',
    countryId: 'ES',
    provinceId: 'madrid',
    costPerMinute: 4.0,
    avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400',
    coverImage: 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=1200',
    membershipType: 'FREE',
    rating: 4.5,
    reviewsCount: 45,
    isOnline: false,
    isVerified: false,
    user: {
      id: 'user-4',
      name: 'Juan Pérez',
      email: 'juan@example.com',
      image: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400',
    },
    category: {
      id: 'abogado',
      name: 'Abogados',
      slug: 'abogado',
    },
    country: {
      id: 'ES',
      name: 'España',
      code: 'ES',
    },
    province: {
      id: 'madrid',
      name: 'Madrid',
      countryId: 'ES',
    },
  },
];

export const mockCategories = [
  { id: 'psicologo', name: 'Psicólogos', slug: 'psicologo', description: 'Profesionales de la salud mental' },
  { id: 'coach', name: 'Coaches', slug: 'coach', description: 'Desarrollo personal y profesional' },
  { id: 'nutricionista', name: 'Nutricionistas', slug: 'nutricionista', description: 'Alimentación y salud' },
  { id: 'abogado', name: 'Abogados', slug: 'abogado', description: 'Asesoría legal' },
  { id: 'medico', name: 'Médicos', slug: 'medico', description: 'Consultas médicas' },
];

export const mockCountries = [
  { id: 'ES', name: 'España', code: 'ES' },
  { id: 'MX', name: 'México', code: 'MX' },
  { id: 'AR', name: 'Argentina', code: 'AR' },
  { id: 'CO', name: 'Colombia', code: 'CO' },
];

export const mockProvinces = [
  { id: 'madrid', name: 'Madrid', countryId: 'ES' },
  { id: 'barcelona', name: 'Barcelona', countryId: 'ES' },
  { id: 'cdmx', name: 'Ciudad de México', countryId: 'MX' },
  { id: 'buenos-aires', name: 'Buenos Aires', countryId: 'AR' },
];

/**
 * Verificar si Prisma está disponible
 */
export async function isPrismaAvailable(): Promise<boolean> {
  try {
    const { prisma } = await import('./prisma');
    await prisma.$connect();
    await prisma.$disconnect();
    return true;
  } catch (error) {
    console.warn('Prisma no disponible, usando datos mock');
    return false;
  }
}

/**
 * Obtener profesionales (mock o real)
 */
export async function getProfessionals(filters?: {
  countryId?: string;
  provinceId?: string;
  categoryId?: string;
}) {
  const useMock = !(await isPrismaAvailable());

  if (useMock) {
    let professionals = [...mockProfessionals];

    if (filters?.countryId) {
      professionals = professionals.filter(p => p.countryId === filters.countryId);
    }

    if (filters?.provinceId) {
      professionals = professionals.filter(p => p.provinceId === filters.provinceId);
    }

    if (filters?.categoryId) {
      professionals = professionals.filter(p => p.categoryId === filters.categoryId);
    }

    // Ordenar por membresía
    const priority: Record<string, number> = {
      GOLD: 1,
      SILVER: 2,
      BRONZE: 3,
      FREE: 4,
    };

    professionals.sort((a, b) => {
      const priorityA = priority[a.membershipType] || 999;
      const priorityB = priority[b.membershipType] || 999;

      if (priorityA !== priorityB) {
        return priorityA - priorityB;
      }

      return (b.rating || 0) - (a.rating || 0);
    });

    return professionals;
  }

  // Código real con Prisma aquí
  const { prisma } = await import('./prisma');

  const where: any = {};
  if (filters?.countryId) where.countryId = filters.countryId;
  if (filters?.provinceId) where.provinceId = filters.provinceId;
  if (filters?.categoryId) where.categoryId = filters.categoryId;

  const professionals = await prisma.professional.findMany({
    where,
    include: {
      user: {
        select: {
          id: true,
          name: true,
          email: true,
          image: true,
        },
      },
      category: true,
      country: true,
      province: true,
    },
  });

  return professionals;
}
