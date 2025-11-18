import Stripe from 'stripe';

if (!process.env.STRIPE_SECRET_KEY) {
  throw new Error('STRIPE_SECRET_KEY no está configurado');
}

export const stripe = new Stripe(process.env.STRIPE_SECRET_KEY, {
  apiVersion: '2025-10-29.clover',
  typescript: true,
});

/**
 * Crear Payment Intent
 */
export async function createPaymentIntent(amount: number, currency: string = 'eur', metadata: any = {}) {
  try {
    const paymentIntent = await stripe.paymentIntents.create({
      amount: Math.round(amount * 100), // Convertir a centavos
      currency,
      metadata,
      automatic_payment_methods: {
        enabled: true,
      },
    });

    return paymentIntent;
  } catch (error) {
    console.error('Error creando Payment Intent:', error);
    throw error;
  }
}

/**
 * Crear suscripción
 */
export async function createSubscription(customerId: string, priceId: string, metadata: any = {}) {
  try {
    const subscription = await stripe.subscriptions.create({
      customer: customerId,
      items: [{ price: priceId }],
      payment_behavior: 'default_incomplete',
      payment_settings: { save_default_payment_method: 'on_subscription' },
      expand: ['latest_invoice.payment_intent'],
      metadata,
    });

    return subscription;
  } catch (error) {
    console.error('Error creando suscripción:', error);
    throw error;
  }
}

/**
 * Crear cliente de Stripe
 */
export async function createCustomer(email: string, name?: string, metadata: any = {}) {
  try {
    const customer = await stripe.customers.create({
      email,
      name,
      metadata,
    });

    return customer;
  } catch (error) {
    console.error('Error creando cliente:', error);
    throw error;
  }
}

/**
 * Crear productos de membresía (ejecutar una vez)
 */
export async function createMembershipProducts() {
  const memberships = [
    {
      name: 'Membresía Bronce',
      description: 'Plan Bronce con funcionalidades intermedias',
      price: 20,
      interval: 'month' as const,
    },
    {
      name: 'Membresía Plata',
      description: 'Plan Plata con streaming y alta visibilidad',
      price: 45,
      interval: 'month' as const,
    },
    {
      name: 'Membresía Oro',
      description: 'Plan Oro premium con todas las funciones',
      price: 65,
      interval: 'month' as const,
    },
  ];

  const products = [];

  for (const membership of memberships) {
    try {
      // Crear producto
      const product = await stripe.products.create({
        name: membership.name,
        description: membership.description,
      });

      // Crear precio
      const price = await stripe.prices.create({
        product: product.id,
        unit_amount: membership.price * 100,
        currency: 'eur',
        recurring: {
          interval: membership.interval,
        },
      });

      products.push({ product, price });
      console.log(`Producto creado: ${membership.name} - Price ID: ${price.id}`);
    } catch (error) {
      console.error(`Error creando ${membership.name}:`, error);
    }
  }

  return products;
}

/**
 * Cancelar suscripción
 */
export async function cancelSubscription(subscriptionId: string) {
  try {
    const subscription = await stripe.subscriptions.cancel(subscriptionId);
    return subscription;
  } catch (error) {
    console.error('Error cancelando suscripción:', error);
    throw error;
  }
}

/**
 * Obtener sesión de checkout
 */
export async function createCheckoutSession(priceId: string, customerId: string, successUrl: string, cancelUrl: string) {
  try {
    const session = await stripe.checkout.sessions.create({
      mode: 'subscription',
      customer: customerId,
      line_items: [
        {
          price: priceId,
          quantity: 1,
        },
      ],
      success_url: successUrl,
      cancel_url: cancelUrl,
    });

    return session;
  } catch (error) {
    console.error('Error creando sesión de checkout:', error);
    throw error;
  }
}
