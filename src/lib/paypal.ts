import paypal from '@paypal/checkout-server-sdk';

const environment =
  process.env.PAYPAL_MODE === 'live'
    ? new paypal.core.LiveEnvironment(
        process.env.PAYPAL_CLIENT_ID!,
        process.env.PAYPAL_SECRET!
      )
    : new paypal.core.SandboxEnvironment(
        process.env.PAYPAL_CLIENT_ID!,
        process.env.PAYPAL_SECRET!
      );

const client = new paypal.core.PayPalHttpClient(environment);

/**
 * Crear orden de PayPal
 */
export async function createOrder(amount: number, currency: string = 'EUR') {
  const request = new paypal.orders.OrdersCreateRequest();
  request.prefer('return=representation');
  request.requestBody({
    intent: 'CAPTURE',
    purchase_units: [
      {
        amount: {
          currency_code: currency,
          value: amount.toFixed(2),
        },
      },
    ],
  });

  try {
    const response = await client.execute(request);
    return response.result;
  } catch (error) {
    console.error('Error creando orden de PayPal:', error);
    throw error;
  }
}

/**
 * Capturar orden de PayPal
 */
export async function captureOrder(orderId: string) {
  const request = new paypal.orders.OrdersCaptureRequest(orderId);
  request.requestBody({});

  try {
    const response = await client.execute(request);
    return response.result;
  } catch (error) {
    console.error('Error capturando orden de PayPal:', error);
    throw error;
  }
}

/**
 * Crear plan de suscripción
 */
export async function createSubscriptionPlan(
  name: string,
  description: string,
  price: number,
  interval: 'MONTH' | 'YEAR' = 'MONTH'
) {
  const request = new paypal.subscriptions.BillingPlansCreateRequest();
  request.requestBody({
    product_id: process.env.PAYPAL_PRODUCT_ID!, // Necesitas crear un producto en PayPal primero
    name,
    description,
    billing_cycles: [
      {
        frequency: {
          interval_unit: interval,
          interval_count: 1,
        },
        tenure_type: 'REGULAR',
        sequence: 1,
        total_cycles: 0, // 0 = ilimitado
        pricing_scheme: {
          fixed_price: {
            value: price.toFixed(2),
            currency_code: 'EUR',
          },
        },
      },
    ],
    payment_preferences: {
      auto_bill_outstanding: true,
      setup_fee_failure_action: 'CONTINUE',
      payment_failure_threshold: 3,
    },
  });

  try {
    const response = await client.execute(request);
    return response.result;
  } catch (error) {
    console.error('Error creando plan de suscripción:', error);
    throw error;
  }
}

/**
 * Crear suscripción
 */
export async function createSubscription(planId: string) {
  const request = new paypal.subscriptions.SubscriptionsCreateRequest();
  request.requestBody({
    plan_id: planId,
    application_context: {
      return_url: `${process.env.NEXT_PUBLIC_APP_URL}/payment/success`,
      cancel_url: `${process.env.NEXT_PUBLIC_APP_URL}/payment/cancel`,
    },
  });

  try {
    const response = await client.execute(request);
    return response.result;
  } catch (error) {
    console.error('Error creando suscripción:', error);
    throw error;
  }
}

/**
 * Cancelar suscripción
 */
export async function cancelSubscription(subscriptionId: string) {
  const request = new paypal.subscriptions.SubscriptionsCancelRequest(subscriptionId);
  request.requestBody({
    reason: 'Usuario canceló la suscripción',
  });

  try {
    const response = await client.execute(request);
    return response;
  } catch (error) {
    console.error('Error cancelando suscripción:', error);
    throw error;
  }
}

export { client as paypalClient };
