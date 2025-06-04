export interface CheckoutFormField {
  containerClass?: string;
  label: string;
  type: 'text' | 'textarea' | 'select';
  required: boolean;
  key: string;
}

export interface Product extends Record<string, any> {
  name: string;
  short_name: string;
  description?: string;
  type: string;
  price: number;
  is_active: boolean;
  metadata: Record<string, any>;
  id?: number;
  category_id?: number | null;
  category?: ProductCategory;
  allow_custom_price: boolean;
  cover_image?: File | string | null;
  cover_image_url?: string;
  display_name?: string;
  checkout_form_schema?: CheckoutFormField[];
}

export interface ProductCategory extends Record<string, any> {
  name: string;
  description?: string;
  id?: number | null;
  products?: Product[];
  cover_image?: File | string | null;
}
