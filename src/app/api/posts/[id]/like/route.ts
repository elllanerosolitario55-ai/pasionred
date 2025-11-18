import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { getServerSession } from '@/lib/auth';

// POST /api/posts/[id]/like - Dar/quitar like a un post
export async function POST(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    const post = await prisma.post.findUnique({
      where: { id: params.id },
    });

    if (!post) {
      return NextResponse.json({ error: 'Post no encontrado' }, { status: 404 });
    }

    // Por ahora solo incrementamos el contador
    // En producción deberías tener una tabla de likes para evitar duplicados
    const updatedPost = await prisma.post.update({
      where: { id: params.id },
      data: { likesCount: { increment: 1 } },
    });

    return NextResponse.json({
      liked: true,
      likesCount: updatedPost.likesCount,
    });
  } catch (error: any) {
    console.error('Error liking post:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
