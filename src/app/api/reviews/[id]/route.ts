import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { getServerSession } from '@/lib/auth';

// GET /api/reviews/[id] - Obtener review por ID
export async function GET(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const review = await prisma.review.findUnique({
      where: { id: params.id },
      include: {
        user: {
          select: {
            id: true,
            name: true,
            image: true,
          },
        },
        professional: {
          include: {
            user: {
              select: {
                id: true,
                name: true,
              },
            },
          },
        },
      },
    });

    if (!review) {
      return NextResponse.json({ error: 'Review no encontrada' }, { status: 404 });
    }

    return NextResponse.json(review);
  } catch (error: any) {
    console.error('Error fetching review:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// PATCH /api/reviews/[id] - Actualizar review
export async function PATCH(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    const existing = await prisma.review.findUnique({
      where: { id: params.id },
    });

    if (!existing) {
      return NextResponse.json({ error: 'Review no encontrada' }, { status: 404 });
    }

    // Solo el autor o un admin puede editar
    if (existing.userId !== session.user.id && session.user.role !== 'ADMIN') {
      return NextResponse.json(
        { error: 'No tienes permiso para editar esta review' },
        { status: 403 }
      );
    }

    const body = await request.json();
    const { rating, comment, status } = body;

    // Validar rating si se proporciona
    if (rating !== undefined && (rating < 1 || rating > 5)) {
      return NextResponse.json(
        { error: 'La calificación debe estar entre 1 y 5' },
        { status: 400 }
      );
    }

    // Solo admin puede cambiar el status
    const updateData: any = {};
    if (rating !== undefined) updateData.rating = rating;
    if (comment !== undefined) updateData.comment = comment;
    if (status !== undefined && session.user.role === 'ADMIN') {
      updateData.status = status.toUpperCase();
    }

    const review = await prisma.review.update({
      where: { id: params.id },
      data: updateData,
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

    // Recalcular estadísticas del profesional
    const stats = await prisma.review.aggregate({
      where: {
        professionalId: existing.professionalId,
        status: 'APPROVED',
      },
      _avg: {
        rating: true,
      },
      _count: true,
    });

    await prisma.professional.update({
      where: { id: existing.professionalId },
      data: {
        rating: stats._avg.rating || 0,
        reviewsCount: stats._count,
      },
    });

    return NextResponse.json(review);
  } catch (error: any) {
    console.error('Error updating review:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// DELETE /api/reviews/[id] - Eliminar review
export async function DELETE(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    const existing = await prisma.review.findUnique({
      where: { id: params.id },
    });

    if (!existing) {
      return NextResponse.json({ error: 'Review no encontrada' }, { status: 404 });
    }

    // Solo el autor o un admin puede eliminar
    if (existing.userId !== session.user.id && session.user.role !== 'ADMIN') {
      return NextResponse.json(
        { error: 'No tienes permiso para eliminar esta review' },
        { status: 403 }
      );
    }

    await prisma.review.delete({
      where: { id: params.id },
    });

    // Recalcular estadísticas del profesional
    const stats = await prisma.review.aggregate({
      where: {
        professionalId: existing.professionalId,
        status: 'APPROVED',
      },
      _avg: {
        rating: true,
      },
      _count: true,
    });

    await prisma.professional.update({
      where: { id: existing.professionalId },
      data: {
        rating: stats._avg.rating || 0,
        reviewsCount: stats._count,
      },
    });

    return NextResponse.json({ message: 'Review eliminada correctamente' });
  } catch (error: any) {
    console.error('Error deleting review:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
