import { NextRequest, NextResponse } from 'next/server';
import { getServerSession } from '@/lib/auth';
import { uploadImage, uploadVideo, ALLOWED_FILE_TYPES, MAX_FILE_SIZES, CLOUDINARY_FOLDERS } from '@/lib/cloudinary';

export const config = {
  api: {
    bodyParser: false, // Desactivar body parser para manejar archivos
  },
};

// POST /api/upload - Upload de archivos
export async function POST(request: NextRequest) {
  try {
    const session = await getServerSession();

    if (!session?.user) {
      return NextResponse.json({ error: 'No autenticado' }, { status: 401 });
    }

    const formData = await request.formData();
    const file = formData.get('file') as File;
    const type = formData.get('type') as string; // 'avatar', 'cover', 'post', 'video'

    if (!file) {
      return NextResponse.json({ error: 'No se proporcionó archivo' }, { status: 400 });
    }

    // Validar tipo de archivo
    const isImage = ALLOWED_FILE_TYPES.images.includes(file.type);
    const isVideo = ALLOWED_FILE_TYPES.videos.includes(file.type);

    if (!isImage && !isVideo) {
      return NextResponse.json(
        { error: 'Tipo de archivo no permitido' },
        { status: 400 }
      );
    }

    // Validar tamaño
    let maxSize = MAX_FILE_SIZES.image;
    if (isVideo) maxSize = MAX_FILE_SIZES.video;
    if (type === 'avatar') maxSize = MAX_FILE_SIZES.avatar;

    if (file.size > maxSize) {
      return NextResponse.json(
        { error: `El archivo es demasiado grande. Máximo: ${maxSize / 1024 / 1024}MB` },
        { status: 400 }
      );
    }

    // Convertir archivo a buffer
    const bytes = await file.arrayBuffer();
    const buffer = Buffer.from(bytes);

    // Convertir buffer a base64 para Cloudinary
    const base64 = `data:${file.type};base64,${buffer.toString('base64')}`;

    // Determinar carpeta de destino
    let folder = CLOUDINARY_FOLDERS.posts;
    if (type === 'avatar') folder = CLOUDINARY_FOLDERS.avatars;
    if (type === 'cover') folder = CLOUDINARY_FOLDERS.covers;
    if (type === 'video') folder = CLOUDINARY_FOLDERS.videos;

    // Upload a Cloudinary
    let result;
    if (isVideo) {
      result = await uploadVideo(base64, {
        folder,
        publicId: `${session.user.id}_${Date.now()}`,
      });
    } else {
      // Transformaciones según tipo
      let transformation: any = [
        { quality: 'auto' },
        { fetch_format: 'auto' },
      ];

      if (type === 'avatar') {
        transformation.push(
          { width: 400, height: 400, crop: 'fill', gravity: 'face' },
          { radius: 'max' }
        );
      } else if (type === 'cover') {
        transformation.push(
          { width: 1200, height: 400, crop: 'fill' }
        );
      }

      result = await uploadImage(base64, {
        folder,
        publicId: `${session.user.id}_${Date.now()}`,
        transformation,
      });
    }

    return NextResponse.json({
      success: true,
      file: result,
    });
  } catch (error: any) {
    console.error('Error uploading file:', error);
    return NextResponse.json(
      { error: error.message || 'Error al subir archivo' },
      { status: 500 }
    );
  }
}
