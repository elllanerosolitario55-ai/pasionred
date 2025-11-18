import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { getServerSession } from '@/lib/auth';

// GET /api/reviews - Listar reviews
export async function GET(request: NextRequest) {
  try {
    const searchParams = request.nextUrl.searchParams;

    // Parámetros de filtrado
    const professionalId = searchParams.get('professionalId');
    const userId = searchParams.get('userId');
    const rating = searchParams.get('rating');
    const status = searchParams.get('status') || 'APPROVED';

    // Parámetros de paginación
    const page = parseInt(searchParams.get('page') || '1');
    const limit = parseInt(searchParams.get('limit') || '10');
    const skip = (page - 1) * limit;

    // Construir filtros
    const where: any = {
      status: status.toUpperCase(),
    };

    if (professionalId) {
      where.professionalId = professionalId;
    }

    if (userId) {
      where.userId = userId;
    }

    if (rating) {
      where.rating = parseInt(rating);
    }

    // Consulta con paginación
    const [reviews, total] = await Promise.all([
      prisma.review.findMany({
        where,
        skip,
        take: limit,
        orderBy: { createdAt: 'desc' },
        include: {
          user: {
            select: {
              id: true,
              name: true,
              image: true,
            },
          },
          professional: {
            select: {
              id: true,
              user: {
                select: {
                  id: true,
                  name: true,
                },
              },
            },
          },
        },
      }),
      prisma.review.count({ where }),
    ]);

    return NextResponse.json({
      reviews,
      pagination: {
        page,
        limit,
        total,
        pages: Math.ceil(total / limit),
      },
    });
  } catch (error: any) {
    console.error('Error fetching reviews:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// POST /api/reviews - Crear review
export async function POST(request: NextRequest) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    const body = await request.json();
    const { professionalId, rating, comment, sessionId } = body;

    // Validaciones
    if (!professionalId) {
      return NextResponse.json(
        { error: 'ID del profesional es requerido' },
        { status: 400 }
      );
    }

    if (!rating || rating < 1 || rating > 5) {
      return NextResponse.json(
        { error: 'La calificación debe estar entre 1 y 5' },
        { status: 400 }
      );
    }

    // Verificar que el profesional exista
    const professional = await prisma.professional.findUnique({
      where: { id: professionalId },
    });

    if (!professional) {
      return NextResponse.json(
        { error: 'Profesional no encontrado' },
        { status: 404 }
      );
    }

    // Verificar que el usuario no sea el mismo profesional
    if (professional.userId === session.user.id) {
      return NextResponse.json(
        { error: 'No puedes valorarte a ti mismo' },
        { status: 400 }
      );
    }

    // Verificar que no haya ya una review del mismo usuario para el mismo profesional y sesión
    if (sessionId) {
      const existingReview = await prisma.review.findUnique({
        where: {
          professionalId_userId_sessionId: {
            professionalId,
            userId: session.user.id,
            sessionId,
          },
        },
      });

      if (existingReview) {
        return NextResponse.json(
          { error: 'Ya has valorado esta sesión' },
          { status: 400 }
        );
      }
    }

    // Crear review
    const review = await prisma.review.create({
      data: {
        professionalId,
        userId: session.user.id,
        rating,
        comment: comment || null,
        sessionId: sessionId || null,
        status: 'APPROVED', // Auto-aprobar (puedes cambiar a PENDING)
      },
      include: {
        user: {
          select: {
            id: true,
            name: true,
            image: true,
          },
        },
      },
    });

    // Actualizar estadísticas del profesional
    const stats = await prisma.review.aggregate({
      where: {
        professionalId,
        status: 'APPROVED',
      },
      _avg: {
        rating: true,
      },
      _count: true,
    });

    await prisma.professional.update({
      where: { id: professionalId },
      data: {
        rating: stats._avg.rating || 0,
        reviewsCount: stats._count,
      },
    });

    // Crear notificación para el profesional
    await prisma.notification.create({
      data: {
        userId: professional.userId,
        type: 'new_review',
        title: 'Nueva reseña recibida',
        message: `${session.user.name} te ha dado ${rating} estrellas`,
        link: `/profesional/${professionalId}`,
      },
    });

    return NextResponse.json(review, { status: 201 });
  } catch (error: any) {
    console.error('Error creating review:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
