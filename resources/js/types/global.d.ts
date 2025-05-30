import type { NavItem as _NavItem, SocialItem as _SocialItem } from './nav';
import type { Product as _Product, ProductCategory as _ProductCategory } from './products';
import type { CartItem as _CartItem, OrderItem as _OrderItem, Order as _Order } from './shop';
import type { Donor as _Donor, DonorLogo as _DonorLogo } from './donor';
import type { MediaItem as _MediaItem } from './media';
import type { ResourceCollection as _ResourceCollection } from './resource';
import type { Golfer as _Golfer } from './participants';
import type { Contact as _Contact } from './contact';

declare global {
  type NavItem = _NavItem;
  type SocialItem = _SocialItem;
  type Product = _Product;
  type ProductCategory = _ProductCategory;
  type CartItem = _CartItem;
  type OrderItem = _OrderItem;
  type Order = _Order;
  type Donor = _Donor;
  type DonorLogo = _DonorLogo;
  type MediaItem = _MediaItem;
  type ResourceCollection<T> = _ResourceCollection<T>;
  type Golfer = _Golfer;
  type Contact = _Contact;
}

export {};
