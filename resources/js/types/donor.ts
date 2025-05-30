export interface Donor {
  id?: number;
  name: string;
}

export interface DonorLogo {
  id: number;
  name: string;
  created_at: string;
  updated_at: string;
  media: MediaItem;
}
