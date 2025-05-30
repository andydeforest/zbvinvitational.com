import { useToast } from 'vue-toast-notification';

import type {
  ToastPropsWithMessage as ToastOptions,
  ToastProps as PluginOptions
} from 'vue-toast-notification/types/toast';

export function useToastNotification(globalOptions: PluginOptions = {}) {
  const toast = useToast({
    ...globalOptions,
    position: 'top-right'
  });

  /** Show a generic toast */
  function notify(
    message: string,
    options: Omit<ToastOptions, 'message'> = {},
    type: 'success' | 'info' | 'warning' | 'error' = 'info'
  ) {
    toast.open({ message, type, ...options });
  }

  function info(message: string, options: Omit<ToastOptions, 'message'> = {}) {
    toast.info(message, options);
  }

  function success(message: string, options: Omit<ToastOptions, 'message'> = {}) {
    toast.success(message, options);
  }

  function warning(message: string, options: Omit<ToastOptions, 'message'> = {}) {
    toast.warning(message, options);
  }

  function error(message: string, options: Omit<ToastOptions, 'message'> = {}) {
    toast.error(message, options);
  }

  return {
    notify,
    info,
    success,
    warning,
    error
  };
}
