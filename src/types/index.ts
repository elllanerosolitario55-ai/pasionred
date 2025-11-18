// Tipos de membresía
export type MembershipType = 'free' | 'bronze' | 'silver' | 'gold';

// Usuario
export interface User {
  id: string;
  email: string;
  name: string;
  role: 'user' | 'professional' | 'admin';
  avatar?: string;
  createdAt: Date;
}

// Profesional
export interface Professional {
  id: string;
  userId: string;
  name: string;
  bio: string;
  avatar?: string;
  coverImage?: string;
  category: string;
  country: string;
  province: string;
  membershipType: MembershipType;
  rating: number;
  reviewsCount: number;
  isOnline: boolean;
  isStreaming: boolean;
  costPerMinute: number;
  verified: boolean;
}

// Membresía
export interface Membership {
  id: string;
  userId: string;
  type: MembershipType;
  startDate: Date;
  endDate: Date;
  status: 'active' | 'expired' | 'cancelled';
  autoRenew: boolean;
}

// Configuración de membresías
export interface MembershipConfig {
  name: string;
  price: number;
  features: {
    postsLimit: number;
    canPostImages: boolean;
    canPostVideos: boolean;
    canVideochat: boolean;
    canStream: boolean;
    canReceiveReviews: boolean;
    canSetSchedule: boolean;
    visibility: string;
    [key: string]: any;
  };
}

// Sesión de videochat/streaming
export interface Session {
  id: string;
  professionalId: string;
  userId?: string;
  sessionType: 'videochat' | 'streaming';
  status: 'waiting' | 'active' | 'completed';
  startTime?: Date;
  endTime?: Date;
  duration: number;
  costPerMinute: number;
  totalCost: number;
  isPrivate: boolean;
  viewersCount: number;
}

// Review/Valoración
export interface Review {
  id: string;
  professionalId: string;
  userId: string;
  rating: number;
  comment: string;
  sessionId?: string;
  status: 'pending' | 'approved' | 'rejected';
  createdAt: Date;
}

// Notificación
export interface Notification {
  id: string;
  userId: string;
  type: string;
  title: string;
  message: string;
  link?: string;
  isRead: boolean;
  createdAt: Date;
}

// Transacción
export interface Transaction {
  id: string;
  userId: string;
  professionalId?: string;
  amount: number;
  currency: string;
  type: string;
  paymentMethod: string;
  paymentId?: string;
  status: 'pending' | 'completed' | 'failed';
  createdAt: Date;
}

// Categoría
export interface Category {
  id: string;
  name: string;
  slug: string;
  description?: string;
  icon?: string;
}

// País
export interface Country {
  id: string;
  name: string;
  slug: string;
  code: string;
}

// Provincia
export interface Province {
  id: string;
  name: string;
  slug: string;
  countryId: string;
}

// Post
export interface Post {
  id: string;
  professionalId: string;
  content: string;
  images?: string[];
  videos?: string[];
  isPaid: boolean;
  price?: number;
  likes: number;
  comments: number;
  createdAt: Date;
}
