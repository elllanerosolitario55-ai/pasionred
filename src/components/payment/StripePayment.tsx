'use client';

import { useState, useEffect } from 'react';
import { loadStripe, StripeElementsOptions } from '@stripe/stripe-js';
import { Elements, PaymentElement, useStripe, useElements } from '@stripe/react-stripe-js';
import { Button } from '@/components/ui/button';
import { Loader2 } from 'lucide-react';

const stripePromise = loadStripe(process.env.NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY!);

interface StripePaymentProps {
  amount: number;
  currency: string;
  description: string;
  type: string;
  metadata?: any;
  onSuccess: (paymentData: any) => void;
  onError: (error: string) => void;
  onProcessing: (processing: boolean) => void;
}

function CheckoutForm({
  amount,
  onSuccess,
  onError,
  onProcessing
}: StripePaymentProps) {
  const stripe = useStripe();
  const elements = useElements();
  const [isProcessing, setIsProcessing] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!stripe || !elements) {
      return;
    }

    setIsProcessing(true);
    onProcessing(true);

    try {
      const { error, paymentIntent } = await stripe.confirmPayment({
        elements,
        confirmParams: {
          return_url: `${window.location.origin}/payment/success`,
        },
        redirect: 'if_required',
      });

      if (error) {
        throw new Error(error.message);
      }

      if (paymentIntent && paymentIntent.status === 'succeeded') {
        onSuccess({
          paymentIntentId: paymentIntent.id,
          amount: paymentIntent.amount / 100,
          currency: paymentIntent.currency,
          status: paymentIntent.status,
        });
      }
    } catch (err: any) {
      console.error('Error processing payment:', err);
      onError(err.message || 'Error al procesar el pago');
    } finally {
      setIsProcessing(false);
      onProcessing(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <PaymentElement />

      <Button
        type="submit"
        disabled={!stripe || isProcessing}
        className="w-full bg-gradient-to-r from-pink-600 to-blue-400 hover:from-pink-700 hover:to-blue-500"
      >
        {isProcessing ? (
          <>
            <Loader2 className="h-4 w-4 mr-2 animate-spin" />
            Procesando...
          </>
        ) : (
          `Pagar ${amount.toFixed(2)}€`
        )}
      </Button>
    </form>
  );
}

export function StripePayment(props: StripePaymentProps) {
  const [clientSecret, setClientSecret] = useState<string | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Crear Payment Intent
    const createPaymentIntent = async () => {
      try {
        const response = await fetch('/api/payment/stripe/create-intent', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            amount: props.amount,
            currency: props.currency,
            metadata: {
              ...props.metadata,
              type: props.type,
              description: props.description,
            },
          }),
        });

        if (!response.ok) {
          throw new Error('Error al crear intención de pago');
        }

        const data = await response.json();
        setClientSecret(data.clientSecret);
      } catch (error: any) {
        console.error('Error creating payment intent:', error);
        props.onError(error.message || 'Error al inicializar pago');
      } finally {
        setLoading(false);
      }
    };

    createPaymentIntent();
  }, [props.amount, props.currency]);

  if (loading) {
    return (
      <div className="flex items-center justify-center py-8">
        <Loader2 className="h-6 w-6 animate-spin text-pink-600" />
      </div>
    );
  }

  if (!clientSecret) {
    return (
      <div className="text-center text-red-600 py-4">
        Error al inicializar el pago
      </div>
    );
  }

  const options: StripeElementsOptions = {
    clientSecret,
    appearance: {
      theme: 'stripe',
      variables: {
        colorPrimary: '#ec4899',
        colorBackground: '#ffffff',
        colorText: '#1e293b',
        colorDanger: '#ef4444',
        borderRadius: '8px',
      },
    },
  };

  return (
    <Elements stripe={stripePromise} options={options}>
      <CheckoutForm {...props} />
    </Elements>
  );
}
