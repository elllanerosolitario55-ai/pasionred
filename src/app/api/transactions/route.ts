import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { getServerSession } from '@/lib/auth';

// GET /api/transactions - Listar transacciones
export async function GET(request: NextRequest) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    const searchParams = request.nextUrl.searchParams;

    // Parámetros de filtrado
    const type = searchParams.get('type');
    const status = searchParams.get('status');

    // Parámetros de paginación
    const page = parseInt(searchParams.get('page') || '1');
    const limit = parseInt(searchParams.get('limit') || '20');
    const skip = (page - 1) * limit;

    // Construir filtros
    const where: any = {
      userId: session.user.id,
    };

    if (type) {
      where.type = type.toUpperCase();
    }

    if (status) {
      where.status = status.toUpperCase();
    }

    // Consulta con paginación
    const [transactions, total] = await Promise.all([
      prisma.transaction.findMany({
        where,
        skip,
        take: limit,
        orderBy: { createdAt: 'desc' },
      }),
      prisma.transaction.count({ where }),
    ]);

    // Calcular totales
    const totals = await prisma.transaction.aggregate({
      where: {
        userId: session.user.id,
        status: 'COMPLETED',
      },
      _sum: {
        amount: true,
      },
    });

    return NextResponse.json({
      transactions,
      pagination: {
        page,
        limit,
        total,
        pages: Math.ceil(total / limit),
      },
      totals: {
        totalAmount: totals._sum.amount || 0,
      },
    });
  } catch (error: any) {
    console.error('Error fetching transactions:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// POST /api/transactions - Crear transacción
export async function POST(request: NextRequest) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    const body = await request.json();
    const {
      professionalId,
      amount,
      currency = 'EUR',
      type,
      paymentMethod,
      paymentId,
      metadata,
    } = body;

    // Validaciones
    if (!amount || amount <= 0) {
      return NextResponse.json(
        { error: 'Monto inválido' },
        { status: 400 }
      );
    }

    if (!type) {
      return NextResponse.json(
        { error: 'Tipo de transacción requerido' },
        { status: 400 }
      );
    }

    if (!paymentMethod) {
      return NextResponse.json(
        { error: 'Método de pago requerido' },
        { status: 400 }
      );
    }

    // Crear transacción
    const transaction = await prisma.transaction.create({
      data: {
        userId: session.user.id,
        professionalId: professionalId || null,
        amount,
        currency,
        type: type.toUpperCase(),
        paymentMethod: paymentMethod.toUpperCase(),
        paymentId: paymentId || null,
        status: 'PENDING',
        metadata: metadata || null,
      },
    });

    return NextResponse.json(transaction, { status: 201 });
  } catch (error: any) {
    console.error('Error creating transaction:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
