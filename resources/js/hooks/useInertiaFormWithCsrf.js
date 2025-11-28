import { useForm } from '@inertiajs/react';

/**
 * Wrapper around Inertia's useForm that automatically includes CSRF token
 */
export function useInertiaFormWithCsrf(initialData = {}) {
  const form = useForm(initialData);

  // Get CSRF token from meta tag
  const getCsrfToken = () => {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : null;
  };

  const getCsrfParam = () => {
    const meta = document.querySelector('meta[name="csrf-param"]');
    return meta ? meta.getAttribute('content') : null;
  };

  // Wrap the post, put, patch, and delete methods to include CSRF token
  const originalPost = form.post.bind(form);
  const originalPut = form.put.bind(form);
  const originalPatch = form.patch.bind(form);
  const originalDelete = form.delete.bind(form);

  const addCsrfToData = (data) => {
    const csrfToken = getCsrfToken();
    const csrfParam = getCsrfParam();
    
    if (!csrfToken || !csrfParam) {
      return data;
    }

    if (data instanceof FormData) {
      data.append(csrfParam, csrfToken);
      return data;
    } else if (typeof data === 'object' && data !== null) {
      return {
        ...data,
        [csrfParam]: csrfToken,
      };
    }
    
    return data;
  };

  form.post = (url, options = {}) => {
    // If data is provided in options, use it; otherwise use form.data
    const dataToSend = options.data !== undefined ? options.data : form.data;
    const data = addCsrfToData(dataToSend);
    return originalPost(url, { ...options, data });
  };

  form.put = (url, options = {}) => {
    const dataToSend = options.data !== undefined ? options.data : form.data;
    const data = addCsrfToData(dataToSend);
    return originalPut(url, { ...options, data });
  };

  form.patch = (url, options = {}) => {
    const dataToSend = options.data !== undefined ? options.data : form.data;
    const data = addCsrfToData(dataToSend);
    return originalPatch(url, { ...options, data });
  };

  form.delete = (url, options = {}) => {
    const dataToSend = options.data !== undefined ? options.data : form.data;
    const data = addCsrfToData(dataToSend);
    return originalDelete(url, { ...options, data });
  };

  return form;
}

