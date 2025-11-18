import { NextRequest, NextResponse } from 'next/server';
import { createPaymentIntent } from '@/lib/stripe';
import { getServerSession } from '@/lib/auth';

export async function POST(request: NextRequest) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    const body = await request.json();
    const { amount, currency = 'eur', metadata } = body;

    if (!amount || amount <= 0) {
      return NextResponse.json({ error: 'Monto inválido' }, { status: 400 });
    }

    const paymentIntent = await createPaymentIntent(amount, currency, {
      userId: session.user.id,
      ...metadata,
    });

    return NextResponse.json({
      clientSecret: paymentIntent.client_secret,
      paymentIntentId: paymentIntent.id,
    });
  } catch (error: any) {
    console.error('Error creando Payment Intent:', error);
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
