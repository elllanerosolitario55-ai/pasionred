'use client';

import { useState, useRef, ChangeEvent } from 'react';
import { Upload, X, Image as ImageIcon, Video, Loader2, CheckCircle } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';

interface FileUploadProps {
  type?: 'avatar' | 'cover' | 'post' | 'video';
  accept?: string;
  maxSize?: number;
  onUploadComplete?: (fileUrl: string, fileData: any) => void;
  onUploadError?: (error: string) => void;
  className?: string;
  multiple?: boolean;
}

export function FileUpload({
  type = 'post',
  accept = 'image/*',
  maxSize = 10 * 1024 * 1024, // 10MB por defecto
  onUploadComplete,
  onUploadError,
  className,
  multiple = false,
}: FileUploadProps) {
  const [files, setFiles] = useState<File[]>([]);
  const [previews, setPreviews] = useState<string[]>([]);
  const [uploading, setUploading] = useState(false);
  const [uploadProgress, setUploadProgress] = useState(0);
  const [uploadedFiles, setUploadedFiles] = useState<any[]>([]);
  const inputRef = useRef<HTMLInputElement>(null);

  const handleFileSelect = (e: ChangeEvent<HTMLInputElement>) => {
    const selectedFiles = Array.from(e.target.files || []);

    if (selectedFiles.length === 0) return;

    // Validar tamaño
    const invalidFiles = selectedFiles.filter(file => file.size > maxSize);
    if (invalidFiles.length > 0) {
      onUploadError?.(
        `Algunos archivos son demasiado grandes. Máximo: ${maxSize / 1024 / 1024}MB`
      );
      return;
    }

    setFiles(selectedFiles);

    // Generar previews
    const newPreviews = selectedFiles.map(file => URL.createObjectURL(file));
    setPreviews(newPreviews);
  };

  const handleUpload = async () => {
    if (files.length === 0) return;

    setUploading(true);
    setUploadProgress(0);

    const uploaded: any[] = [];

    try {
      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', type);

        const response = await fetch('/api/upload', {
          method: 'POST',
          body: formData,
        });

        if (!response.ok) {
          const error = await response.json();
          throw new Error(error.error || 'Error al subir archivo');
        }

        const data = await response.json();
        uploaded.push(data.file);

        // Actualizar progreso
        setUploadProgress(Math.round(((i + 1) / files.length) * 100));

        // Callback por cada archivo
        if (onUploadComplete) {
          onUploadComplete(data.file.url, data.file);
        }
      }

      setUploadedFiles(uploaded);

      // Limpiar después de 2 segundos
      setTimeout(() => {
        handleClear();
      }, 2000);

    } catch (error: any) {
      console.error('Error uploading files:', error);
      onUploadError?.(error.message);
    } finally {
      setUploading(false);
    }
  };

  const handleClear = () => {
    setFiles([]);
    setPreviews([]);
    setUploadProgress(0);
    setUploadedFiles([]);
    if (inputRef.current) {
      inputRef.current.value = '';
    }
  };

  const handleRemoveFile = (index: number) => {
    const newFiles = files.filter((_, i) => i !== index);
    const newPreviews = previews.filter((_, i) => i !== index);
    setFiles(newFiles);
    setPreviews(newPreviews);
  };

  const isImage = accept.includes('image');
  const isVideo = accept.includes('video');

  return (
    <div className={cn('space-y-4', className)}>
      {/* Área de drop/selección */}
      <div
        onClick={() => inputRef.current?.click()}
        className={cn(
          'border-2 border-dashed rounded-lg p-8 text-center cursor-pointer transition-colors',
          'hover:border-pink-500 hover:bg-pink-50/50',
          files.length > 0 ? 'border-pink-500 bg-pink-50/50' : 'border-slate-300'
        )}
      >
        <input
          ref={inputRef}
          type="file"
          accept={accept}
          multiple={multiple}
          onChange={handleFileSelect}
          className="hidden"
        />

        <div className="flex flex-col items-center gap-2">
          {isImage && <ImageIcon className="h-12 w-12 text-slate-400" />}
          {isVideo && <Video className="h-12 w-12 text-slate-400" />}
          {!isImage && !isVideo && <Upload className="h-12 w-12 text-slate-400" />}

          <div>
            <p className="text-sm font-medium text-slate-900">
              Click para seleccionar {multiple ? 'archivos' : 'archivo'}
            </p>
            <p className="text-xs text-slate-500 mt-1">
              Máximo {maxSize / 1024 / 1024}MB por archivo
            </p>
          </div>
        </div>
      </div>

      {/* Previews */}
      {previews.length > 0 && (
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          {previews.map((preview, index) => (
            <div key={index} className="relative group">
              <div className="aspect-square rounded-lg overflow-hidden bg-slate-100">
                {isImage && (
                  <img
                    src={preview}
                    alt={`Preview ${index + 1}`}
                    className="w-full h-full object-cover"
                  />
                )}
                {isVideo && (
                  <video
                    src={preview}
                    className="w-full h-full object-cover"
                  />
                )}
              </div>

              {!uploading && !uploadedFiles.length && (
                <button
                  onClick={() => handleRemoveFile(index)}
                  className="absolute top-2 right-2 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                >
                  <X className="h-4 w-4" />
                </button>
              )}

              {uploadedFiles[index] && (
                <div className="absolute inset-0 bg-green-500/90 flex items-center justify-center rounded-lg">
                  <CheckCircle className="h-8 w-8 text-white" />
                </div>
              )}
            </div>
          ))}
        </div>
      )}

      {/* Barra de progreso */}
      {uploading && (
        <div className="space-y-2">
          <div className="flex items-center justify-between text-sm">
            <span className="text-slate-600">Subiendo...</span>
            <span className="font-medium text-pink-600">{uploadProgress}%</span>
          </div>
          <div className="h-2 bg-slate-200 rounded-full overflow-hidden">
            <div
              className="h-full bg-gradient-to-r from-pink-600 to-blue-400 transition-all duration-300"
              style={{ width: `${uploadProgress}%` }}
            />
          </div>
        </div>
      )}

      {/* Botones de acción */}
      {files.length > 0 && !uploadedFiles.length && (
        <div className="flex gap-2">
          <Button
            onClick={handleUpload}
            disabled={uploading}
            className="flex-1 bg-gradient-to-r from-pink-600 to-blue-400 hover:from-pink-700 hover:to-blue-500"
          >
            {uploading ? (
              <>
                <Loader2 className="h-4 w-4 mr-2 animate-spin" />
                Subiendo...
              </>
            ) : (
              <>
                <Upload className="h-4 w-4 mr-2" />
                Subir {files.length} {files.length === 1 ? 'archivo' : 'archivos'}
              </>
            )}
          </Button>

          {!uploading && (
            <Button
              onClick={handleClear}
              variant="outline"
            >
              <X className="h-4 w-4 mr-2" />
              Cancelar
            </Button>
          )}
        </div>
      )}
    </div>
  );
}
