import { PrismaClient } from '@prisma/client';
import bcrypt from 'bcryptjs';

const prisma = new PrismaClient();

async function main() {
  console.log('🌱 Iniciando seed de base de datos...');

  // Limpiar datos existentes
  await prisma.message.deleteMany();
  await prisma.notification.deleteMany();
  await prisma.credit.deleteMany();
  await prisma.transaction.deleteMany();
  await prisma.review.deleteMany();
  await prisma.videoSession.deleteMany();
  await prisma.post.deleteMany();
  await prisma.availability.deleteMany();
  await prisma.membership.deleteMany();
  await prisma.professional.deleteMany();
  await prisma.province.deleteMany();
  await prisma.country.deleteMany();
  await prisma.category.deleteMany();
  await prisma.user.deleteMany();

  // Crear categorías
  console.log('📦 Creando categorías...');
  const categories = await Promise.all([
    prisma.category.create({
      data: {
        name: 'Coaches',
        slug: 'coaches',
        description: 'Profesionales del coaching personal y empresarial',
      },
    }),
    prisma.category.create({
      data: {
        name: 'Psicólogos',
        slug: 'psicologos',
        description: 'Psicólogos y terapeutas',
      },
    }),
    prisma.category.create({
      data: {
        name: 'Médicos',
        slug: 'medicos',
        description: 'Profesionales de la medicina',
      },
    }),
  ]);

  // Crear países y provincias
  console.log('🌍 Creando países y provincias...');
  const espana = await prisma.country.create({
    data: {
      name: 'España',
      slug: 'espana',
      code: 'ES',
      provinces: {
        create: [
          { name: 'Madrid', slug: 'madrid' },
          { name: 'Barcelona', slug: 'barcelona' },
          { name: 'Valencia', slug: 'valencia' },
        ],
      },
    },
    include: { provinces: true },
  });

  const mexico = await prisma.country.create({
    data: {
      name: 'México',
      slug: 'mexico',
      code: 'MX',
      provinces: {
        create: [
          { name: 'Ciudad de México', slug: 'ciudad-de-mexico' },
          { name: 'Guadalajara', slug: 'guadalajara' },
        ],
      },
    },
    include: { provinces: true },
  });

  // Crear usuarios de prueba
  console.log('👤 Creando usuarios...');
  const hashedPassword = await bcrypt.hash('password123', 10);

  const adminUser = await prisma.user.create({
    data: {
      name: 'Admin Usuario',
      email: 'admin@pasiones.com',
      password: hashedPassword,
      role: 'ADMIN',
      emailVerified: new Date(),
    },
  });

  const professionalUser1 = await prisma.user.create({
    data: {
      name: 'Dra. María González',
      email: 'maria@pasiones.com',
      password: hashedPassword,
      role: 'PROFESSIONAL',
      emailVerified: new Date(),
    },
  });

  const professionalUser2 = await prisma.user.create({
    data: {
      name: 'Dr. Juan Pérez',
      email: 'juan@pasiones.com',
      password: hashedPassword,
      role: 'PROFESSIONAL',
      emailVerified: new Date(),
    },
  });

  const regularUser = await prisma.user.create({
    data: {
      name: 'Cliente Regular',
      email: 'cliente@pasiones.com',
      password: hashedPassword,
      role: 'USER',
      emailVerified: new Date(),
    },
  });

  // Crear profesionales
  console.log('💼 Creando profesionales...');
  const professional1 = await prisma.professional.create({
    data: {
      userId: professionalUser1.id,
      bio: 'Psicóloga clínica especializada en terapia cognitivo-conductual con más de 10 años de experiencia.',
      categoryId: categories[1].id, // Psicólogos
      countryId: espana.id,
      provinceId: espana.provinces[0].id, // Madrid
      membershipType: 'GOLD',
      costPerMinute: 3.5,
      isOnline: true,
      isVerified: true,
      rating: 4.9,
      reviewsCount: 243,
    },
  });

  const professional2 = await prisma.professional.create({
    data: {
      userId: professionalUser2.id,
      bio: 'Coach profesional certificado especializado en desarrollo personal y liderazgo empresarial.',
      categoryId: categories[0].id, // Coaches
      countryId: mexico.id,
      provinceId: mexico.provinces[0].id, // CDMX
      membershipType: 'SILVER',
      costPerMinute: 2.5,
      isOnline: false,
      isVerified: true,
      rating: 4.7,
      reviewsCount: 156,
    },
  });

  // Crear membresías
  console.log('💳 Creando membresías...');
  await prisma.membership.create({
    data: {
      professionalId: professional1.id,
      type: 'GOLD',
      startDate: new Date(),
      endDate: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000), // +30 días
      status: 'ACTIVE',
    },
  });

  await prisma.membership.create({
    data: {
      professionalId: professional2.id,
      type: 'SILVER',
      startDate: new Date(),
      endDate: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000),
      status: 'ACTIVE',
    },
  });

  // Crear posts
  console.log('📝 Creando posts...');
  await prisma.post.create({
    data: {
      professionalId: professional1.id,
      categoryId: categories[1].id,
      content: '🌟 Nueva sesión disponible sobre manejo del estrés y ansiedad. ¡Reserva tu cita!',
      isPaid: false,
      likesCount: 45,
      viewsCount: 230,
    },
  });

  // Crear reviews
  console.log('⭐ Creando reviews...');
  await prisma.review.create({
    data: {
      professionalId: professional1.id,
      userId: regularUser.id,
      rating: 5,
      comment: 'Excelente profesional, muy recomendada. Me ayudó mucho con mi ansiedad.',
      status: 'APPROVED',
    },
  });

  // Crear créditos de prueba
  console.log('💰 Creando créditos...');
  await prisma.credit.create({
    data: {
      userId: regularUser.id,
      amount: 50,
      type: 'PURCHASE',
      description: 'Compra inicial de créditos',
    },
  });

  // Crear notificaciones
  console.log('🔔 Creando notificaciones...');
  await prisma.notification.create({
    data: {
      userId: professionalUser1.id,
      type: 'new_review',
      title: 'Nueva reseña recibida',
      message: 'Has recibido una nueva reseña de 5 estrellas',
      link: '/dashboard/reviews',
    },
  });

  console.log('✅ Seed completado exitosamente!');
  console.log('\n📊 Datos creados:');
  console.log(`- ${3} categorías`);
  console.log(`- ${2} países con provincias`);
  console.log(`- ${4} usuarios (1 admin, 2 profesionales, 1 cliente)`);
  console.log(`- ${2} profesionales con membresías`);
  console.log(`- Posts, reviews, créditos y notificaciones`);
  console.log('\n🔐 Credenciales de prueba:');
  console.log('Admin: admin@pasiones.com / password123');
  console.log('Profesional 1: maria@pasiones.com / password123');
  console.log('Profesional 2: juan@pasiones.com / password123');
  console.log('Cliente: cliente@pasiones.com / password123');
}

main()
  .catch((e) => {
    console.error('❌ Error en seed:', e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
