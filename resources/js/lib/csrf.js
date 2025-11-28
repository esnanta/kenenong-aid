/**
 * Utility functions for CSRF token handling
 */

/**
 * Get CSRF token from Inertia shared props or meta tag
 * This ensures we always get the most current token
 */
export function getCsrfToken() {
  // Try to get from Inertia page props first (more reliable)
  if (typeof window !== 'undefined' && window.__INERTIA_PAGE__) {
    const page = window.__INERTIA_PAGE__;
    if (page?.props?.csrfToken) {
      return page.props.csrfToken;
    }
  }
  
  // Fallback to meta tag
  const meta = document.querySelector('meta[name="csrf-token"]');
  return meta ? meta.getAttribute('content') : null;
}

/**
 * Get CSRF parameter name from Inertia shared props or meta tag
 */
export function getCsrfParam() {
  // Try to get from Inertia page props first (more reliable)
  if (typeof window !== 'undefined' && window.__INERTIA_PAGE__) {
    const page = window.__INERTIA_PAGE__;
    if (page?.props?.csrfParam) {
      return page.props.csrfParam;
    }
  }
  
  // Fallback to meta tag
  const meta = document.querySelector('meta[name="csrf-param"]');
  return meta ? meta.getAttribute('content') : null;
}

/**
 * Add CSRF token to form data
 * @param {Object|FormData} data - The form data
 * @returns {Object|FormData} - Data with CSRF token added
 */
export function addCsrfToData(data) {
  const csrfToken = getCsrfToken();
  const csrfParam = getCsrfParam();
  
  if (!csrfToken || !csrfParam) {
    console.warn('CSRF token or param not found!', { csrfToken, csrfParam });
    return data;
  }

  if (data instanceof FormData) {
    // Check if already added
    if (!data.has(csrfParam)) {
      data.append(csrfParam, csrfToken);
    }
    return data;
  } else if (typeof data === 'object' && data !== null) {
    return {
      ...data,
      [csrfParam]: csrfToken,
    };
  }
  
  return data;
}
