export interface CartItem {
  product: Product;
  quantity: number;
}

export interface OrderItem {
  id: number;
  order_id: number;
  product_id: number;
  order: Order;
  quantity: number;
  unit_price_cents: number;
  metadata: Record<string, any>;
  created_at: string;
  updated_at: string;
  product?: Product;
}

export interface Order {
  id: number;
  public_id: string;
  status: 'pending' | 'paid' | string;
  total_cents: number;
  total_dollars: number;
  first_name: string;
  last_name: string;
  address: string;
  city: string;
  state: string;
  zip: string;
  phone: string;
  email: string;
  notes: string | null;
  stripe_payment_intent_id: string;
  stripe_charge_id: string | null;
  paid_at: string | null;
  created_at: string;
  updated_at: string;
  items: OrderItem[];
}
