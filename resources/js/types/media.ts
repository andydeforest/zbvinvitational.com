export interface MediaItem {
  id: number;
  uuid: string;
  file_name: string;
  mime_type: string;
  original_url: string;
}

interface GalleryItem {
  id: number;
  year: number;
  media: MediaItem[];
}
