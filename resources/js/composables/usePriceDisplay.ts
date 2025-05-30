import { computed, unref, type MaybeRef } from 'vue';

/**
 * Returns a reactive, human-friendly price dislay. Drops .00 on whole prices (i.e. 100.00 -> 100).
 * Shows exactly two decimal places otherwise (i.e. 123.45 -> 123.45)
 *
 * @param {MaybeRef<number|string>} price raw number or numeric stringm or a ref to one
 * @returns {ComputedRef<string>} computed string
 */
export function usePriceDisplay(price: MaybeRef<number | string>) {
  return computed(() => {
    const raw = unref(price);
    const n = typeof raw === 'string' ? parseFloat(raw) : raw;

    if (Number.isNaN(n)) {
      return '';
    }

    if (Number.isInteger(n)) {
      return n.toString();
    }

    return n.toFixed(2);
  });
}
