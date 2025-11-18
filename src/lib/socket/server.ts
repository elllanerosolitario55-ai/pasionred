import { Server as HTTPServer } from 'http';
import { Server as SocketIOServer, Socket } from 'socket.io';
import { prisma } from '../prisma';

export function initializeSocketIO(httpServer: HTTPServer) {
  const io = new SocketIOServer(httpServer, {
    cors: {
      origin: process.env.NEXT_PUBLIC_APP_URL || 'http://localhost:3000',
      methods: ['GET', 'POST'],
    },
  });

  // Almacenar usuarios conectados
  const connectedUsers = new Map<string, string>(); // userId -> socketId

  io.on('connection', (socket: Socket) => {
    console.log('Usuario conectado:', socket.id);

    // Autenticar usuario
    socket.on('auth', async (userId: string) => {
      connectedUsers.set(userId, socket.id);
      socket.data.userId = userId;

      // Emitir estado online
      io.emit('user:online', { userId, online: true });

      console.log(`Usuario ${userId} autenticado`);
    });

    // Enviar mensaje
    socket.on('message:send', async (data: {
      receiverId: string;
      content: string;
      type: 'TEXT' | 'IMAGE' | 'VIDEO' | 'AUDIO';
      mediaUrl?: string;
    }) => {
      try {
        const senderId = socket.data.userId;

        if (!senderId) {
          socket.emit('error', { message: 'No autenticado' });
          return;
        }

        // Guardar mensaje en base de datos
        const message = await prisma.message.create({
          data: {
            senderId,
            receiverId: data.receiverId,
            content: data.content,
            type: data.type,
            mediaUrl: data.mediaUrl,
          },
          include: {
            sender: {
              select: {
                id: true,
                name: true,
                image: true,
              },
            },
          },
        });

        // Enviar a receptor si está conectado
        const receiverSocketId = connectedUsers.get(data.receiverId);
        if (receiverSocketId) {
          io.to(receiverSocketId).emit('message:receive', message);
        }

        // Confirmar al remitente
        socket.emit('message:sent', message);

        // Crear notificación
        await prisma.notification.create({
          data: {
            userId: data.receiverId,
            type: 'new_message',
            title: 'Nuevo mensaje',
            message: `${message.sender.name} te ha enviado un mensaje`,
            link: `/chat/${senderId}`,
          },
        });

      } catch (error) {
        console.error('Error enviando mensaje:', error);
        socket.emit('error', { message: 'Error al enviar mensaje' });
      }
    });

    // Marcar mensajes como leídos
    socket.on('messages:read', async (data: { senderId: string }) => {
      try {
        const userId = socket.data.userId;

        if (!userId) return;

        await prisma.message.updateMany({
          where: {
            senderId: data.senderId,
            receiverId: userId,
            isRead: false,
          },
          data: {
            isRead: true,
          },
        });

        // Notificar al remitente
        const senderSocketId = connectedUsers.get(data.senderId);
        if (senderSocketId) {
          io.to(senderSocketId).emit('messages:read', { userId });
        }

      } catch (error) {
        console.error('Error marcando mensajes como leídos:', error);
      }
    });

    // Usuario escribiendo...
    socket.on('typing:start', (data: { receiverId: string }) => {
      const receiverSocketId = connectedUsers.get(data.receiverId);
      if (receiverSocketId) {
        io.to(receiverSocketId).emit('typing:start', { userId: socket.data.userId });
      }
    });

    socket.on('typing:stop', (data: { receiverId: string }) => {
      const receiverSocketId = connectedUsers.get(data.receiverId);
      if (receiverSocketId) {
        io.to(receiverSocketId).emit('typing:stop', { userId: socket.data.userId });
      }
    });

    // Videochat signaling
    socket.on('webrtc:offer', (data: { to: string; offer: any }) => {
      const receiverSocketId = connectedUsers.get(data.to);
      if (receiverSocketId) {
        io.to(receiverSocketId).emit('webrtc:offer', {
          from: socket.data.userId,
          offer: data.offer,
        });
      }
    });

    socket.on('webrtc:answer', (data: { to: string; answer: any }) => {
      const receiverSocketId = connectedUsers.get(data.to);
      if (receiverSocketId) {
        io.to(receiverSocketId).emit('webrtc:answer', {
          from: socket.data.userId,
          answer: data.answer,
        });
      }
    });

    socket.on('webrtc:ice-candidate', (data: { to: string; candidate: any }) => {
      const receiverSocketId = connectedUsers.get(data.to);
      if (receiverSocketId) {
        io.to(receiverSocketId).emit('webrtc:ice-candidate', {
          from: socket.data.userId,
          candidate: data.candidate,
        });
      }
    });

    // Desconexión
    socket.on('disconnect', () => {
      const userId = socket.data.userId;

      if (userId) {
        connectedUsers.delete(userId);

        // Emitir estado offline
        io.emit('user:online', { userId, online: false });

        console.log(`Usuario ${userId} desconectado`);
      }

      console.log('Socket desconectado:', socket.id);
    });
  });

  return io;
}
