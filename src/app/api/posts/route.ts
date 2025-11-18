import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { getServerSession } from '@/lib/auth';

// GET /api/posts - Listar posts
export async function GET(request: NextRequest) {
  try {
    const searchParams = request.nextUrl.searchParams;

    // Parámetros de filtrado
    const professionalId = searchParams.get('professionalId');
    const categoryId = searchParams.get('categoryId');
    const isPaid = searchParams.get('isPaid');

    // Parámetros de paginación
    const page = parseInt(searchParams.get('page') || '1');
    const limit = parseInt(searchParams.get('limit') || '20');
    const skip = (page - 1) * limit;

    // Construir filtros
    const where: any = {
      isPublished: true,
    };

    if (professionalId) {
      where.professionalId = professionalId;
    }

    if (categoryId) {
      where.categoryId = categoryId;
    }

    if (isPaid !== null) {
      where.isPaid = isPaid === 'true';
    }

    // Consulta con paginación
    const [posts, total] = await Promise.all([
      prisma.post.findMany({
        where,
        skip,
        take: limit,
        orderBy: { createdAt: 'desc' },
        include: {
          professional: {
            include: {
              user: {
                select: {
                  id: true,
                  name: true,
                  image: true,
                },
              },
              membership: {
                select: {
                  type: true,
                },
              },
            },
          },
          category: true,
        },
      }),
      prisma.post.count({ where }),
    ]);

    return NextResponse.json({
      posts,
      pagination: {
        page,
        limit,
        total,
        pages: Math.ceil(total / limit),
      },
    });
  } catch (error: any) {
    console.error('Error fetching posts:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// POST /api/posts - Crear post
export async function POST(request: NextRequest) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    // Verificar que el usuario sea profesional
    const professional = await prisma.professional.findUnique({
      where: { userId: session.user.id },
      include: {
        membership: true,
      },
    });

    if (!professional) {
      return NextResponse.json(
        { error: 'Debes ser un profesional para crear posts' },
        { status: 403 }
      );
    }

    const body = await request.json();
    const {
      content,
      images = [],
      videos = [],
      isPaid = false,
      price,
      categoryId,
    } = body;

    // Validaciones
    if (!content || content.trim().length === 0) {
      return NextResponse.json(
        { error: 'El contenido es requerido' },
        { status: 400 }
      );
    }

    if (isPaid && (!price || price <= 0)) {
      return NextResponse.json(
        { error: 'El precio es requerido para contenido de pago' },
        { status: 400 }
      );
    }

    // Verificar límites de membresía
    const membershipType = professional.membershipType;
    const config = await getMembershipConfig(membershipType);

    if (config.features.postsLimit !== -1) {
      // Contar posts del mes actual
      const startOfMonth = new Date();
      startOfMonth.setDate(1);
      startOfMonth.setHours(0, 0, 0, 0);

      const postsThisMonth = await prisma.post.count({
        where: {
          professionalId: professional.id,
          createdAt: { gte: startOfMonth },
        },
      });

      if (postsThisMonth >= config.features.postsLimit) {
        return NextResponse.json(
          {
            error: `Has alcanzado el límite de ${config.features.postsLimit} posts para tu membresía ${membershipType}`,
          },
          { status: 403 }
        );
      }
    }

    // Verificar permisos de contenido multimedia
    if (images.length > 0 && !config.features.canPostImages) {
      return NextResponse.json(
        { error: 'Tu membresía no permite publicar imágenes' },
        { status: 403 }
      );
    }

    if (videos.length > 0 && !config.features.canPostVideos) {
      return NextResponse.json(
        { error: 'Tu membresía no permite publicar videos' },
        { status: 403 }
      );
    }

    // Crear post
    const post = await prisma.post.create({
      data: {
        professionalId: professional.id,
        content,
        images,
        videos,
        isPaid,
        price,
        categoryId,
        isPublished: true,
      },
      include: {
        professional: {
          include: {
            user: {
              select: {
                id: true,
                name: true,
                image: true,
              },
            },
          },
        },
        category: true,
      },
    });

    return NextResponse.json(post, { status: 201 });
  } catch (error: any) {
    console.error('Error creating post:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// Helper para obtener configuración de membresía
async function getMembershipConfig(type: string) {
  const config: Record<string, any> = {
    FREE: {
      features: {
        postsLimit: 5,
        canPostImages: false,
        canPostVideos: false,
      },
    },
    BRONZE: {
      features: {
        postsLimit: 50,
        canPostImages: true,
        canPostVideos: true,
      },
    },
    SILVER: {
      features: {
        postsLimit: 200,
        canPostImages: true,
        canPostVideos: true,
      },
    },
    GOLD: {
      features: {
        postsLimit: -1, // ilimitado
        canPostImages: true,
        canPostVideos: true,
      },
    },
  };

  return config[type] || config.FREE;
}
