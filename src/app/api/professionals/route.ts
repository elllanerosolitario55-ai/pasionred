import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { getServerSession } from '@/lib/auth';

// GET /api/professionals - Listar profesionales
export async function GET(request: NextRequest) {
  try {
    const searchParams = request.nextUrl.searchParams;

    // Parámetros de filtrado
    const categoryId = searchParams.get('categoryId');
    const countryId = searchParams.get('countryId');
    const provinceId = searchParams.get('provinceId');
    const membershipType = searchParams.get('membershipType');
    const isOnline = searchParams.get('isOnline');
    const isVerified = searchParams.get('isVerified');
    const search = searchParams.get('search');

    // Parámetros de paginación
    const page = parseInt(searchParams.get('page') || '1');
    const limit = parseInt(searchParams.get('limit') || '12');
    const skip = (page - 1) * limit;

    // Parámetros de ordenamiento
    const sortBy = searchParams.get('sortBy') || 'membership'; // Por defecto ordenar por membresía
    const sortOrder = searchParams.get('sortOrder') || 'desc';

    // Construir filtros
    const where: any = {};

    if (categoryId) {
      where.categoryId = categoryId;
    }

    if (countryId) {
      where.countryId = countryId;
    }

    if (provinceId) {
      where.provinceId = provinceId;
    }

    if (membershipType) {
      where.membershipType = membershipType.toUpperCase();
    }

    if (isOnline === 'true') {
      where.isOnline = true;
    }

    if (isVerified === 'true') {
      where.isVerified = true;
    }

    if (search) {
      where.OR = [
        { user: { name: { contains: search, mode: 'insensitive' } } },
        { bio: { contains: search, mode: 'insensitive' } },
      ];
    }

    // Obtener profesionales con ordenamiento personalizado
    let professionals;

    if (sortBy === 'membership' || sortBy === 'priority') {
      // Ordenamiento por prioridad de membresía: ORO > PLATA > BRONCE > GRATIS
      // Usamos raw SQL para ordenamiento personalizado
      const allProfessionals = await prisma.professional.findMany({
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
          membership: {
            select: {
              type: true,
              status: true,
              endDate: true,
            },
          },
        },
      });

      // Ordenar manualmente por prioridad de membresía
      const membershipPriority: Record<string, number> = {
        'GOLD': 1,
        'SILVER': 2,
        'BRONZE': 3,
        'FREE': 4,
      };

      allProfessionals.sort((a, b) => {
        const priorityA = membershipPriority[a.membershipType] || 999;
        const priorityB = membershipPriority[b.membershipType] || 999;

        if (priorityA !== priorityB) {
          return priorityA - priorityB;
        }

        // Si tienen la misma membresía, ordenar por rating
        return (b.rating || 0) - (a.rating || 0);
      });

      // Aplicar paginación manualmente
      professionals = allProfessionals.slice(skip, skip + limit);

    } else {
      // Ordenamiento normal por el campo especificado
      const orderBy: any = {};
      orderBy[sortBy] = sortOrder;

      professionals = await prisma.professional.findMany({
        where,
        skip,
        take: limit,
        orderBy,
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
          membership: {
            select: {
              type: true,
              status: true,
              endDate: true,
            },
          },
        },
      });
    }

    const total = await prisma.professional.count({ where });

    return NextResponse.json({
      professionals,
      pagination: {
        page,
        limit,
        total,
        pages: Math.ceil(total / limit),
      },
    });
  } catch (error: any) {
    console.error('Error fetching professionals:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// POST /api/professionals - Crear profesional
export async function POST(request: NextRequest) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    const body = await request.json();
    const {
      bio,
      categoryId,
      countryId,
      provinceId,
      costPerMinute = 2.5,
      avatar,
      coverImage,
    } = body;

    // Validaciones
    if (!categoryId || !countryId || !provinceId) {
      return NextResponse.json(
        { error: 'Categoría, país y provincia son requeridos' },
        { status: 400 }
      );
    }

    // Verificar que el usuario no tenga ya un perfil profesional
    const existing = await prisma.professional.findUnique({
      where: { userId: session.user.id },
    });

    if (existing) {
      return NextResponse.json(
        { error: 'Ya tienes un perfil profesional' },
        { status: 400 }
      );
    }

    // Crear profesional
    const professional = await prisma.professional.create({
      data: {
        userId: session.user.id,
        bio,
        categoryId,
        countryId,
        provinceId,
        costPerMinute,
        avatar,
        coverImage,
        membershipType: 'FREE', // Por defecto gratis
      },
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

    // Actualizar rol del usuario
    await prisma.user.update({
      where: { id: session.user.id },
      data: { role: 'PROFESSIONAL' },
    });

    // Crear membresía gratuita
    await prisma.membership.create({
      data: {
        professionalId: professional.id,
        type: 'FREE',
        startDate: new Date(),
        endDate: new Date(Date.now() + 365 * 24 * 60 * 60 * 1000), // 1 año
        status: 'ACTIVE',
      },
    });

    return NextResponse.json(professional, { status: 201 });
  } catch (error: any) {
    console.error('Error creating professional:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
