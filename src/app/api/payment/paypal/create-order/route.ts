import { NextRequest, NextResponse } from 'next/server';
import { createOrder } from '@/lib/paypal';
import { getServerSession } from '@/lib/auth';

export async function POST(request: NextRequest) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    const body = await request.json();
    const { amount, currency = 'EUR' } = body;

    if (!amount || amount <= 0) {
      return NextResponse.json({ error: 'Monto inválido' }, { status: 400 });
    }

    const order = await createOrder(amount, currency);

    return NextResponse.json({
      orderId: order.id,
      links: order.links,
    });
  } catch (error: any) {
    console.error('Error creando orden de PayPal:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
