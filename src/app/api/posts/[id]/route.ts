import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { getServerSession } from '@/lib/auth';

// GET /api/posts/[id] - Obtener post por ID
export async function GET(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const post = await prisma.post.findUnique({
      where: { id: params.id },
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
    });

    if (!post) {
      return NextResponse.json({ error: 'Post no encontrado' }, { status: 404 });
    }

    // Si es contenido de pago, verificar acceso
    if (post.isPaid) {
      const session = await getServerSession();

      // Solo el dueño puede ver contenido de pago sin pagar
      if (!session?.user || session.user.id !== post.professional.userId) {
        // Aquí se debería verificar si el usuario ha comprado el contenido
        // Por ahora solo mostramos info básica
        return NextResponse.json({
          ...post,
          images: [],
          videos: [],
          content: post.content.substring(0, 100) + '...',
          isPaid: true,
          price: post.price,
        });
      }
    }

    // Incrementar contador de vistas
    await prisma.post.update({
      where: { id: params.id },
      data: { viewsCount: { increment: 1 } },
    });

    return NextResponse.json(post);
  } catch (error: any) {
    console.error('Error fetching post:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// PATCH /api/posts/[id] - Actualizar post
export async function PATCH(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    // Verificar que el post pertenezca al profesional del usuario
    const existing = await prisma.post.findUnique({
      where: { id: params.id },
      include: {
        professional: true,
      },
    });

    if (!existing) {
      return NextResponse.json({ error: 'Post no encontrado' }, { status: 404 });
    }

    if (existing.professional.userId !== session.user.id && session.user.role !== 'ADMIN') {
      return NextResponse.json(
        { error: 'No tienes permiso para editar este post' },
        { status: 403 }
      );
    }

    const body = await request.json();
    const {
      content,
      images,
      videos,
      isPaid,
      price,
      isPublished,
      categoryId,
    } = body;

    // Actualizar post
    const post = await prisma.post.update({
      where: { id: params.id },
      data: {
        ...(content !== undefined && { content }),
        ...(images !== undefined && { images }),
        ...(videos !== undefined && { videos }),
        ...(isPaid !== undefined && { isPaid }),
        ...(price !== undefined && { price }),
        ...(isPublished !== undefined && { isPublished }),
        ...(categoryId !== undefined && { categoryId }),
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

    return NextResponse.json(post);
  } catch (error: any) {
    console.error('Error updating post:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// DELETE /api/posts/[id] - Eliminar post
export async function DELETE(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    // Verificar que el post pertenezca al profesional del usuario
    const existing = await prisma.post.findUnique({
      where: { id: params.id },
      include: {
        professional: true,
      },
    });

    if (!existing) {
      return NextResponse.json({ error: 'Post no encontrado' }, { status: 404 });
    }

    if (existing.professional.userId !== session.user.id && session.user.role !== 'ADMIN') {
      return NextResponse.json(
        { error: 'No tienes permiso para eliminar este post' },
        { status: 403 }
      );
    }

    // Eliminar post
    await prisma.post.delete({
      where: { id: params.id },
    });

    return NextResponse.json({ message: 'Post eliminado correctamente' });
  } catch (error: any) {
    console.error('Error deleting post:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
