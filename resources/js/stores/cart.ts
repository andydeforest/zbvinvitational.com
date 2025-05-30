import { defineStore } from 'pinia';

function loadFromStorage(): CartItem[] {
  try {
    const raw = localStorage.getItem('cart');
    return raw ? JSON.parse(raw) : [];
  } catch {
    return [];
  }
}

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: loadFromStorage() as CartItem[]
  }),

  getters: {
    totalItems: (state) => state.items.reduce((acc, item) => acc + item.quantity, 0),
    totalPrice: (state) => state.items.reduce((acc, item) => acc + item.quantity * item.product.price, 0),
    totalPriceDollars: (state) =>
      state.items.reduce((acc, item) => acc + item.quantity * item.product.price_dollars, 0),
    isEmpty: (state) => state.items.length === 0
  },

  actions: {
    saveToStorage() {
      localStorage.setItem('cart', JSON.stringify(this.items));
    },

    addItem(newItem: Product) {
      const existing = this.items.find((item) => item.product.id === newItem.id);

      if (existing) {
        existing.quantity += 1;
      } else {
        this.items.push({ product: newItem, quantity: 1 });
      }

      this.saveToStorage();
    },

    updateQuantity(cartItem: CartItem, quantity: number) {
      if (typeof cartItem.product.id !== 'number') {
        console.warn('Cannot update cart quantity: missing id');
        return;
      }

      const item = this.items.find((item) => item.product.id === cartItem.product.id);
      if (item) {
        item.quantity = quantity;
        if (item.quantity <= 0) {
          this.removeItem(cartItem);
        } else {
          this.saveToStorage();
        }
      }
    },

    removeItem(cartItem: CartItem) {
      if (typeof cartItem.product.id !== 'number') {
        console.warn('Cannot remove cart item: missing id');
        return;
      }

      this.items = this.items.filter((item) => item.product.id !== cartItem.product.id);
      this.saveToStorage();
    },

    clearCart() {
      this.items = [];
      this.saveToStorage();
    }
  }
});
