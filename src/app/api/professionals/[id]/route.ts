import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { getServerSession } from '@/lib/auth';

// GET /api/professionals/[id] - Obtener profesional por ID
export async function GET(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const professional = await prisma.professional.findUnique({
      where: { id: params.id },
      include: {
        user: {
          select: {
            id: true,
            name: true,
            email: true,
            image: true,
            createdAt: true,
          },
        },
        category: true,
        country: true,
        province: true,
        membership: {
          select: {
            type: true,
            status: true,
            startDate: true,
            endDate: true,
            autoRenew: true,
          },
        },
        posts: {
          take: 10,
          orderBy: { createdAt: 'desc' },
          where: { isPublished: true },
        },
        reviews: {
          take: 10,
          orderBy: { createdAt: 'desc' },
          where: { status: 'APPROVED' },
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
        availability: true,
      },
    });

    if (!professional) {
      return NextResponse.json(
        { error: 'Profesional no encontrado' },
        { status: 404 }
      );
    }

    return NextResponse.json(professional);
  } catch (error: any) {
    console.error('Error fetching professional:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// PATCH /api/professionals/[id] - Actualizar profesional
export async function PATCH(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    // Verificar que el profesional pertenezca al usuario
    const existing = await prisma.professional.findUnique({
      where: { id: params.id },
    });

    if (!existing) {
      return NextResponse.json(
        { error: 'Profesional no encontrado' },
        { status: 404 }
      );
    }

    if (existing.userId !== session.user.id && session.user.role !== 'ADMIN') {
      return NextResponse.json(
        { error: 'No tienes permiso para editar este perfil' },
        { status: 403 }
      );
    }

    const body = await request.json();
    const {
      bio,
      categoryId,
      countryId,
      provinceId,
      costPerMinute,
      avatar,
      coverImage,
      isOnline,
    } = body;

    // Actualizar profesional
    const professional = await prisma.professional.update({
      where: { id: params.id },
      data: {
        ...(bio !== undefined && { bio }),
        ...(categoryId !== undefined && { categoryId }),
        ...(countryId !== undefined && { countryId }),
        ...(provinceId !== undefined && { provinceId }),
        ...(costPerMinute !== undefined && { costPerMinute }),
        ...(avatar !== undefined && { avatar }),
        ...(coverImage !== undefined && { coverImage }),
        ...(isOnline !== undefined && { isOnline }),
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
        membership: true,
      },
    });

    return NextResponse.json(professional);
  } catch (error: any) {
    console.error('Error updating professional:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// DELETE /api/professionals/[id] - Eliminar profesional
export async function DELETE(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    // Verificar que el profesional pertenezca al usuario o sea admin
    const existing = await prisma.professional.findUnique({
      where: { id: params.id },
    });

    if (!existing) {
      return NextResponse.json(
        { error: 'Profesional no encontrado' },
        { status: 404 }
      );
    }

    if (existing.userId !== session.user.id && session.user.role !== 'ADMIN') {
      return NextResponse.json(
        { error: 'No tienes permiso para eliminar este perfil' },
        { status: 403 }
      );
    }

    // Eliminar profesional (cascade eliminará memberships, posts, etc.)
    await prisma.professional.delete({
      where: { id: params.id },
    });

    // Actualizar rol del usuario de vuelta a USER
    await prisma.user.update({
      where: { id: existing.userId },
      data: { role: 'USER' },
    });

    return NextResponse.json({ message: 'Profesional eliminado correctamente' });
  } catch (error: any) {
    console.error('Error deleting professional:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
