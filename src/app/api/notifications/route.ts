import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { getServerSession } from '@/lib/auth';

// GET /api/notifications - Listar notificaciones
export async function GET(request: NextRequest) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    const searchParams = request.nextUrl.searchParams;

    // Parámetros de filtrado
    const isRead = searchParams.get('isRead');

    // Parámetros de paginación
    const page = parseInt(searchParams.get('page') || '1');
    const limit = parseInt(searchParams.get('limit') || '20');
    const skip = (page - 1) * limit;

    // Construir filtros
    const where: any = {
      userId: session.user.id,
    };

    if (isRead !== null) {
      where.isRead = isRead === 'true';
    }

    // Consulta con paginación
    const [notifications, total, unreadCount] = await Promise.all([
      prisma.notification.findMany({
        where,
        skip,
        take: limit,
        orderBy: { createdAt: 'desc' },
      }),
      prisma.notification.count({ where }),
      prisma.notification.count({
        where: {
          userId: session.user.id,
          isRead: false,
        },
      }),
    ]);

    return NextResponse.json({
      notifications,
      pagination: {
        page,
        limit,
        total,
        pages: Math.ceil(total / limit),
      },
      unreadCount,
    });
  } catch (error: any) {
    console.error('Error fetching notifications:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// PATCH /api/notifications - Marcar todas como leídas
export async function PATCH(request: NextRequest) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    await prisma.notification.updateMany({
      where: {
        userId: session.user.id,
        isRead: false,
      },
      data: {
        isRead: true,
      },
    });

    return NextResponse.json({ message: 'Notificaciones marcadas como leídas' });
  } catch (error: any) {
    console.error('Error updating notifications:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
