import { v2 as cloudinary } from 'cloudinary';

// Configurar Cloudinary
cloudinary.config({
  cloud_name: process.env.NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME,
  api_key: process.env.CLOUDINARY_API_KEY,
  api_secret: process.env.CLOUDINARY_API_SECRET,
  secure: true,
});

export { cloudinary };

/**
 * Tipos de archivos permitidos
 */
export const ALLOWED_FILE_TYPES = {
  images: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
  videos: ['video/mp4', 'video/mov', 'video/avi', 'video/webm'],
  audio: ['audio/mp3', 'audio/mpeg', 'audio/wav'],
};

/**
 * Tamaños máximos por tipo (en bytes)
 */
export const MAX_FILE_SIZES = {
  image: 10 * 1024 * 1024, // 10MB
  video: 100 * 1024 * 1024, // 100MB
  audio: 20 * 1024 * 1024, // 20MB
  avatar: 5 * 1024 * 1024, // 5MB
};

/**
 * Carpetas en Cloudinary por tipo de contenido
 */
export const CLOUDINARY_FOLDERS = {
  avatars: 'pasiones/avatars',
  covers: 'pasiones/covers',
  posts: 'pasiones/posts',
  videos: 'pasiones/videos',
  audio: 'pasiones/audio',
  temp: 'pasiones/temp',
};

/**
 * Upload de imagen a Cloudinary
 */
export async function uploadImage(
  file: string,
  options: {
    folder?: string;
    publicId?: string;
    transformation?: any;
  } = {}
) {
  try {
    const result = await cloudinary.uploader.upload(file, {
      folder: options.folder || CLOUDINARY_FOLDERS.posts,
      public_id: options.publicId,
      resource_type: 'image',
      transformation: options.transformation || [
        { quality: 'auto' },
        { fetch_format: 'auto' },
      ],
    });

    return {
      url: result.secure_url,
      publicId: result.public_id,
      width: result.width,
      height: result.height,
      format: result.format,
      size: result.bytes,
    };
  } catch (error) {
    console.error('Error uploading image to Cloudinary:', error);
    throw error;
  }
}

/**
 * Upload de video a Cloudinary
 */
export async function uploadVideo(
  file: string,
  options: {
    folder?: string;
    publicId?: string;
  } = {}
) {
  try {
    const result = await cloudinary.uploader.upload(file, {
      folder: options.folder || CLOUDINARY_FOLDERS.videos,
      public_id: options.publicId,
      resource_type: 'video',
      chunk_size: 6000000, // 6MB chunks para archivos grandes
    });

    return {
      url: result.secure_url,
      publicId: result.public_id,
      duration: result.duration,
      width: result.width,
      height: result.height,
      format: result.format,
      size: result.bytes,
    };
  } catch (error) {
    console.error('Error uploading video to Cloudinary:', error);
    throw error;
  }
}

/**
 * Eliminar archivo de Cloudinary
 */
export async function deleteFile(publicId: string, resourceType: 'image' | 'video' | 'raw' = 'image') {
  try {
    const result = await cloudinary.uploader.destroy(publicId, {
      resource_type: resourceType,
    });

    return result;
  } catch (error) {
    console.error('Error deleting file from Cloudinary:', error);
    throw error;
  }
}

/**
 * Generar URL de imagen con transformaciones
 */
export function getImageUrl(
  publicId: string,
  options: {
    width?: number;
    height?: number;
    crop?: string;
    quality?: string | number;
    format?: string;
  } = {}
) {
  return cloudinary.url(publicId, {
    width: options.width,
    height: options.height,
    crop: options.crop || 'fill',
    quality: options.quality || 'auto',
    fetch_format: options.format || 'auto',
    secure: true,
  });
}

/**
 * Validar tipo de archivo
 */
export function validateFileType(file: File, allowedTypes: string[]): boolean {
  return allowedTypes.includes(file.type);
}

/**
 * Validar tamaño de archivo
 */
export function validateFileSize(file: File, maxSize: number): boolean {
  return file.size <= maxSize;
}

/**
 * Obtener firma para upload directo desde cliente
 */
export function getUploadSignature(params: any) {
  const timestamp = Math.round(new Date().getTime() / 1000);

  const signature = cloudinary.utils.api_sign_request(
    {
      timestamp,
      ...params,
    },
    process.env.CLOUDINARY_API_SECRET!
  );

  return {
    signature,
    timestamp,
    apiKey: process.env.CLOUDINARY_API_KEY,
    cloudName: process.env.NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME,
  };
}
